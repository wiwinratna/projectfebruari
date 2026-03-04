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
            if ($cardsByAppId->has($a->application_id)) continue;

            $jobCatId = (int) $a->job_category_id;
            $map = $mappingByJobCategory->get($jobCatId);

            // kalau belum ada mapping untuk job category ini, skip (biar admin benerin mapping dulu)
            if (!$map) continue;

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

        return view('menu.admin.card.index', [
            'apps' => $apps,
            'cardsByAppId' => $cardsByAppId,
            'jobCategories' => $jobCategories,
            'mappingByJobCategory' => $mappingByJobCategory,
            'eventId' => $eventId,
            'q' => $q,
            'statusCard' => $statusCard,
        ]);
    }
}