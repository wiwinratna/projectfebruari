<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrController extends Controller
{
    // Menampilkan form upload
    public function index()
    {
        return view('ocr.upload');
    }

    // Memproses gambar yang diupload
    public function process(Request $request)
    {
        // 1. Validasi file harus gambar
        $request->validate([
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // 2. Simpan file sementara
        $image = $request->file('foto_ktp');
        $path = $image->store('uploads_ktp', 'public');
        $fullPath = storage_path('app/public/' . $path);

        try {
            // 3. Jalankan OCR
            $ocr = new TesseractOCR($fullPath);
            $ocr->lang('ind', 'eng'); // Coba Bahasa Indonesia, lalu Inggris
            $teks = $ocr->run();

            // 4. Cari data spesifik (NIK, Nama, dll) dari teks hasil OCR
            $dataKtp = $this->cariDataKtp($teks);

            // 5. Tampilkan hasil
            return view('ocr.result', [
                'teks_asli' => $teks,
                'data_ktp' => $dataKtp,
                'image_url' => asset('storage/' . $path)
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses OCR: ' . $e->getMessage());
        }
    }

    // Fungsi sederhana untuk mencari data dari teks
    private function cariDataKtp($teks)
    {
        $data = [
            'nik' => '-',
            'nama' => '-',
            'alamat' => '-'
        ];

        // Memecah teks menjadi baris-baris
        $baris = explode("\n", $teks);

        foreach ($baris as $line) {
            // Cari NIK (angka 16 digit)
            if (preg_match('/(\d{16})/', $line, $matches)) {
                $data['nik'] = $matches[1];
            }
            
            // Cari Nama (Baris yang mengandung kata "Nama")
            if (str_contains(strtolower($line), 'nama')) {
                // Hapus kata "Nama" dan titik dua, ambil sisanya
                $bersih = str_ireplace(['nama', ':', ': '], '', $line);
                $data['nama'] = trim($bersih);
            }

             // Cari Alamat (Baris yang mengandung kata "Alamat")
             if (str_contains(strtolower($line), 'alamat')) {
                $bersih = str_ireplace(['alamat', ':', ': '], '', $line);
                $data['alamat'] = trim($bersih);
            }
        }

        return $data;
    }
}
