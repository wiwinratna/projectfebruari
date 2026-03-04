<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessCardConfig;
use App\Models\AccreditationMapping;
use App\Models\VenueAccess;
use App\Models\ZoneAccessCode;
use App\Models\TransportationCode;
use App\Models\AccommodationCode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccessCardConfigController extends Controller
{
    private function requireAdmin()
    {
        if (!session('admin_authenticated')) return redirect('/admin/login');
        return null;
    }

    private function eventId(): ?int
    {
        return session('admin_event_id') ? (int) session('admin_event_id') : null;
    }

    public function index()
    {
        if ($r = $this->requireAdmin()) return $r;
        $eventId = $this->eventId();
        if (!$eventId) return back()->with('error', 'Akun admin belum ditugaskan ke event manapun.');

        $configs = AccessCardConfig::with([
                'mapping:id,event_id,nama_akreditasi,warna,keterangan',
                'venueAccesses:id,nama_vanue',
                'zoneAccessCodes:id,kode_zona',
                'transportationCode:id,kode',
                'accommodationCode:id,kode',
            ])
            ->where('event_id', $eventId)
            ->orderByDesc('id')
            ->get();

        return view('menu.events.card-configs.index', compact('configs'));
    }

    public function create()
    {
        if ($r = $this->requireAdmin()) return $r;

        $eventId = $this->eventId();
        if (!$eventId) return back()->with('error', 'Akun admin belum ditugaskan ke event manapun.');

        $mappings = AccreditationMapping::where('event_id', $eventId)
            ->orderBy('nama_akreditasi')
            ->get(['id','nama_akreditasi','warna','keterangan']);

        $transportationCodes = TransportationCode::where('event_id', $eventId)
            ->orderBy('kode')
            ->get();

        $accommodationCodes = AccommodationCode::where('event_id', $eventId)
            ->orderBy('kode')
            ->get();

        // ⚠️ kolom kamu di DB: nama_vanue (bukan nama_venue)
        $venueAccesses = VenueAccess::where('event_id', $eventId)
            ->orderBy('nama_vanue')
            ->get();

        $zoneAccessCodes = ZoneAccessCode::where('event_id', $eventId)
            ->orderBy('kode_zona')
            ->get();

        return view('menu.events.card-configs.create', compact(
            'mappings',
            'transportationCodes',
            'accommodationCodes',
            'venueAccesses',
            'zoneAccessCodes'
        ))->with([
            'config' => null,
            'selectedVenueAccesses' => [],
            'selectedZoneAccessCodes' => [],
        ]);
    }

    public function store(Request $request)
    {
        if ($r = $this->requireAdmin()) return $r;
        $eventId = $this->eventId();
        if (!$eventId) return back()->with('error', 'Akun admin belum ditugaskan ke event manapun.');

        $data = $request->validate([
            'accreditation_mapping_id' => [
                'required','integer',
                Rule::exists('accreditation_mappings', 'id')->where('event_id', $eventId),
                Rule::unique('access_card_configs', 'accreditation_mapping_id')->where(fn($q) => $q->where('event_id', $eventId)),
            ],
            'transportation_code_id' => ['nullable','integer', Rule::exists('transportation_codes','id')->where('event_id',$eventId)],
            'accommodation_code_id'  => ['nullable','integer', Rule::exists('accommodation_codes','id')->where('event_id',$eventId)],
            'keterangan' => ['nullable','string','max:1000'],

            'venue_access_ids' => ['nullable','array'],
            'venue_access_ids.*' => ['integer', Rule::exists('venue_accesses','id')->where('event_id',$eventId)],

            'zone_access_code_ids' => ['nullable','array'],
            'zone_access_code_ids.*' => ['integer', Rule::exists('zone_access_codes','id')->where('event_id',$eventId)],
        ], [
            'accreditation_mapping_id.unique' => 'Konfigurasi untuk mapping ini sudah ada.',
        ]);

        $config = AccessCardConfig::create([
            'event_id' => $eventId,
            'accreditation_mapping_id' => $data['accreditation_mapping_id'],
            'transportation_code_id' => $data['transportation_code_id'] ?? null,
            'accommodation_code_id' => $data['accommodation_code_id'] ?? null,
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        $config->venueAccesses()->sync($data['venue_access_ids'] ?? []);
        $config->zoneAccessCodes()->sync($data['zone_access_code_ids'] ?? []);

        return redirect()->route('admin.card-configs.index')
            ->with('success', 'Konfigurasi kartu akses berhasil dibuat.');
    }

    public function edit(AccessCardConfig $config)
    {
        if ($r = $this->requireAdmin()) return $r;

        $eventId = $this->eventId();
        if (!$eventId) return back()->with('error', 'Akun admin belum ditugaskan ke event manapun.');

        // pastikan config ini milik event admin
        if ((int)$config->event_id !== (int)$eventId) abort(403);

        $mappings = AccreditationMapping::where('event_id', $eventId)
            ->orderBy('nama_akreditasi')
            ->get(['id','nama_akreditasi','warna','keterangan']);

        $transportationCodes = TransportationCode::where('event_id', $eventId)
            ->orderBy('kode')
            ->get();

        $accommodationCodes = AccommodationCode::where('event_id', $eventId)
            ->orderBy('kode')
            ->get();

        $venueAccesses = VenueAccess::where('event_id', $eventId)
            ->orderBy('nama_vanue')
            ->get();

        $zoneAccessCodes = ZoneAccessCode::where('event_id', $eventId)
            ->orderBy('kode_zona')
            ->get();

        $selectedVenueAccesses = $config->venueAccesses()->pluck('venue_accesses.id')->map(fn($v)=>(int)$v)->toArray();
        $selectedZoneAccessCodes = $config->zoneAccessCodes()->pluck('zone_access_codes.id')->map(fn($v)=>(int)$v)->toArray();

        return view('menu.events.card-configs.edit', compact(
            'config',
            'mappings',
            'transportationCodes',
            'accommodationCodes',
            'venueAccesses',
            'zoneAccessCodes',
            'selectedVenueAccesses',
            'selectedZoneAccessCodes'
        ));
    }

    public function update(Request $request, AccessCardConfig $config)
    {
        if ($r = $this->requireAdmin()) return $r;
        $eventId = $this->eventId();
        if (!$eventId) return back()->with('error', 'Akun admin belum ditugaskan ke event manapun.');
        if ((int)$config->event_id !== $eventId) abort(404);

        $data = $request->validate([
            'accreditation_mapping_id' => [
                'required','integer',
                Rule::exists('accreditation_mappings', 'id')->where('event_id', $eventId),
                Rule::unique('access_card_configs', 'accreditation_mapping_id')
                    ->where(fn($q) => $q->where('event_id', $eventId))
                    ->ignore($config->id),
            ],
            'transportation_code_id' => ['nullable','integer', Rule::exists('transportation_codes','id')->where('event_id',$eventId)],
            'accommodation_code_id'  => ['nullable','integer', Rule::exists('accommodation_codes','id')->where('event_id',$eventId)],
            'keterangan' => ['nullable','string','max:1000'],

            'venue_access_ids' => ['nullable','array'],
            'venue_access_ids.*' => ['integer', Rule::exists('venue_accesses','id')->where('event_id',$eventId)],

            'zone_access_code_ids' => ['nullable','array'],
            'zone_access_code_ids.*' => ['integer', Rule::exists('zone_access_codes','id')->where('event_id',$eventId)],
        ], [
            'accreditation_mapping_id.unique' => 'Konfigurasi untuk mapping ini sudah ada.',
        ]);

        $config->update([
            'accreditation_mapping_id' => $data['accreditation_mapping_id'],
            'transportation_code_id' => $data['transportation_code_id'] ?? null,
            'accommodation_code_id' => $data['accommodation_code_id'] ?? null,
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        $config->venueAccesses()->sync($data['venue_access_ids'] ?? []);
        $config->zoneAccessCodes()->sync($data['zone_access_code_ids'] ?? []);

        return redirect()->route('admin.card-configs.index')
            ->with('success', 'Konfigurasi kartu akses berhasil diperbarui.');
    }

    public function destroy(AccessCardConfig $config)
    {
        if ($r = $this->requireAdmin()) return $r;
        $eventId = $this->eventId();
        if ((int)$config->event_id !== $eventId) abort(404);

        $config->delete();

        return response()->json(['success' => true, 'message' => 'Konfigurasi berhasil dihapus.']);
    }
}