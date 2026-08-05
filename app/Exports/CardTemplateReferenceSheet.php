<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Facades\DB;

class CardTemplateReferenceSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $eventId;

    public function __construct($eventId = null)
    {
        $this->eventId = $eventId;
    }

    public function title(): string
    {
        return 'Referensi Master Data';
    }

    public function headings(): array
    {
        return [
            ['REFERENSI KODE UNTUK PENGISIAN EXCEL'],
            ['Gunakan data di bawah ini untuk mengisi sheet "Data Kartu" agar tidak terjadi error.'],
            [],
            ['Nama Akreditasi (Isikan ke kolom Category)', 'Warna Kartu', 'Keterangan']
        ];
    }

    public function collection()
    {
        if (!$this->eventId) {
            return collect([
                ['Silakan login untuk melihat referensi data event Anda', '', '']
            ]);
        }

        $references = [];

        // 1. Accreditation Mapping (Kategori)
        $mappings = DB::table('accreditation_mappings')
            ->where('event_id', $this->eventId)
            ->get();

        if ($mappings->isEmpty()) {
            $references[] = ['(Belum ada Akreditasi yang dibuat di event ini)', '', ''];
        } else {
            foreach ($mappings as $map) {
                $references[] = [
                    $map->nama_akreditasi,
                    $map->warna,
                    $map->keterangan
                ];
            }
        }

        $references[] = ['', '', ''];
        $references[] = ['DAFTAR KODE TRANSPORT', 'Keterangan', ''];
        
        $transports = DB::table('transportation_codes')
            ->where('event_id', $this->eventId)
            ->get();
            
        if ($transports->isEmpty()) {
            $references[] = ['(Belum ada kode transport)', '', ''];
        } else {
            foreach ($transports as $t) {
                $references[] = [$t->kode, $t->keterangan, ''];
            }
        }

        $references[] = ['', '', ''];
        $references[] = ['DAFTAR KODE VENUE', 'Keterangan', ''];

        $venues = DB::table('venue_accesses')
            ->where('event_id', $this->eventId)
            ->get();

        if ($venues->isEmpty()) {
            $references[] = ['(Belum ada kode venue)', '', ''];
        } else {
            foreach ($venues as $v) {
                $references[] = [$v->nama_vanue, $v->keterangan, ''];
            }
        }

        $references[] = ['', '', ''];
        $references[] = ['DAFTAR KODE ZONE', 'Keterangan', ''];

        $zones = DB::table('zone_access_codes')
            ->where('event_id', $this->eventId)
            ->get();

        if ($zones->isEmpty()) {
            $references[] = ['(Belum ada kode zone)', '', ''];
        } else {
            foreach ($zones as $z) {
                $references[] = [$z->kode_zona, $z->keterangan, ''];
            }
        }

        return collect($references);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 45,
            'B' => 20,
            'C' => 35,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '475569']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle('A4:C4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '111827']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E2E8F0']],
        ]);
        
        $highestRow = $sheet->getHighestRow();
        for ($i = 5; $i <= $highestRow; $i++) {
            $val = $sheet->getCell("A{$i}")->getValue();
            if (str_starts_with($val ?? '', 'DAFTAR KODE')) {
                $sheet->getStyle("A{$i}:C{$i}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => '111827']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E2E8F0']],
                ]);
            }
        }
    }
}
