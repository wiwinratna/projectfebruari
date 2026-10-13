<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\RedirectResponse;

class ApplicantPhotoDownloadService
{
    /**
     * Resolve the absolute local file path for an applicant's profile photo.
     */
    public function resolvePhotoPath(?string $photo): ?string
    {
        if (empty($photo)) {
            return null;
        }

        $raw = ltrim($photo, '/\\');
        $normalized = preg_replace('#^storage/#', '', $raw);

        // Common format in this project: "profile_photos/xyz.jpg" or "xyz.jpg"
        $candidates = [
            $normalized,
            str_contains($normalized, '/') ? $normalized : 'profile_photos/' . $normalized,
            $photo,
        ];

        foreach ($candidates as $candidate) {
            if (Storage::disk('public')->exists($candidate)) {
                return Storage::disk('public')->path($candidate);
            }
        }

        // Direct filesystem check in case stored without public disk prefix
        $directPublic = public_path('storage/' . $normalized);
        if (file_exists($directPublic) && !is_dir($directPublic)) {
            return $directPublic;
        }

        return null;
    }

    /**
     * Generate a sanitized, clean file name for an applicant's photo.
     * Example output: "01 Wiwin Ratna Sari - 320123456789.jpg" or "Wiwin Ratna Sari - 320123456789.jpg"
     */
    public function generatePhotoFilename(Application $application, ?int $index = null, string $extension = 'jpg'): string
    {
        $user = $application->user;
        $profile = $user?->profile;

        // Get raw applicant name with original capitalization
        $rawName = trim((string)($user?->name ?: ($profile?->name ?: ($user?->username ?: 'Pelamar'))));
        // Remove illegal filesystem characters, keep normal spaces and characters
        $cleanName = preg_replace('[/\\\\:*?"<>|]', '', $rawName);
        $cleanName = preg_replace('/\s+/', ' ', trim($cleanName));
        if (empty($cleanName)) {
            $cleanName = 'Pelamar';
        }

        // Get NIK or fallback to username / application ID
        $rawIdentifier = trim((string)($profile?->nik ?: ($user?->username ?: 'ID ' . $application->id)));
        $cleanIdentifier = preg_replace('[/\\\\:*?"<>|]', '', $rawIdentifier);
        $cleanIdentifier = preg_replace('/\s+/', ' ', trim($cleanIdentifier));

        // Format prefix: "01 ", "02 ", etc. when index provided
        $prefix = $index !== null ? sprintf('%02d ', $index) : '';

        // Combine: "01 Wiwin Ratna Sari - 320123456789.jpg"
        if (!empty($cleanIdentifier)) {
            return "{$prefix}{$cleanName} - {$cleanIdentifier}.{$extension}";
        }

        return "{$prefix}{$cleanName}.{$extension}";
    }

    /**
     * Download a single applicant photo directly.
     */
    public function downloadSinglePhoto(Application $application): BinaryFileResponse|RedirectResponse
    {
        $application->loadMissing(['user.profile']);
        $photoRaw = $application->user?->profile?->profile_photo;
        $path = $this->resolvePhotoPath($photoRaw);

        if (!$path || !file_exists($path)) {
            return back()->with('error', 'Foto profil pelamar belum diunggah atau file tidak ditemukan di server.');
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?: 'jpg';
        $filename = $this->generatePhotoFilename($application, null, $ext);

        return response()->download($path, $filename);
    }

    /**
     * Download multiple applicant photos as a ZIP archive.
     *
     * @param Collection<int, Application> $applications
     */
    public function downloadZip(Collection $applications, string $archiveTitle = 'Foto_Pelamar_Diterima'): BinaryFileResponse|RedirectResponse
    {
        if ($applications->isEmpty()) {
            return back()->with('error', 'Tidak ada data pelamar yang dipilih untuk diunduh.');
        }

        if (!class_exists('ZipArchive')) {
            return back()->with('error', 'Ekstensi PHP ZipArchive tidak aktif pada server.');
        }

        // Filter and collect existing photo files
        $validItems = [];
        $index = 1;

        foreach ($applications as $app) {
            $app->loadMissing(['user.profile']);
            $photoRaw = $app->user?->profile?->profile_photo;
            $diskPath = $this->resolvePhotoPath($photoRaw);

            if ($diskPath && file_exists($diskPath)) {
                $ext = strtolower(pathinfo($diskPath, PATHINFO_EXTENSION)) ?: 'jpg';
                $entryName = $this->generatePhotoFilename($app, $index, $ext);
                $validItems[] = [
                    'source' => $diskPath,
                    'name' => $entryName,
                ];
                $index++;
            }
        }

        if (empty($validItems)) {
            return back()->with('error', 'Tidak ada foto yang tersedia untuk diunduh dari pelamar terpilih (belum mengunggah foto).');
        }

        // Prepare temporary directory
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempZipPath = $tempDir . DIRECTORY_SEPARATOR . 'photos_' . Str::uuid() . '.zip';

        $zip = new ZipArchive();
        $openResult = $zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($openResult !== true) {
            return back()->with('error', 'Gagal membuat file ZIP arsip foto (Error: ' . $openResult . ').');
        }

        // Add files to ZIP
        foreach ($validItems as $item) {
            $zip->addFile($item['source'], $item['name']);
        }

        $zip->close();

        if (!file_exists($tempZipPath)) {
            return back()->with('error', 'File ZIP tidak berhasil disimpan.');
        }

        $safeTitle = Str::slug($archiveTitle, '_');
        $zipDownloadName = ($safeTitle ?: 'Foto_Pelamar_Diterima') . '_' . date('Ymd_His') . '.zip';

        return response()->download($tempZipPath, $zipDownloadName)->deleteFileAfterSend(true);
    }
}
