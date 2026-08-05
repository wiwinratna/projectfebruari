<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;

class CardTemplateDataSheet implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    WithCustomStartCell
{
    protected $eventId;

    public function __construct($eventId = null)
    {
        $this->eventId = $eventId;
    }

    public function title(): string
    {
        return 'Data Kartu';
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return [
            'Name',
            'Population',
            'Category',
            'Venue Access',
            'Zone Access',
            'Transport',
        ];
    }

    public function collection()
    {
        $defaultExamples = [
            ['Prabowo Subianto', 'President RI', 'VVIP', 'ALL, CC, BC', '2, 4, MERAH', 'T2'],
            ['Budi Santoso', 'Kameramen', 'Media', 'PRESS, BC', '1, 3', 'BUS'],
            ['Siti Rahmawati', 'Koordinator Lapangan', 'Panitia', 'STAFF_ROOM, BC', '1, 2', 'T1'],
            ['Andi Permana', 'Volunteer Acara', 'Volunteer', 'HALL', '1', ''],
        ];

        // Jika eventId ada, ambil daftar kategori akreditasi yang ada
        if ($this->eventId) {
            $categories = DB::table('accreditation_mappings')
                ->where('event_id', $this->eventId)
                ->pluck('nama_akreditasi')
                ->toArray();
                
            if (count($categories) > 0) {
                $dynamicExamples = [];
                $names = ['Prabowo Subianto', 'Budi Santoso', 'Siti Rahmawati', 'Andi Permana'];
                $populations = ['Tamu Kehormatan', 'Jurnalis', 'Koordinator Lapangan', 'Tim Support'];
                foreach (array_slice($categories, 0, 4) as $idx => $catName) {
                    $dynamicExamples[] = [
                        $names[$idx % 4] ?? 'Nama Contoh',
                        $populations[$idx % 4] ?? 'Posisi',
                        $catName,
                        'CC, BC', // placeholder
                        '1, 2',   // placeholder
                        'T1'      // placeholder
                    ];
                }
                return collect($dynamicExamples);
            }
        }

        return collect($defaultExamples);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 28,  // Name
            'B' => 28,  // Population
            'C' => 22,  // Category
            'D' => 22,  // Venue Access
            'E' => 20,  // Zone Access
            'F' => 14,  // Transport
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // ── Baris 1: Judul utama ───────────────────────────────────────────
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', '📋  Template Import Penerima Kartu  —  Jangan ubah nama kolom di baris 3');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(36);

        // ── Baris 2: Sub-info ──────────────────────────────────────────────
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'Kolom wajib: Name, Category  |  Lihat sheet "Panduan" & "Referensi Master Data"');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '475569']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F1F5F9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // ── Baris 3: Header kolom ──────────────────────────────────────────
        $sheet->getStyle('A3:F3')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BFDBFE']]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(24);

        // ── Komentar pada header ───────────────────────────────────────────
        $comments = [
            'A3' => "WAJIB diisi.\nNama lengkap penerima kartu.\nContoh: Budi Santoso",
            'B3' => "Opsional.\nJabatan atau posisi orang ini. Teks bebas.\nContoh: President RI, Jurnalis, Kameramen",
            'C3' => "WAJIB diisi.\nKategori Akreditasi (Warna Kartu).\nHarus sama persis dengan master data event.\nContoh: VVIP, VIP, Media",
            'D3' => "Opsional.\nKode venue yang boleh diakses.\nPisahkan dengan koma jika lebih dari satu.\nContoh: ALL, CC, BC",
            'E3' => "Opsional.\nKode zona yang boleh diakses.\nPisahkan dengan koma jika lebih dari satu.\nContoh: 1, 2, MERAH",
            'F3' => "Opsional.\nKode transportasi.\nContoh: T1, T2, BUS\nHarus cocok dengan kode di master data event.",
        ];

        foreach ($comments as $cell => $text) {
            $comment = $sheet->getComment($cell);
            $comment->getText()->createTextRun($text);
            $comment->setWidth('220pt');
            $comment->setHeight('120pt');
        }

        // ── Baris data: zebra stripe + border ─────────────────────────────
        for ($row = 4; $row <= $lastRow; $row++) {
            $bgColor = ($row % 2 === 0) ? 'F8FAFC' : 'FFFFFF';
            $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $bgColor]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => [
                    'allBorders' => ['borderStyle' => Border::BORDER_HAIR, 'color' => ['rgb' => 'E2E8F0']],
                ],
            ]);
            $sheet->getRowDimension($row)->setRowHeight(20);
        }

        // Kolom Name bold
        $sheet->getStyle("A4:A{$lastRow}")->getFont()->setBold(true);

        // Freeze header
        $sheet->freezePane('A4');

        // Auto-filter
        $sheet->setAutoFilter("A3:F{$lastRow}");
    }
}
