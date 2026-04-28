@php
/**
 * Certificate rendering engine — mirrors preview-card-content.blade.php
 */
$isPdf   = $isPdf ?? false;
$layout  = $layout ?: \App\Models\CertificateLayout::getDefaultLayout();
$payload = $payload ?? [];

/**
 * Resolve an image path to either a URL (web) or a local path (PDF)
 */
$resolveImage = function ($subPath) use ($isPdf) {
    if (empty($subPath)) return null;
    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    if (!$disk->exists($subPath)) return null;

    if ($isPdf) {
        return storage_path('app/public/' . ltrim($subPath, '/'));
    }
    return url('/media/' . ltrim($subPath, '/'));
};

$bgUrl        = $resolveImage($payload['background_path'] ?? $layoutModel?->background_path ?? null);
$eventLogoUrl = $resolveImage($payload['event_logo_path'] ?? $layoutModel?->event_logo_path ?? $event->logo_path ?? null);
$orgLogoUrl   = $resolveImage($payload['org_logo_path'] ?? $layoutModel?->org_logo_path ?? null);
$qrBase64     = $payload['qr_base64'] ?? null;
@endphp

<style>
    @page { margin: 0; }
    .cert-canvas {
        width: 297mm; height: 210mm; 
        position: relative; background: white; 
        overflow: hidden; margin: 0; padding: 0;
    }
    .abs { position: absolute; }
    .full { width: 100%; height: 100%; }
    .table-fill { display: table; width: 100%; height: 100%; }
    .table-cell { display: table-cell; vertical-align: middle; }
</style>

