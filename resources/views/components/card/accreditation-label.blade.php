@props([
    'text' => '',
    'color' => '#16a34a',
    'style' => [],
])

@php
    $styleArr = is_array($style) ? $style : [];
    $radiusCss = \App\Support\CardLayoutRenderStyle::borderRadiusCss($styleArr, 4);
    $fontPt = (float)($styleArr['fontSizePt'] ?? 10);
    $fontWeight = $styleArr['fontWeight'] ?? 'bold';
    $align = $styleArr['align'] ?? 'left';
    $justify = $align === 'right' ? 'flex-end' : ($align === 'center' ? 'center' : 'flex-start');
@endphp

<div style="width:100%;height:100%;padding:2mm 4mm;font-size:{{ $fontPt }}pt;font-weight:{{ $fontWeight }};color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;border-radius:{{ $radiusCss }};display:flex;align-items:center;justify-content:{{ $justify }};background-color:{{ $color }};">
    {{ $text }}
</div>
