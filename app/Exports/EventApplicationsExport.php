<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class EventApplicationsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithColumnFormatting,
    WithCustomStartCell,
    WithTitle
{
    protected Event $event;
    protected ?string $statusFilter;
    protected int $rowCounter = 0;
    protected int $totalCount = 0;
    protected int $acceptedCount = 0;
    protected int $pendingCount = 0;
    protected int $rejectedCount = 0;

    public function __construct(Event $event, ?string $statusFilter = null)
    {
        $this->event = $event;
        $this->statusFilter = $statusFilter;
    }

    public function title(): string
    {
        return 'Data Pelamar ' . substr(preg_replace('/[^A-Za-z0-9 ]/', '', $this->event->title), 0, 20);
    }

    public function startCell(): string
    {
        return 'A8';
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
        ])
        ->whereHas('opening', function ($q) {
            $q->where('event_id', $this->event->id);
        })
        ->orderBy('created_at', 'desc');

        if ($this->statusFilter && $this->statusFilter !== 'all') {
            if ($this->statusFilter === 'accepted') {
                $query->whereIn('status', ['approved', 'accepted']);
            } elseif ($this->statusFilter === 'pending') {
                $query->whereNotIn('status', ['approved', 'accepted', 'rejected']);
            } else {
                $query->where('status', $this->statusFilter);
            }
        }

        $collection = $query->get();

        // Calculate summary statistics
        $allApps = Application::whereHas('opening', fn($q) => $q->where('event_id', $this->event->id))->get();
        $this->totalCount = $allApps->count();
        $this->acceptedCount = $allApps->whereIn('status', ['approved', 'accepted'])->count();
        $this->rejectedCount = $allApps->where('status', 'rejected')->count();
        $this->pendingCount = $this->totalCount - $this->acceptedCount - $this->rejectedCount;

        return $collection;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'ID Lamaran',
            'Nama Lengkap',
            'Username',
            'Email',
            'No. Telepon / WhatsApp',
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
            'Pendidikan Terakhir',
            'Bidang Studi / Jurusan',
            'Universitas / Sekolah',
            'Tahun Kelulusan',
            'Keahlian (Skills)',
            'Kemampuan Bahasa',
            'Event',
            'Posisi Dilamar',
            'Kategori Posisi',
            'Functional Area (FA)',
            'Status Lamaran',
            'Tanggal Daftar',
            'Waktu Daftar',
            'Motivasi Pelamar',
            'Pengalaman Pelamar',
            'Catatan Review',
            'Tanggal Review',
            'Direview Oleh',
            'Status Foto Profil',
            'File / Path CV',
            'Instagram',
            'LinkedIn',
            'TikTok',
            'Twitter / X',
            'Website',
        ];
    }

    /**
     * @param Application $application
     * @return array
     */
    public function map($application): array
    {
        $this->rowCounter++;
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
            $age = $parsedDob->age . ' Thn';
        }

        $address = $profile?->address ?: '-';
        $province = $profile?->province ?: '-';
        $cityRegency = $profile?->city_regency ?: '-';
        $district = $profile?->district ?: '-';
        $village = $profile?->village ?: '-';
        $postalCode = $profile?->postal_code ? (string) $profile->postal_code : '-';

        $rtRw = '-';
        if ($profile && ($profile->rt || $profile->rw)) {
            $rtRw = 'RT ' . ($profile->rt ?: '-') . ' / RW ' . ($profile->rw ?: '-');
        }

        $country = $isWna ? ($profile?->country ?: '-') : 'Indonesia';

        $lastEducation = $profile?->last_education ?: '-';
        $fieldOfStudy = $profile?->field_of_study ?: '-';
        $university = $profile?->university ?: '-';
        $gradYear = $profile?->graduation_year ? (string) $profile->graduation_year : '-';

        $skills = $profile?->skills ?: '-';
        $languages = $profile?->languages ?: '-';

        $cvFile = $application->cv_path ?: ($profile?->cv_file ?: '-');
        $hasPhoto = !empty($profile?->profile_photo) ? 'Tersedia' : 'Belum Ada';

        $reviewerName = $application->reviewer?->name ?: '-';

        // Friendly Indonesian status
        $statusLabel = match ($application->status) {
            'approved', 'accepted' => 'DITERIMA',
            'rejected'             => 'DITOLAK',
            'reviewed'             => 'SEDANG DIREVIEW',
            default                => 'MENUNGGU REVIEW (PENDING)',
        };

        $jobCatName = $application->opening?->jobCategory?->name ?: '-';
        $faName = $application->opening?->jobCategory?->fa_name ?: '-';

        return [
            $this->rowCounter,
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
            $lastEducation,
            $fieldOfStudy,
            $university,
            $gradYear,
            $skills,
            $languages,
            $this->event->title,
            $application->opening?->title ?: '-',
            $jobCatName,
            $faName,
            $statusLabel,
            $application->created_at ? $application->created_at->format('d M Y') : '-',
            $application->created_at ? $application->created_at->format('H:i:s') : '-',
            $application->motivation ?: '-',
            $application->experience ?: '-',
            $application->review_notes ?: '-',
            $application->reviewed_at ? $application->reviewed_at->format('d M Y H:i') : '-',
            $reviewerName,
            $hasPhoto,
            $cvFile,
            $profile?->instagram ?: '-',
            $profile?->linkedin ?: '-',
            $profile?->tiktok ?: '-',
            $profile?->twitter ?: '-',
            $profile?->website ?: '-',
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_TEXT, // Phone
            'G' => NumberFormat::FORMAT_TEXT, // NIK
            'Q' => NumberFormat::FORMAT_TEXT, // Postal code
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = max(8, $sheet->getHighestRow());
        $lastColumn = 'AR';

        // ══════════════════════════════════════════════════════════════════════
        // KOP SURAT / HEADER LAPORAN EXCEL
        // ══════════════════════════════════════════════════════════════════════

        // Row 1: Brand / Organization Title
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', 'KOMITE OLIMPIADE INDONESIA (NOC INDONESIA)  •  ARISE GAMES');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '0F172A']], // Dark Navy
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Row 2: Document Title
        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', 'LAPORAN DATA LENGKAP PELAMAR & REKRUTMEN EVENT');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E293B']], // Slate
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(26);

        // Row 3: Event Name Banner (Red theme)
        $sheet->mergeCells("A3:{$lastColumn}3");
        $sheet->setCellValue('A3', 'EVENT: ' . strtoupper($this->event->title));
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'C40000']], // Brand Red
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(26);

        // Row 4: Event Location & Schedule Metadata
        $venueInfo = ($this->event->venue ?: '-') . ($this->event->city ? ' (' . $this->event->city->name . ', ' . $this->event->city->province . ')' : '');
        $dateInfo = ($this->event->start_at ? $this->event->start_at->format('d M Y') : '-') . ' s/d ' . ($this->event->end_at ? $this->event->end_at->format('d M Y') : '-');
        $organizerInfo = $this->event->penyelenggara ?: 'NOC Indonesia';

        $sheet->mergeCells("A4:{$lastColumn}4");
        $sheet->setCellValue('A4', "Lokasi / Venue: {$venueInfo}   |   Periode: {$dateInfo}   |   Penyelenggara: {$organizerInfo}");
        $sheet->getStyle('A4')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '1E293B']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E2E8F0']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(22);

        // Row 5: Summary Stats & Export Timestamp
        $exportTime = Carbon::now()->format('d F Y, H:i') . ' WIB';
        $summaryText = "Total Data Pelamar: {$this->totalCount} Orang  ( Diterima: {$this->acceptedCount}  |  Pending: {$this->pendingCount}  |  Ditolak: {$this->rejectedCount} )   •   Waktu Download: {$exportTime}";

        $sheet->mergeCells("A5:{$lastColumn}5");
        $sheet->setCellValue('A5', $summaryText);
        $sheet->getStyle('A5')->applyFromArray([
            'font'      => ['italic' => true, 'bold' => true, 'size' => 9.5, 'color' => ['rgb' => '475569']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F1F5F9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // Row 6: Empty Divider
        $sheet->mergeCells("A6:{$lastColumn}6");
        $sheet->setCellValue('A6', '');
        $sheet->getRowDimension(6)->setRowHeight(6);

        // Row 7: Empty Divider
        $sheet->mergeCells("A7:{$lastColumn}7");
        $sheet->setCellValue('A7', '');
        $sheet->getRowDimension(7)->setRowHeight(6);

        // ══════════════════════════════════════════════════════════════════════
        // ROW 8: TABLE HEADINGS
        // ══════════════════════════════════════════════════════════════════════
        $sheet->getStyle("A8:{$lastColumn}8")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E3A8A']], // Deep Royal Blue
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders'   => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => '3B82F6'],
                ],
            ],
        ]);
        $sheet->getRowDimension(8)->setRowHeight(28);

        // ══════════════════════════════════════════════════════════════════════
        // DATA ROWS STYLING (ROW 9 to $lastRow)
        // ══════════════════════════════════════════════════════════════════════
        if ($lastRow >= 9) {
            // General data style
            $sheet->getStyle("A9:{$lastColumn}{$lastRow}")->applyFromArray([
                'font'      => ['size' => 9.5, 'color' => ['rgb' => '1E293B']],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ]);

            // Alternate row background & row heights
            for ($r = 9; $r <= $lastRow; $r++) {
                $sheet->getRowDimension($r)->setRowHeight(22);
                if ($r % 2 === 1) {
                    $sheet->getStyle("A{$r}:{$lastColumn}{$r}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color'    => ['rgb' => 'F8FAFC'],
                        ],
                    ]);
                }
            }

            // Center-align specific columns (No, ID, Phone, NIK, Kewarganegaraan, DOB, Age, Postal, RT/RW, Status, Dates)
            $centeredCols = ['A', 'B', 'F', 'G', 'H', 'I', 'J', 'Q', 'R', 'AD', 'AE', 'AF', 'AJ', 'AL'];
            foreach ($centeredCols as $col) {
                $sheet->getStyle("{$col}9:{$col}{$lastRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            // Highlight Status Column (AD = Status Lamaran)
            for ($r = 9; $r <= $lastRow; $r++) {
                $statusVal = (string) $sheet->getCell("AD{$r}")->getValue();
                if (str_contains($statusVal, 'DITERIMA')) {
                    $sheet->getStyle("AD{$r}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '166534']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'DCFCE7']], // Light green
                    ]);
                } elseif (str_contains($statusVal, 'DITOLAK')) {
                    $sheet->getStyle("AD{$r}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '991B1B']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FEE2E2']], // Light red
                    ]);
                } elseif (str_contains($statusVal, 'PENDING')) {
                    $sheet->getStyle("AD{$r}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => '854D0E']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FEF9C3']], // Light yellow
                    ]);
                }
            }
        }

        // AutoFilter on row 8
        $sheet->setAutoFilter("A8:{$lastColumn}{$lastRow}");

        // Freeze panes below row 8 (so the Kop and Table Headings stay pinned!)
        $sheet->freezePane('A9');

        return [];
    }
}
