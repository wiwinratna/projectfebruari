@props([
    'iconKey' => null,
    'type' => null,
    'size' => '1em',
    'class' => '',
])

@php
    $svg = null;
    if ($iconKey) {
        $svg = icon_svg_inline($iconKey);
    }
@endphp

@if($svg)
    <span class="{{ $class }}" style="display:inline-flex;align-items:center;justify-content:center;width:{{ $size }};height:{{ $size }};line-height:1;color:currentColor;">
        {!! preg_replace('/\s(width|height)="[^"]*"/', '', $svg) !!}
    </span>
@else
    @switch($type)
        @case('transport')
            <span class="{{ $class }}" style="display:inline-flex;align-items:center;justify-content:center;width:{{ $size }};height:{{ $size }};line-height:1;color:currentColor;">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;display:block;" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3.5" y="5" width="17" height="11" rx="2"></rect>
                    <line x1="3.5" y1="11" x2="20.5" y2="11"></line>
                    <circle cx="7.5" cy="17.5" r="1.5" fill="currentColor" stroke="none"></circle>
                    <circle cx="16.5" cy="17.5" r="1.5" fill="currentColor" stroke="none"></circle>
                </svg>
            </span>
            @break
        @case('accommodation')
            <span class="{{ $class }}" style="display:inline-flex;align-items:center;justify-content:center;width:{{ $size }};height:{{ $size }};line-height:1;color:currentColor;">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;display:block;" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 10.5h4.5c1.4 0 2.5 1.1 2.5 2.5V16H4v-5.5z"></path>
                    <path d="M11 12h6.3c1.5 0 2.7 1.2 2.7 2.7V16H11v-4z"></path>
                    <line x1="4" y1="16" x2="4" y2="19"></line>
                    <line x1="20" y1="16" x2="20" y2="19"></line>
                    <line x1="3" y1="16" x2="21" y2="16"></line>
                </svg>
            </span>
            @break
        @default
            <span class="{{ $class }}" style="display:inline-flex;align-items:center;justify-content:center;width:{{ $size }};height:{{ $size }};border:1px solid currentColor;opacity:.45;"></span>
    @endswitch
@endif