<div class="cert-canvas">
    {{-- Background image --}}
    @if($bgUrl)
        <img src="{{ $bgUrl }}" alt="Background"
             style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" />
    @endif

    {{-- Render each element from layout JSON --}}
    @foreach($layout['elements'] ?? [] as $element)
        @if(!empty($element['visible']))
            @php
                $rect   = $element['rect'] ?? [];
                $xMm    = $rect['xMm'] ?? 0;
                $yMm    = $rect['yMm'] ?? 0;
                $wMm    = $rect['wMm'] ?? 20;
                $hMm    = $rect['hMm'] ?? 10;
                $style  = is_array($element['style'] ?? null) ? $element['style'] : [];
                $type   = $element['type'] ?? '';

                $fontSize   = $style['fontSize']   ?? 12;
                $fontWeight = $style['fontWeight']  ?? 'normal';
                $align      = $style['align']       ?? 'left';
                $color      = $style['color']       ?? '#1a1a2e';
                $objectFit  = $style['objectFit']   ?? 'contain';
            @endphp
            <div style="position: absolute; left: {{ $xMm }}mm; top: {{ $yMm }}mm; width: {{ $wMm }}mm; height: {{ $hMm }}mm; overflow: hidden; z-index: 10;">

                @if($type === 'text-volunteer-name' || $type === 'volunteer_name')
                    <div class="table-fill">
                        <div class="table-cell" style="text-align:{{ $align }}; font-size:{{ $fontSize }}pt; font-weight:{{ $fontWeight }}; color:{{ $color }}; line-height:1.1;">
                            {{ $payload['volunteer_name'] ?? 'VOLUNTEER NAME' }}
                        </div>
                    </div>

                @elseif($type === 'text-volunteer-role' || $type === 'volunteer_role')
                    <div class="table-fill">
                        <div class="table-cell" style="text-align:{{ $align }}; font-size:{{ $fontSize }}pt; font-weight:{{ $fontWeight }}; color:{{ $color }};">
                            {{ $payload['role_label'] ?? 'ROLE / POSITION' }}
                        </div>
                    </div>

                @elseif($type === 'text-event-name' || $type === 'event_name')
                    <div class="table-fill">
                        <div class="table-cell" style="text-align:{{ $align }}; font-size:{{ $fontSize }}pt; font-weight:{{ $fontWeight }}; color:{{ $color }};">
                            {{ $payload['event_title'] ?? $event->title ?? 'EVENT NAME' }}
                        </div>
                    </div>

                @elseif($type === 'text-event-period' || $type === 'event_period')
                    <div class="table-fill">
                        <div class="table-cell" style="text-align:{{ $align }}; font-size:{{ $fontSize }}pt; color:{{ $color }};">
                            {{ $payload['event_start_at'] ?? '—' }} – {{ $payload['event_end_at'] ?? '—' }}
                        </div>
                    </div>

                @elseif($type === 'text-issue-date' || $type === 'issue_date')
                    <div class="table-fill">
                        <div class="table-cell" style="text-align:{{ $align }}; font-size:{{ $fontSize }}pt; color:{{ $color }};">
                            {{ $payload['issue_date'] ?? now()->format('d F Y') }}
                        </div>
                    </div>

                @elseif($type === 'event_logo')
                    @if($eventLogoUrl)
                        <img src="{{ $eventLogoUrl }}" alt="Logo Event"
                             style="width:100%; height:100%; object-fit:{{ $objectFit }};" />
                    @else
                        <div style="width:100%; height:100%; background:#f3f4f6; display:flex; align-items:center; justify-content:center; font-size:8px; color:#9ca3af; border:1px dashed #d1d5db;">
                            Logo Event
                        </div>
                    @endif

                @elseif($type === 'org_logo')
                    @if($orgLogoUrl)
                        <img src="{{ $orgLogoUrl }}" alt="Logo Organisasi"
                             style="width:100%; height:100%; object-fit:{{ $objectFit }};" />
                    @else
                        <div style="width:100%; height:100%; background:#f3f4f6; display:flex; align-items:center; justify-content:center; font-size:8px; color:#9ca3af; border:1px dashed #d1d5db;">
                            Logo Org
                        </div>
                    @endif

                @elseif($type === 'qr' || $type === 'qr_code')
                    @if($qrBase64)
                        <img src="{{ $qrBase64 }}" alt="QR Verifikasi"
                             style="width:100%; height:100%; object-fit:contain;" />
                    @else
                        <div style="width:100%; height:100%; background:#f3f4f6; display:flex; align-items:center; justify-content:center; font-size:8px; color:#9ca3af; border:1px dashed #d1d5db;">
                            QR Code
                        </div>
                    @endif

                @elseif($type === 'text-template')
                    @php
                        $template = $element['template'] ?? 'This certificate is awarded to {{name}} as {{role}} for participating in {{event_name}} on {{event_period}}.';
                        $lineHeight = $style['lineHeight'] ?? 1.4;
                        
                        $replacements = [
                            '{{name}}'         => '<strong>' . ($payload['volunteer_name'] ?? 'VOLUNTEER NAME') . '</strong>',
                            '{{role}}'         => '<strong>' . ($payload['role_label'] ?? 'ROLE / POSITION') . '</strong>',
                            '{{event_name}}'   => '<strong>' . ($payload['event_title'] ?? $event->title ?? 'EVENT NAME') . '</strong>',
                            '{{event_period}}' => '<strong>' . (($payload['event_start_at'] ?? '—') . ' – ' . ($payload['event_end_at'] ?? '—')) . '</strong>',
                        ];
                        
                        $renderedText = strtr($template, $replacements);
                    @endphp
                    <div class="table-fill">
                        <div class="table-cell" style="text-align:{{ $align }}; font-size:{{ $fontSize }}pt; font-weight:{{ $fontWeight }}; color:{{ $color }}; line-height:{{ $lineHeight }};">
                            <span>{!! $renderedText !!}</span>
                        </div>
                    </div>

                @elseif($type === 'extra_logo')
                    @php
                        $logoPath = $element['imagePath'] ?? null;
                        $logoUrl  = $resolveImage($logoPath);
                    @endphp
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo" style="width:100%; height:100%; object-fit:contain;" />
                    @else
                        <div style="width:100%; height:100%; background:#f3f4f6; display:flex; align-items:center; justify-content:center; font-size:8px; color:#9ca3af; border:1px dashed #d1d5db;">
                            Extra Logo
                        </div>
                    @endif

                @elseif($type === 'signature')
                    @php
                        $sigPath = $element['signatureImagePath'] ?? null;
                        $sigUrl  = $resolveImage($sigPath);
                        $signerName = $element['signerName'] ?? 'Signer Name';
                        $signerTitle= $element['signerTitle'] ?? 'Title';
                    @endphp
                    <div style="width:100%; height:100%; position:relative; overflow:hidden;">
                        <div style="position:absolute; bottom:0; left:0; width:100%; text-align:center;">
                            @if($sigUrl)
                                <img src="{{ $sigUrl }}" alt="Signature" style="display:block; height:45px; margin: 0 auto 5px auto;" />
                            @else
                                <div style="height:40px; border-bottom:1.5px solid #374151; width:90%; margin: 0 auto 5px auto;"></div>
                            @endif
                            <div style="font-weight:bold; font-size:{{ $fontSize }}pt; color:{{ $color }}; line-height:1.2; margin-top:3px;">{{ $signerName }}</div>
                            <div style="font-size:{{ max(7, $fontSize - 2) }}pt; color:#666; line-height:1.2;">{{ $signerTitle }}</div>
                        </div>
                    </div>

                @endif
            </div>
        @endif
    @endforeach
</div>
