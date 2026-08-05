<?php

namespace App\Http\Controllers\Admin\Card;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Card;
use App\Models\WorkerOpening;
use App\Models\User;
use App\Models\JobCategory;
use App\Models\AccreditationMapping;
use App\Models\AccessCardConfig;
use App\Services\Card\CardAccessResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CardController extends Controller
{
    public function index(Request $request, CardAccessResolver $resolver)
    {
        $eventId = session('admin_event_id');

        $q = trim((string) $request->get('q', ''));
        $statusCard = $request->get('card_status');

        // --- ✅ Tentukan sumber foto yang valid ---
        $photoSelect = 'NULL as applicant_photo';
        $extraJoin = null;

        // 1) Kalau ternyata foto ada langsung di users.*
        foreach (['profile_photo', 'photo', 'avatar', 'profile_photo_path', 'photo_path'] as $col) {
            if (Schema::hasColumn('users', $col)) {
                $photoSelect = "users.$col as applicant_photo";
                break;
            }
        }

        // 2) Kalau foto adanya di tabel lain (misal: user_profiles)
        if ($photoSelect === 'NULL as applicant_photo') {
            // Cek beberapa kemungkinan nama tabel profil
            $profileTables = ['profiles', 'user_profiles', 'profiles_users'];

            foreach ($profileTables as $tbl) {
                if (Schema::hasTable($tbl)) {
                    // cari kolom foto yang umum
                    foreach (['profile_photo', 'photo', 'avatar', 'photo_path'] as $pcol) {
                        if (Schema::hasColumn($tbl, $pcol)) {
                            $extraJoin = [$tbl, "$tbl.user_id", '=', 'users.id'];
                            $photoSelect = "$tbl.$pcol as applicant_photo";
                            break 2;
                        }
                    }
                }
            }
        }

        $appsQuery = Application::query()
            ->select([
                'applications.id as application_id',
                'applications.user_id',
                'applications.worker_opening_id',
                'applications.status as application_status',
                'worker_openings.title as opening_title',
                'worker_openings.job_category_id',
                'worker_openings.event_id',
                'users.name as applicant_name',
                'users.email as applicant_email',
                DB::raw($photoSelect), // ✅ kolom foto aman
            ])
            ->join('worker_openings', 'worker_openings.id', '=', 'applications.worker_opening_id')
            ->join('users', 'users.id', '=', 'applications.user_id');

        // ✅ join profil hanya kalau tabelnya beneran ada
        if ($extraJoin) {
            [$tbl, $left, $op, $right] = $extraJoin;
            $appsQuery->leftJoin($tbl, $left, $op, $right);
        }

        $appsQuery
            ->where('worker_openings.event_id', $eventId)
            ->where('applications.status', 'approved')
            ->orderByDesc('applications.id');

        if ($q !== '') {
            $appsQuery->where(function ($w) use ($q) {
                $w->where('users.name', 'like', "%{$q}%")
                    ->orWhere('users.email', 'like', "%{$q}%")
                    ->orWhere('worker_openings.title', 'like', "%{$q}%");
            });
        }

        $apps = $appsQuery->get();

        // Ambil semua job_category_id yang kepake
        $jobCategoryIds = $apps->pluck('job_category_id')->unique()->filter()->values();

        // JobCategory global (buat tampil nama)
        $jobCategories = JobCategory::whereIn('id', $jobCategoryIds)->get()->keyBy('id');

        // Mapping job_category -> accreditation_mapping_id (via pivot mapping-jobcategory kamu)
        // Aku asumsi relasi pivotnya: accreditation_mapping_job_categories (sesuaikan kalau beda)
        // Cara aman: query pivot langsung
        // Mapping job_category -> mapping detail (nama+warna)
        $mappingRows = DB::table('accreditation_mapping_job_category')
            ->join('accreditation_mappings', 'accreditation_mappings.id', '=', 'accreditation_mapping_job_category.accreditation_mapping_id')
            ->select([
                'accreditation_mapping_job_category.job_category_id',
                'accreditation_mapping_job_category.accreditation_mapping_id as mapping_id',
                'accreditation_mappings.nama_akreditasi',
                'accreditation_mappings.warna',
            ])
            ->where('accreditation_mapping_job_category.event_id', $eventId)
            ->whereIn('accreditation_mapping_job_category.job_category_id', $jobCategoryIds)
            ->get();

        $mappingByJobCategory = $mappingRows->keyBy('job_category_id');

        // Load cards existing untuk aplikasi yg tampil
        $appIds = $apps->pluck('application_id')->values();

        $cardsByAppId = Card::where('event_id', $eventId)
            ->whereIn('application_id', $appIds)
            ->get()
            ->keyBy('application_id');

        // AUTO-PROVISION: buat draft card kalau belum ada (idempotent)
        // + seed default overrides (riwayat)
        foreach ($apps as $a) {
            if ($cardsByAppId->has($a->application_id))
                continue;

            $jobCatId = (int) $a->job_category_id;
            $map = $mappingByJobCategory->get($jobCatId);

            // kalau belum ada mapping untuk job category ini, skip (biar admin benerin mapping dulu)
            if (!$map)
                continue;

            $config = AccessCardConfig::where('accreditation_mapping_id', $map->mapping_id)->first();

            $card = Card::firstOrCreate(
                ['event_id' => $eventId, 'application_id' => $a->application_id],
                [
                    'accreditation_mapping_id' => $map->mapping_id,
                    'access_card_config_id' => $config?->id,
                    'status' => 'draft',
                    'snapshot' => [
                        'name' => $a->applicant_name,
                        'email' => $a->applicant_email,
                        'opening_title' => $a->opening_title,
                        'job_category_id' => $jobCatId,
                        'job_category_name' => $jobCategories->get($jobCatId)->name ?? null,
                        'mapping_name' => $map->nama_akreditasi ?? null,
                        'mapping_color' => $map->warna ?? null,
                    ],
                ]
            );

            // seed default overrides biar riwayat terlihat
            $resolver->seedDefaultOverrides($card);

            $cardsByAppId->put($a->application_id, $card);
        }

        // Apply filter card status jika diminta
        if ($statusCard) {
            $apps = $apps->filter(function ($a) use ($cardsByAppId, $statusCard) {
                $card = $cardsByAppId->get($a->application_id);
                return $card && $card->status === $statusCard;
            })->values();
        }

        $importedCardsQuery = Card::with(['cardRecipient', 'mapping'])
            ->where('event_id', $eventId)
            ->whereNotNull('card_recipient_id');

        if ($q !== '') {
            $importedCardsQuery->whereHas('cardRecipient', function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('population', 'like', "%{$q}%");
            });
        }

        if ($statusCard) {
            $importedCardsQuery->where('status', $statusCard);
        }

        $importedCards = $importedCardsQuery->orderByDesc('id')->get();

        return view('menu.admin.card.index', [
            'apps' => $apps,
            'importedCards' => $importedCards,
            'cardsByAppId' => $cardsByAppId,
            'jobCategories' => $jobCategories,
            'mappingByJobCategory' => $mappingByJobCategory,
            'eventId' => $eventId,
            'q' => $q,
            'statusCard' => $statusCard,
        ]);
    }

    public function downloadImportTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CardTemplateExport, 'card_import_template.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $data = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\CardImport, $request->file('excel_file'));
            if (empty($data) || empty($data[0])) {
                return back()->with('error', 'File Excel kosong atau format tidak sesuai.');
            }

            session(['card_import_data' => $data[0]]);
            return redirect()->route('admin.cards.import.preview');
        } catch (\Exception $e) {
            return back()->with('error', 'Error reading Excel: ' . $e->getMessage());
        }
    }

    public function importPreview()
    {
        $data = session('card_import_data');
        if (!$data) {
            return redirect()->route('admin.cards.index')->with('error', 'Tidak ada data import.');
        }

        $eventId = session('admin_event_id');
        $validated = [];

        foreach ($data as $row) {
            $name       = trim((string)($row['name'] ?? $row['nama_lengkap'] ?? ''));
            $population = trim((string)($row['population'] ?? $row['jabatan'] ?? ''));
            $category   = trim((string)($row['category'] ?? ''));
            $venue      = trim((string)($row['venue_access'] ?? $row['venue'] ?? ''));
            $zone       = trim((string)($row['zone_access'] ?? $row['zone'] ?? ''));
            $transport  = trim((string)($row['transport'] ?? ''));

            if ($name === '') {
                continue; // Skip silently for completely empty names
            }

            // Lookup mapping berdasarkan nama kategori akreditasi (scoped ke event admin)
            // Menggunakan fungsi LOWER() dan TRIM() untuk memastikan tidak peka huruf besar-kecil
            $mapping = \Illuminate\Support\Facades\DB::table('accreditation_mappings')
                ->where('event_id', $eventId)
                ->whereRaw('LOWER(TRIM(nama_akreditasi)) = ?', [strtolower(trim($category))])
                ->select('id as accreditation_mapping_id', 'nama_akreditasi', 'warna')
                ->first();

            $validated[] = [
                'name'                     => $name,
                'population'               => $population,
                'category'                 => $category,
                'venue_access'             => $venue,
                'zone_access'              => $zone,
                'transport'                => $transport,
                'job_category_id'          => null, // Population sekarang teks bebas, tidak perlu ID
                'accreditation_mapping_id' => $mapping?->accreditation_mapping_id,
                'mapping_label'            => $mapping?->nama_akreditasi,
                'mapping_color'            => $mapping?->warna,
                'status'                   => $mapping ? 'valid' : 'error',
            ];
        }

        // Simpan validated data ke session agar process tak perlu query ulang
        session(['card_import_validated' => $validated]);

        return view('menu.admin.card.import-preview', ['data' => $validated]);
    }

    public function importProcess()
    {
        $validated = session('card_import_validated');
        if (!$validated) {
            return redirect()->route('admin.cards.index')->with('error', 'Tidak ada data import yang divalidasi.');
        }

        $eventId = session('admin_event_id');
        $adminId = session('admin_id');

        if (!$eventId || !$adminId) {
            return redirect()->route('admin.cards.index')->with('error', 'Sesi admin tidak valid.');
        }

        $count = 0;
        $failed = 0;

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use (
                $validated, $eventId, $adminId, &$count, &$failed
            ) {
                foreach ($validated as $row) {
                    if ($row['status'] !== 'valid') {
                        $failed++;
                        continue; // Skip baris error (Opsi B)
                    }

                    $recipient = \App\Models\CardRecipient::create([
                        'admin_id'                 => $adminId,
                        'event_id'                 => $eventId,
                        'name'                     => $row['name'],
                        'population'               => $row['population'] ?: null,
                        'category'                 => $row['category'] ?: null,
                        'venue_access'             => $row['venue_access'] ?: null,
                        'zone_access'              => $row['zone_access'] ?: null,
                        'transport'                => $row['transport'] ?: null,
                        'job_category_id'          => null, // Teks bebas
                        'accreditation_mapping_id' => $row['accreditation_mapping_id'],
                        // Kolom seating_access, identity_number tidak diisi
                    ]);

                    $snapshot = [
                        'applicant_name'    => $row['name'],
                        'job_category_name' => $row['population'], // text-job element
                        'mapping_name'      => $row['mapping_label'], // Untuk fallback jika mapping terhapus
                    ];

                    $qrToken = 'IMP-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(16));

                    \App\Models\Card::create([
                        'event_id'                 => $eventId,
                        'application_id'           => null,
                        'card_recipient_id'        => $recipient->id,
                        'accreditation_mapping_id' => $row['accreditation_mapping_id'], // FK ke mapping
                        'status'                   => 'issued',
                        'card_number'              => 'IMP-' . strtoupper(\Illuminate\Support\Str::random(8)),
                        'qr_token'                 => $qrToken,
                        'qr_payload'               => url("/cards/verify/{$qrToken}"),
                        'issued_at'                => now(),
                        'issued_by'                => $adminId,
                        'snapshot'                 => $snapshot,
                    ]);

                    $count++;
                }
            });
        } catch (\Exception $e) {
            session()->forget('card_import_data');
            session()->forget('card_import_validated');
            return redirect()->route('admin.cards.index')
                ->with('error', 'Gagal mengimpor kartu: ' . $e->getMessage());
        }

        session()->forget('card_import_data');
        session()->forget('card_import_validated');

        $msg = "Berhasil menggenerate {$count} kartu.";
        if ($failed > 0) {
            $msg .= " {$failed} baris dilewati (Category tidak ditemukan di master data atau nama kosong).";
        }

        return redirect()->route('admin.cards.index')->with('success', $msg);
    }
}