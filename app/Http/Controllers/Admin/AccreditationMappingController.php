<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccreditationMapping;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class AccreditationMappingController extends Controller
{
    private function requireAdmin()
    {
        if (!session('admin_authenticated')) {
            return redirect('/admin/login');
        }
        return null;
    }

    private function adminEventId()
    {
        return session('admin_event_id');
    }

    // ✅ helper untuk sync pivot dengan event_id
    private function syncPivot(int $eventId, int $mappingId, array $jobCategoryIds): void
    {
        DB::table('accreditation_mapping_job_category')
            ->where('event_id', $eventId)
            ->where('accreditation_mapping_id', $mappingId)
            ->delete();

        $rows = [];
        foreach (collect($jobCategoryIds)->unique() as $jcId) {
            $rows[] = [
                'event_id' => $eventId,
                'accreditation_mapping_id' => $mappingId,
                'job_category_id' => (int) $jcId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($rows)) {
            DB::table('accreditation_mapping_job_category')->insert($rows);
        }
    }

    public function index()
    {
        if ($r = $this->requireAdmin()) return $r;

        $eventId = $this->adminEventId();
        if (!$eventId) return back()->with('error', 'Akun admin belum ditugaskan ke event manapun.');

        $mappings = AccreditationMapping::where('event_id', $eventId)
            ->orderBy('nama_akreditasi')
            ->get();

        // mapping_id => [job category names]
        $mappedByMappingId = [];
        foreach ($mappings as $m) {
            $names = DB::table('accreditation_mapping_job_category as p')
                ->join('job_categories as jc', 'jc.id', '=', 'p.job_category_id')
                ->where('p.event_id', $eventId)
                ->where('p.accreditation_mapping_id', $m->id)
                ->orderBy('jc.name')
                ->pluck('jc.name')
                ->toArray();

            $mappedByMappingId[$m->id] = $names;
        }

        return view('menu.admin.accreditation-mapping.index', [
            'mappings' => $mappings,
            'mappedByMappingId' => $mappedByMappingId,
        ]);
    }

    public function create()
    {
        if ($r = $this->requireAdmin()) return $r;

        $eventId = $this->adminEventId();
        if (!$eventId) return back()->with('error', 'Akun admin belum ditugaskan ke event manapun.');

        // ✅ hide job categories yang sudah dipakai di event ini
        $used = DB::table('accreditation_mapping_job_category')
            ->where('event_id', $eventId)
            ->pluck('job_category_id')
            ->unique()
            ->toArray();

        $jobCategories = JobCategory::with('workerType')
            ->whereNotIn('id', $used)
            ->orderBy('name')
            ->get();

        return view('menu.admin.accreditation-mapping.create', [
            'mapping' => null,
            'jobCategories' => $jobCategories,
            'selectedIds' => [],
        ]);
    }

    public function store(Request $request)
    {
        if ($r = $this->requireAdmin()) return $r;

        $eventId = $this->adminEventId();
        if (!$eventId) return back()->with('error', 'Akun admin belum ditugaskan ke event manapun.');

        $request->merge([
        'nama_akreditasi' => strtoupper(trim($request->nama_akreditasi)),
        ]);
        $data = $request->validate([
            'nama_akreditasi' => [
                'required','string','max:50',
                Rule::unique('accreditation_mappings', 'nama_akreditasi')
                    ->where(fn($q) => $q->where('event_id', $eventId)),
            ],
            'warna' => ['required','string','max:20'],
            'keterangan' => ['nullable','string','max:1000'],
            'job_category_ids' => ['required','array','min:1'],
            'job_category_ids.*' => ['integer','exists:job_categories,id'],
        ], [
            'nama_akreditasi.unique' => 'Nama akreditasi sudah ada untuk event ini.',
        ]);

        DB::transaction(function () use ($data, $eventId, &$mapping) {
            $mapping = AccreditationMapping::create([
                'event_id' => $eventId,
                'nama_akreditasi' => $data['nama_akreditasi'],
                'warna' => $data['warna'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // ✅ simpan pivot pakai event_id
            $this->syncPivot($eventId, $mapping->id, $data['job_category_ids']);
        });

        return redirect()
            ->route('admin.accreditation-mapping.index')
            ->with('success', 'Accreditation mapping created.');
    }

    public function edit($id)
    {
        if ($r = $this->requireAdmin()) return $r;

        $eventId = $this->adminEventId();
        if (!$eventId) return back()->with('error', 'Akun admin belum ditugaskan ke event manapun.');

        $mapping = AccreditationMapping::where('event_id', $eventId)->findOrFail($id);

        // selected milik mapping ini
        $selectedIds = DB::table('accreditation_mapping_job_category')
            ->where('event_id', $eventId)
            ->where('accreditation_mapping_id', $mapping->id)
            ->pluck('job_category_id')
            ->map(fn($v) => (int)$v)
            ->toArray();

        // ✅ hide yang dipakai mapping lain
        $usedByOthers = DB::table('accreditation_mapping_job_category')
            ->where('event_id', $eventId)
            ->where('accreditation_mapping_id', '!=', $mapping->id)
            ->pluck('job_category_id')
            ->unique()
            ->toArray();

        $jobCategories = JobCategory::with('workerType')
            ->whereNotIn('id', $usedByOthers)
            ->orderBy('name')
            ->get();

        return view('menu.admin.accreditation-mapping.edit', [
            'mapping' => $mapping,
            'acc' => $mapping, // alias kalau blade kamu masih pakai $acc
            'jobCategories' => $jobCategories,
            'selectedIds' => $selectedIds,
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($r = $this->requireAdmin()) return $r;

        $eventId = $this->adminEventId();
        if (!$eventId) return back()->with('error', 'Akun admin belum ditugaskan ke event manapun.');

        $mapping = AccreditationMapping::where('event_id', $eventId)->findOrFail($id);
        $request->merge([
        'nama_akreditasi' => strtoupper(trim($request->nama_akreditasi)),
        ]);
        $data = $request->validate([
            'nama_akreditasi' => [
                'required','string','max:50',
                Rule::unique('accreditation_mappings', 'nama_akreditasi')
                    ->where(fn($q) => $q->where('event_id', $eventId))
                    ->ignore($mapping->id),
            ],
            'warna' => ['required','string','max:20'],
            'keterangan' => ['nullable','string','max:1000'],
            'job_category_ids' => ['required','array','min:1'],
            'job_category_ids.*' => ['integer','exists:job_categories,id'],
        ], [
            'nama_akreditasi.unique' => 'Nama akreditasi sudah ada untuk event ini.',
        ]);

        DB::transaction(function () use ($mapping, $data, $eventId) {
            $mapping->update([
                'nama_akreditasi' => $data['nama_akreditasi'],
                'warna' => $data['warna'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // ✅ update pivot pakai event_id
            $this->syncPivot($eventId, $mapping->id, $data['job_category_ids']);
        });

        return redirect()
            ->route('admin.accreditation-mapping.index')
            ->with('success', 'Accreditation mapping updated.');
    }
}