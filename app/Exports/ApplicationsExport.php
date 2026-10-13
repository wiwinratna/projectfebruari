<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class ApplicationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $eventId;
    protected $search;

    public function __construct($eventId = null, $search = null)
    {
        $this->eventId = $eventId;
        $this->search = $search;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Application::with([
            'user.profile',
            'opening.jobCategory',
            'opening.event.city',
            'reviewer'
        ])->orderBy('created_at', 'desc');

        // Filter by event if specified
        if ($this->eventId) {
            $query->whereHas('opening', function($q) {
                $q->where('event_id', $this->eventId);
            });
        }

        // Apply search if present
        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', '%'.$search.'%')
                       ->orWhere('username', 'like', '%'.$search.'%')
                       ->orWhere('email', 'like', '%'.$search.'%');
                })
                ->orWhereHas('user.profile', function($pq) use ($search) {
                    $pq->where('nik', 'like', '%'.$search.'%')
                       ->orWhere('phone', 'like', '%'.$search.'%');
                })
                ->orWhereHas('opening', function($oq) use ($search) {
                    $oq->where('title', 'like', '%'.$search.'%')
                       ->orWhereHas('event', function($eq) use ($search) {
                            $eq->where('title', 'like', '%'.$search.'%');
                       })
                       ->orWhereHas('jobCategory', function($jcq) use ($search) {
                            $jcq->where('name', 'like', '%'.$search.'%');
                       });
                });
            });
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Aplikasi',
            'Nama Lengkap',
            'Username',
            'Email',
            'No. Telepon / WA',
            'NIK',
            'Kewarganegaraan',
            'Tanggal Lahir',
            'Usia',
            'Headline Profesional',
            'Alamat Lengkap',
            'Provinsi',
            'Kota / Kabupaten',
            'Kecamatan',
            'Kelurahan / Desa',
            'Kode Pos',
            'RT / RW',
            'Negara (WNA)',
            'State / Wilayah (WNA)',
            'Pendidikan Terakhir',
            'Bidang Studi / Jurusan',
            'Universitas / Sekolah',
            'Tahun Kelulusan',
            'Keahlian (Skills)',
            'Bahasa',
            'Event',
            'Posisi Dilamar',
            'Kategori Posisi',
            'Kota Penyelenggaraan',
            'Status Aplikasi',
            'Tanggal Mendaftar',
            'Waktu Mendaftar',
            'Motivasi Pelamar',
            'Pengalaman Pelamar',
            'Catatan Review',
            'Tanggal Review',
            'Direview Oleh',
            'Instagram',
            'LinkedIn',
            'TikTok',
            'Twitter / X',
            'Website',
            'File CV',
            'Status Foto Profil',
        ];
    }

    /**
     * @param Application $application
     * @return array
     */
    public function map($application): array
    {
        $user = $application->user;
        $profile = $user?->profile;

        $isWna = ($profile && $profile->nationality_type === 'wna');
        $kewarganegaraan = $profile ? ($isWna ? 'WNA' : 'WNI') : '-';
        $nik = ($profile && $profile->nik) ? (string) $profile->nik : '-';
        $phone = ($profile && $profile->phone) ? (string) $profile->phone : '-';

        $dob = '-';
        $age = '-';
        if ($profile && $profile->date_of_birth) {
            $parsedDob = Carbon::parse($profile->date_of_birth);
            $dob = $parsedDob->format('d M Y');
            $age = $parsedDob->age . ' Tahun';
        }

        $address = $profile?->address ?: '-';
        $province = $profile?->province ?: '-';
        $cityRegency = $profile?->city_regency ?: '-';
        $district = $profile?->district ?: '-';
        $village = $profile?->village ?: '-';
        $postalCode = $profile?->postal_code ? (string)$profile->postal_code : '-';

        $rtRw = '-';
        if ($profile && ($profile->rt || $profile->rw)) {
            $rtRw = 'RT ' . ($profile->rt ?: '-') . ' / RW ' . ($profile->rw ?: '-');
        }

        $country = $isWna ? ($profile?->country ?: '-') : '-';
        $stateRegion = $isWna ? ($profile?->state_region ?: '-') : '-';

        $lastEducation = $profile?->last_education ?: '-';
        $fieldOfStudy = $profile?->field_of_study ?: '-';
        $university = $profile?->university ?: '-';
        $gradYear = $profile?->graduation_year ? (string)$profile->graduation_year : '-';

        $skills = $profile?->skills ?: '-';
        $languages = $profile?->languages ?: '-';

        $cvFile = $application->cv_path ?: ($profile?->cv_file ?: '-');
        $hasPhoto = !empty($profile?->profile_photo) ? 'Tersedia' : 'Belum Ada';

        $reviewerName = $application->reviewer?->name ?: '-';

        return [
            $application->id,
            $user?->name ?: ($profile?->name ?: ($user?->username ?: '-')),
            $user?->username ?: '-',
            $user?->email ?: '-',
            $phone,
            $nik,
            $kewarganegaraan,
            $dob,
            $age,
            $profile?->professional_headline ?: '-',
            $address,
            $province,
            $cityRegency,
            $district,
            $village,
            $postalCode,
            $rtRw,
            $country,
            $stateRegion,
            $lastEducation,
            $fieldOfStudy,
            $university,
            $gradYear,
            $skills,
            $languages,
            $application->opening?->event?->title ?: '-',
            $application->opening?->title ?: '-',
            $application->opening?->jobCategory?->name ?: '-',
            $application->opening?->event?->city?->name ?: '-',
            ucfirst($application->status),
            $application->created_at->format('d M Y'),
            $application->created_at->format('H:i:s'),
            $application->motivation ?: '-',
            $application->experience ?: '-',
            $application->review_notes ?: '-',
            $application->reviewed_at ? $application->reviewed_at->format('d M Y H:i') : '-',
            $reviewerName,
            $profile?->instagram ?: '-',
            $profile?->linkedin ?: '-',
            $profile?->tiktok ?: '-',
            $profile?->twitter ?: '-',
            $profile?->website ?: '-',
            $cvFile,
            $hasPhoto,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT, // Phone
            'F' => NumberFormat::FORMAT_TEXT, // NIK
            'P' => NumberFormat::FORMAT_TEXT, // Postal code
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E40AF'], // Royal Navy Blue
                ],
            ],
        ];
    }
}
