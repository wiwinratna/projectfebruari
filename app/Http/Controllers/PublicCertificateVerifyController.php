<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PublicCertificateVerifyController extends Controller
{
    public function show(string $token)
    {
        $certificate = Certificate::where('qr_token', $token)
            ->with(['application.opening.event'])
            ->first();

        if (!$certificate) {
            return view('public.certificates.verify', [
                'valid'       => false,
                'reason'      => 'Sertifikat tidak ditemukan.',
                'certificate' => null,
                'layout'      => null,
                'payload'     => null,
                'qrBase64'    => null,
            ]);
        }

        if ($certificate->status !== 'published') {
            return view('public.certificates.verify', [
                'valid'       => false,
                'reason'      => 'Sertifikat belum dipublikasikan.',
                'certificate' => $certificate,
                'layout'      => null,
                'payload'     => null,
                'qrBase64'    => null,
            ]);
        }

        // Validate HMAC signature (same pattern as cards)
        $expectedSig = hash_hmac('sha256', $certificate->qr_token . '|' . $certificate->cert_code, config('app.key'));
        $isValid = hash_equals((string)$certificate->signature, (string)$expectedSig);

        if (!$isValid) {
            return view('public.certificates.verify', [
                'valid'       => false,
                'reason'      => 'Tanda tangan tidak valid.',
                'certificate' => $certificate,
                'layout'      => null,
                'payload'     => null,
                'qrBase64'    => null,
            ]);
        }

        // Resolve the frozen layout (from layout_snapshot — never the live layout)
        $layout = $certificate->getEffectiveLayout();

        $payload = $certificate->payload ?? [];

        // Generate QR code for rendering inside the certificate
        $qrBase64 = $this->qrBase64($payload['qr_url'] ?? url("/sertifikat/verify/{$token}"));

        return view('public.certificates.verify', [
            'valid'       => true,
            'reason'      => null,
            'certificate' => $certificate,
            'layout'      => $layout,
            'payload'     => array_merge($payload, ['qr_base64' => $qrBase64]),
            'qrBase64'    => $qrBase64,
        ]);
    }

    public function download(string $token)
    {
        $certificate = Certificate::where('qr_token', $token)
            ->where('status', 'published')
            ->firstOrFail();

        $layout = $certificate->getEffectiveLayout();
        $payload = $certificate->payload ?? [];
        $qrBase64 = $this->qrBase64($payload['qr_url'] ?? url('/sertifikat/verify/' . $certificate->qr_token));

        $pdf = Pdf::loadView('admin.certificates.preview-content', [
            'layout'      => $layout,
            'layoutModel' => $certificate->layout ?? \App\Models\CertificateLayout::find($certificate->layout_id),
            'event'       => $certificate->application?->opening?->event ?? (object)[],
            'payload'     => array_merge($payload, ['qr_base64' => $qrBase64]),
            'isPdf'       => true,
        ])->setPaper('a4', 'landscape');

        // Mark as downloaded
        $certificate->update(['downloaded_at' => now()]);

        $filename = 'Certificate-' . \Illuminate\Support\Str::slug($payload['volunteer_name'] ?? $certificate->cert_code) . '.pdf';

        return $pdf->download($filename);
    }

    private function qrBase64(?string $text): ?string
    {
        if (!$text) return null;

        $writer = new PngWriter();
        $qrCode = QrCode::create($text)->setSize(220)->setMargin(2);
        $result = $writer->write($qrCode);

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }
}
