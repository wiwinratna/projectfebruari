@props([
    'items' => [],
    'style' => [],
    'maxItems' => null,
])

@php
    $styleArr = is_array($style) ? $style : [];
    $cfg = \App\Support\CardLayoutRenderStyle::chipRenderConfig($styleArr);
    $itemsArr = collect($items)->filter(fn($it) => is_array($it))->values();
    if ($maxItems !== null) {
        $itemsArr = $itemsArr->take((int)$maxItems);
    }
@endphp

<div style="width:100%;height:100%;padding:2mm;display:flex;flex-wrap:wrap;gap:{{ $cfg['gap'] }}px;align-content:flex-start;align-items:flex-start;overflow:hidden;max-height:{{ $cfg['maxHeightPx'] }}px;">
    @foreach($itemsArr as $item)
        @php $code = trim((string)($item['code'] ?? '')); @endphp
        @if($code !== '')
            <span style="background:#f3f4f6;border:{{ $cfg['borderWidth'] }}px solid #d1d5db;color:#1f2937;border-radius:{{ $cfg['radiusCss'] }};padding:{{ $cfg['padY'] }}px {{ $cfg['padX'] }}px;display:inline-block;white-space:nowrap;min-width:0;max-width:100%;overflow:hidden;text-overflow:ellipsis;font-size:{{ $cfg['fontPt'] }}pt;line-height:1;">
                {{ \Illuminate\Support\Str::limit($code, 12) }}
            </span>
        @endif
    @endforeach
</div>
