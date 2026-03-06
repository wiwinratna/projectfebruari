@props([
    'items' => [],
    'style' => [],
])

@php
    $styleArr = is_array($style) ? $style : [];
    $cfg = \App\Support\CardLayoutRenderStyle::chipRenderConfig($styleArr);
    $itemsArr = collect($items)->filter(fn($it) => is_array($it))->values();
@endphp

<div style="width:100%;height:100%;padding:2mm;display:flex;flex-wrap:wrap;gap:{{ $cfg['gap'] }}px;align-content:flex-start;align-items:flex-start;overflow:hidden;max-height:{{ $cfg['maxHeightPx'] }}px;">
    @foreach($itemsArr as $item)
        @php
            $iconKey = $item['icon_key'] ?? null;
            $showCode = (bool)($item['show_code'] ?? true);
            $code = trim((string)($item['code'] ?? ''));
            $kind = (string)($item['kind'] ?? 'transport');
            $canRenderIcon = filled($iconKey);
            $canRenderCode = $showCode && $code !== '';
        @endphp
        @if($canRenderIcon || $canRenderCode)
            <span style="background:#f3f4f6;border:{{ $cfg['borderWidth'] }}px solid #d1d5db;border-radius:{{ $cfg['radiusCss'] }};padding:{{ $cfg['padY'] }}px {{ $cfg['padX'] }}px;display:inline-flex;align-items:center;gap:4px;white-space:nowrap;min-width:0;max-width:100%;overflow:hidden;text-overflow:ellipsis;font-size:{{ $cfg['fontPt'] }}pt;line-height:1;color:#1f2937;">
                @if($canRenderIcon)
                    <x-card.icon-svg :icon-key="$iconKey" :type="$kind === 'hotel' ? 'accommodation' : 'transport'" :size="$cfg['iconSizePx'] . 'px'" />
                @endif
                @if($canRenderCode)
                    <span style="font-family:monospace;font-weight:600;">{{ $code }}</span>
                @endif
            </span>
        @endif
    @endforeach
</div>
