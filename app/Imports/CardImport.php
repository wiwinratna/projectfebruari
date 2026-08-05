<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * CardImport — hanya mem-parse Excel menjadi Collection.
 * Penyimpanan ke DB dilakukan di CardController@importProcess
 * setelah admin melakukan preview dan konfirmasi.
 *
 * Template yang digunakan memiliki:
 *   - Baris 1: Judul utama
 *   - Baris 2: Sub-info
 *   - Baris 3: Header kolom (Name, Population, Category, dst.)
 *   - Baris 4+: Data
 *
 * WithHeadingRow akan otomatis pakai baris pertama yang dibaca sebagai header.
 * Karena startCell kita di A3, heading row = baris 3 = kolom header yang benar.
 */
class CardImport implements ToCollection, WithHeadingRow
{
    /**
     * Heading row dimulai di baris 3 (baris 1-2 = judul & info).
     */
    public function headingRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows): Collection
    {
        // Filter baris kosong (nama kosong) agar preview bersih
        return $rows->filter(function ($row) {
            $name = trim((string)($row['name'] ?? $row['nama_lengkap'] ?? ''));
            return $name !== '';
        })->values();
    }
}
