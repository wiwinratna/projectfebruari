@props(['key' => null, 'size' => '12pt'])

@php
  $svg = $key ? icon_svg_inline($key) : null;
@endphp

@if($svg)
  <span style="display:inline-flex;align-items:center;line-height:1;">
    <span style="width:{{ $size }};height:{{ $size }};display:inline-flex;align-items:center;justify-content:center;">
      {!! preg_replace('/\s(width|height)="[^"]*"/','',$svg) !!}
    </span>
  </span>
@elseif($key)
  <span style="display:inline-flex;align-items:center;justify-content:center;width:{{ $size }};height:{{ $size }};
               border:1px solid #e5e7eb;border-radius:4px;font-size:9pt;color:#9ca3af;">
    {{ strtoupper(substr($key,0,1)) }}
  </span>
@endif