<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CardTemplateInstructionSheet implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    public function title(): string
    {
        return 'Panduan Pengisian';
    }

    public function headings(): array
    {
        return [
            ['PANDUAN PENGISIAN DATA IMPORT KARTU'],
            ['Harap baca panduan ini dengan saksama sebelum mengisi sheet "Data Kartu"'],
            [''],
            ['Kolom', 'Sifat', 'Cara Pengisian', 'Contoh'],
        ];
    }

    public function collection()
    {
        return collect([
            [
                'Name',
                'WAJIB',
                "Nama lengkap penerima kartu yang akan dicetak.",
                "Budi Santoso"
            ],
            [
                'Population',
                'Opsional',
                "Jabatan, posisi, atau instansi. Berupa teks bebas yang akan langsung dicetak di kartu.",
                "President RI, Kameramen, Relawan"
            ],
            [
                'Category',
                'WAJIB',
                "Kategori Akreditasi (menentukan warna kartu dan label). Harus diketik persis sesuai dengan yang ada di sheet \"Referensi Master Data\". Tidak boleh ada salah ketik (typo).",
                "VVIP, VIP, Media, Panitia"
            ],
            [
                'Venue Access',
                'Opsional',
                "Kode Venue yang bisa diakses. Pisahkan dengan koma jika lebih dari satu. Harus sesuai kode di sheet referensi.",
                "ALL, CC, BC"
            ],
            [
                'Zone Access',
                'Opsional',
                "Kode Zona yang bisa diakses. Pisahkan dengan koma jika lebih dari satu. Harus sesuai kode di sheet referensi.",
                "1, 2, 4, MERAH"
            ],
            [
                'Transport',
                'Opsional',
                "Kode Transportasi yang bisa diakses. Harus sesuai kode di sheet referensi.",
                "T1, T2, BUS"
            ],
            ['', '', '', ''],
            ['PERHATIAN PENTING', '', '', ''],
            [
                '1. Baris Error',
                '-',
                "Jika Anda salah mengetik 'Category' (misal: 'VVIPx' padahal harusnya 'VVIP'), maka baris tersebut akan ditandai ERROR pada halaman preview dan akan dilewati (tidak diproses).",
                ""
            ],
            [
                '2. Format Teks',
                '-',
                "Nama kolom pada baris 3 di sheet 'Data Kartu' tidak boleh diubah, ditambah, atau dihapus.",
                ""
            ]
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 15,
            'C' => 70,
            'D' => 40,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle('A4:D4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'E2E8F0']
            ]
        ]);
        
        $sheet->getStyle('A12:D12')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'DC2626']], // Red color for warning
        ]);

        $sheet->getStyle('C5:C14')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A5:D14')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
    }
}
