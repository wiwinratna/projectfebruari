@extends('layouts.app')

@section('title', 'Certificate Builder — ' . $event->title)

@section('content')
@php
    $bgPath       = $activeLayout?->background_path;
    $bgUrl        = ($bgPath && \Storage::disk('public')->exists($bgPath)) ? asset('storage/'.$bgPath) : null;
    $evLogoPath   = $activeLayout?->event_logo_path ?? $event->logo_path;
    $evLogoUrl    = ($evLogoPath && \Storage::disk('public')->exists($evLogoPath)) ? asset('storage/'.$evLogoPath) : null;
    $orgLogoPath  = $activeLayout?->org_logo_path;
    $orgLogoUrl   = ($orgLogoPath && \Storage::disk('public')->exists($orgLogoPath)) ? asset('storage/'.$orgLogoPath) : null;
    $locked       = $activeLayout && $activeLayout->isLocked();
@endphp

{{-- ═══════ PAGE ═══════ --}}
<div class="min-h-screen bg-gray-50">
<div class="max-w-screen-2xl mx-auto px-4 py-5 space-y-4">

{{-- ① HEADER --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
            <i class="fas fa-drafting-compass text-amber-600 text-lg"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold text-gray-900 leading-tight">Certificate Builder</h1>
            <p class="text-sm text-gray-500">{{ $event->title }}</p>
        </div>

    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.certificates.index') }}"
           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
            <i class="fas fa-list mr-1.5"></i>Certificate List
        </a>
        <button id="btnPreview" onclick="openPreview()"
            class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition">
            <i class="fas fa-eye mr-1.5"></i>Preview
        </button>
        <button id="btnSave" onclick="saveLayout()"
            class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition
            {{ $locked ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ $locked ? 'disabled' : '' }}>
            <i class="fas fa-check-circle mr-1.5"></i>Save
        </button>
        @if(!$locked)
        <button id="btnPublish" onclick="confirmPublish()"
            class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm font-medium hover:bg-green-700 transition flex items-center gap-1.5">
            <i class="fas fa-lock"></i>Publish
        </button>
        @else
        <button onclick="openRevertModal()"
            class="px-4 py-2 bg-amber-500 text-white rounded-xl text-sm font-bold hover:bg-amber-600 transition flex items-center gap-1.5 shadow-sm">
            <i class="fas fa-unlock"></i>Revert to Draft
        </button>
        @endif
    </div>
</div>

{{-- Publish Modal --}}
<div id="publishModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closePublishModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lock text-green-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Publish & Lock Layout</h3>
                <p class="text-sm text-gray-500">This action cannot be undone</p>
            </div>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-sm text-amber-800 space-y-1">
            <p class="font-bold">⚠️ Important — Read before continuing:</p>
            <ul class="list-disc ml-4 space-y-1 text-xs">
                <li>Once published, this layout <strong>cannot be edited</strong></li>
                <li>Generated certificates will continue using this layout as a snapshot</li>
                <li>To modify the design, use <strong>Back to Draft</strong> or <strong>Duplicate Layout</strong></li>
                <li>Make sure the preview looks correct before continuing</li>
            </ul>
        </div>
        <p class="text-sm text-gray-700">Save the layout first before publishing if there are unsaved changes.</p>
        <div class="flex gap-2 pt-2">
            <button onclick="closePublishModal()" class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium text-sm hover:bg-gray-200 transition">Cancel</button>
            <button onclick="doPublish()" id="btnDoPublish" class="flex-1 py-2.5 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition flex items-center justify-center gap-2">
                <i class="fas fa-lock"></i>Yes, Publish Now
            </button>
        </div>
    </div>
</div>

@if($locked)
<div class="bg-amber-50 border border-amber-200 text-amber-900 px-5 py-3 rounded-xl text-sm flex items-center gap-3 flex-wrap">
    <i class="fas fa-lock text-amber-500 flex-shrink-0"></i>
    <div class="flex-1 min-w-0">
        <span class="font-semibold">This layout is published & locked.</span>
        <span class="text-amber-700 ml-1 text-xs">Revert to Draft to edit &mdash; this will also clear all generated certificates so you can re-publish cleanly.</span>
    </div>
    <button onclick="openRevertModal()" class="flex-shrink-0 px-4 py-2 bg-amber-600 text-white rounded-lg text-xs font-bold hover:bg-amber-700 transition">
        <i class="fas fa-unlock mr-1"></i>Revert to Draft
    </button>
</div>
@endif

{{-- Revert to Draft Modal --}}
@if($locked)
<div id="revertModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeRevertModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Revert to Draft?</h3>
                <p class="text-sm text-gray-500">This will clear existing certificates</p>
            </div>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-sm text-red-800 space-y-1">
            <p class="font-bold">⚠️ Warning:</p>
            <ul class="list-disc ml-4 space-y-1 text-xs">
                <li>The layout will be unlocked and editable again.</li>
                <li><strong>ALL generated certificates for this event will be deleted.</strong></li>
                <li>You will need to re-publish to generate them again.</li>
            </ul>
        </div>
        <div class="flex gap-2 pt-2">
            <button onclick="closeRevertModal()" class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium text-sm hover:bg-gray-200 transition">Cancel</button>
            <form method="POST" action="{{ route('admin.certificate-layouts.unpublish', $activeLayout) }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full py-2.5 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-trash-alt"></i>Yes, Revert & Clear
                </button>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ② STEP INDICATOR --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4">
    <div class="flex items-center justify-between flex-wrap gap-3">
        @foreach([
            ['1', 'Upload Assets', 'Upload logo, background & signature photo', 'amber'],
            ['2', 'Arrange Layout', 'Position elements on the canvas', 'blue'],
            ['3', 'Preview', 'Check the design before saving', 'indigo'],
            ['4', 'Publish', 'Lock layout & generate certificates', 'green'],
        ] as [$step, $title, $desc, $color])
        <div class="flex items-center gap-2 flex-1 min-w-[160px]">
            <div class="w-9 h-9 rounded-full bg-{{ $color }}-100 border-2 border-{{ $color }}-300 flex items-center justify-center flex-shrink-0">
                <span class="text-{{ $color }}-700 font-bold text-sm">{{ $step }}</span>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-800">{{ $title }}</div>
                <div class="text-[11px] text-gray-400">{{ $desc }}</div>
            </div>
        </div>
        @if(!$loop->last)
        <i class="fas fa-chevron-right text-gray-300 hidden lg:block"></i>
        @endif
        @endforeach
    </div>
</div>

{{-- ③ MAIN EDITOR --}}
<div class="flex gap-4 items-start">

    {{-- ── CANVAS ── --}}
    <div class="flex-1 min-w-0 space-y-3">
        {{-- Toolbar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-4 py-3 flex items-center gap-2 flex-wrap">
            <button onclick="toggleGuides()" id="btnGuides"
                class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition text-xs font-medium">
                <i class="fas fa-border-all mr-1"></i>Grid Guide
            </button>
            <button onclick="resetLayout()"
                class="px-3 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition text-xs font-medium">
                <i class="fas fa-undo mr-1"></i>Reset Default
            </button>
            <div class="ml-auto flex items-center gap-2">
                <span id="saveStatus" class="text-xs text-gray-400"></span>
            </div>
        </div>

        {{-- Canvas area --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Canvas — A4 Landscape (297 × 210 mm)</span>
                <span class="text-[11px] text-gray-400">Click to select · Drag to move · Pull corner to resize</span>
            </div>
            <div id="canvasOuter" class="bg-gray-100 rounded-xl border border-gray-200 overflow-auto p-3">
                <div id="canvasScaleWrap" style="transform-origin:top left;">
                    <div id="certCanvas" class="relative bg-white shadow-md"
                         style="width:297mm;height:210mm;touch-action:none;">
                        <img id="canvasBgImg" src="{{ $bgUrl ?? '' }}"
                             class="{{ $bgUrl ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover pointer-events-none"
                             style="z-index:1;">
                        <div id="certGuides" class="absolute inset-0 pointer-events-none" style="z-index:5;display:none;">
                            <div class="g-grid"></div>
                            <div class="g-margin"></div>
                            <div class="g-border"></div>
                        </div>
                        <div id="elementsContainer" class="absolute inset-0" style="z-index:10;"></div>
                    </div>
                </div>
            </div>
            <p class="mt-2 text-center text-[11px] text-gray-400">
                Canvas is auto-zoomed to fit the screen. Click <strong>Preview</strong> to see the actual A4 size.
            </p>
        </div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="w-80 flex-shrink-0 space-y-0" style="max-height:82vh;overflow-y:auto;">

        {{-- Tab Nav --}}
        <div class="bg-white rounded-t-2xl border border-gray-200 border-b-0 px-2 pt-3 flex gap-1">
            @foreach([
                ['properties', 'Properties', 'fa-sliders-h', 'blue'],
                ['elements',   'Elements',   'fa-th-large',  'indigo'],
                ['assets',     'Assets',     'fa-images',    'amber'],
            ] as [$tab, $label, $icon, $color])
            <button id="tab-btn-{{ $tab }}"
                onclick="switchTab('{{ $tab }}')"
                class="tab-btn flex-1 py-2.5 px-2 rounded-t-lg text-xs font-bold transition flex flex-col items-center gap-1
                       {{ $tab === 'properties' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="fas {{ $icon }}"></i>
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- ── TAB: PROPERTI ── --}}
        <div id="tab-properties" class="tab-panel bg-white rounded-b-2xl border border-gray-200 border-t-0 p-4">
            <div id="propertiesContent">
                <div class="text-center py-8 text-gray-300">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-mouse-pointer text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-400">No element selected</p>
                    <p class="text-xs text-gray-300 mt-1">Click an element on the canvas to edit its properties here</p>
                </div>
            </div>
        </div>

        {{-- ── TAB: ELEMEN ── --}}
        <div id="tab-elements" class="tab-panel hidden bg-white rounded-b-2xl border border-gray-200 border-t-0 p-4 space-y-4">

            {{-- Core Data --}}
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-blue-600 text-[10px]"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Core Data</span>
                </div>
                <p class="text-[11px] text-gray-400 mb-2 pl-8">Volunteer information automatically filled when the certificate is generated.</p>
                <div class="pl-8 flex flex-wrap gap-1.5">
                    @foreach([
                        ['text-volunteer-name', 'Full Name', 'fa-user', true],
                        ['text-volunteer-role', 'Role / Position', 'fa-id-badge', false],
                        ['text-event-name', 'Event Name', 'fa-calendar-alt', false],
                        ['text-event-period', 'Event Period', 'fa-clock', false],
                        ['text-issue-date', 'Issue Date', 'fa-calendar-check', false],
                    ] as [$type, $lbl, $icon, $required])
                    <button onclick="addElement('{{ $type }}')"
                        class="px-2.5 py-1.5 text-[11px] font-medium rounded-lg border transition flex items-center gap-1
                               {{ $required ? 'bg-blue-50 border-blue-300 text-blue-700 hover:bg-blue-100' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas {{ $icon }} text-[9px]"></i>{{ $lbl }}
                        @if($required)<span class="ml-0.5 text-blue-400 font-bold" title="Required">*</span>@endif
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Certificate Text Template --}}
            <div class="border border-indigo-200 bg-indigo-50 rounded-xl p-3">
                <div class="flex items-start gap-2 mb-2">
                    <div class="w-6 h-6 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-paragraph text-indigo-600 text-[10px]"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-indigo-800 block">Certificate Text Template</span>
                        <p class="text-[11px] text-indigo-600 mt-0.5">Dynamic text such as: <em>"This certificate is awarded to [Name] as [Role]…"</em></p>
                    </div>
                </div>
                <button onclick="addElement('text-template')"
                    class="w-full py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-plus-circle"></i>Add Text Template
                </button>
            </div>

            {{-- Signatories --}}
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-pen-nib text-emerald-600 text-[10px]"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Signatories</span>
                </div>
                <p class="text-[11px] text-gray-400 mb-2 pl-8">Up to 3 signatories. Position can be freely moved on the canvas.</p>
                <div class="pl-8">
                    <button onclick="addSignature()"
                        class="w-full py-2 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                        <i class="fas fa-plus-circle"></i>Add Signatory
                    </button>
                    <p class="text-[10px] text-gray-400 mt-1 text-center">Max. 3 signatories per certificate</p>
                </div>
            </div>

            {{-- Visual Assets --}}
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-6 h-6 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-image text-amber-600 text-[10px]"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Visual Assets</span>
                </div>
                <p class="text-[11px] text-gray-400 mb-2 pl-8">Logo & QR. Upload images first in the <strong>Assets</strong> tab, then add them to the canvas here.</p>
                <div class="pl-8 flex flex-wrap gap-1.5">
                    <button onclick="addElement('event_logo')"
                        class="px-2.5 py-1.5 text-[11px] font-medium bg-amber-50 border border-amber-200 text-amber-700 rounded-lg hover:bg-amber-100 transition flex items-center gap-1">
                        <i class="fas fa-star text-[9px]"></i>Event Logo
                    </button>
                    <button onclick="addElement('org_logo')"
                        class="px-2.5 py-1.5 text-[11px] font-medium bg-amber-50 border border-amber-200 text-amber-700 rounded-lg hover:bg-amber-100 transition flex items-center gap-1">
                        <i class="fas fa-building text-[9px]"></i>Org Logo
                    </button>
                    <button onclick="addElement('qr_code')"
                        class="px-2.5 py-1.5 text-[11px] font-medium bg-gray-100 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-200 transition flex items-center gap-1">
                        <i class="fas fa-qrcode text-[9px]"></i>QR Code
                    </button>
                </div>
            </div>

            {{-- Daftar elemen di canvas --}}
            <div class="border-t border-gray-100 pt-3">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wide">Elements on Canvas</span>
                    <span id="elemCount" class="text-[10px] text-gray-400"></span>
                </div>
                <div id="elementsList" class="space-y-1"></div>
            </div>
        </div>

        {{-- ── TAB: ASET ── --}}
        <div id="tab-assets" class="tab-panel hidden bg-white rounded-b-2xl border border-gray-200 border-t-0 p-4 space-y-5">

            {{-- HOW IT WORKS --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl px-3 py-3">
                <p class="text-xs font-bold text-blue-800 mb-1.5"><i class="fas fa-lightbulb mr-1 text-blue-500"></i>How assets work:</p>
                <ol class="text-[11px] text-blue-700 space-y-1 list-decimal ml-4">
                    <li>Upload the image in this section first</li>
                    <li>The image will automatically appear on the canvas after uploading</li>
                    <li>Click <strong>Arrange on Canvas</strong> to reposition it</li>
                </ol>
            </div>

            {{-- ── SECTION 1: Background ── --}}
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="bg-gray-50 px-3 py-2 flex items-center gap-2 border-b border-gray-200">
                    <i class="fas fa-image text-blue-500"></i>
                    <span class="text-xs font-bold text-gray-700">Certificate Background</span>
                    <span class="ml-auto text-[10px] text-gray-400">Optional</span>
                </div>
                <div class="p-3">
                    <div id="bgDropzone"
                        class="relative h-24 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 overflow-hidden flex items-center justify-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition group"
                        onclick="if(!isLocked) document.getElementById('bgFile').click()">
                        <img id="bgPreview" src="{{ $bgUrl ?? '' }}" class="{{ $bgUrl ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover">
                        <div id="bgPh" class="{{ $bgUrl ? 'hidden' : '' }} text-center text-gray-400 pointer-events-none">
                            <i class="fas fa-cloud-upload-alt text-2xl mb-1 block text-gray-300"></i>
                            <span class="text-xs font-medium">Click to select background image</span>
                            <span class="block text-[10px] text-gray-300 mt-0.5">PNG / JPG / WebP, max. 5MB</span>
                        </div>
                    </div>
                    <input type="file" id="bgFile" accept="image/*" class="hidden" onchange="previewAsset('bgFile','bgPreview','bgPh','canvasBgImg'); doUploadAsset('background');">
                    <button onclick="doUploadAsset('background')" {{ $locked ? 'disabled' : '' }}
                        class="mt-2 w-full py-2 text-xs font-bold bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-1.5 {{ $locked ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-upload"></i>{{ $bgUrl ? 'Replace Background' : 'Upload Background' }}
                    </button>
                </div>
            </div>

            {{-- ── SECTION 2: Logos ── --}}
            <div class="border border-amber-200 rounded-xl overflow-hidden">
                <div class="bg-amber-50 px-3 py-2 flex items-center justify-between border-b border-amber-200">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-images text-amber-500"></i>
                        <span class="text-xs font-bold text-gray-700">Logo</span>
                        <span class="text-[10px] text-amber-600 bg-amber-100 rounded-full px-2 py-0.5" id="logoCountBadge">0–4 logo</span>
                    </div>
                    <button onclick="addExtraLogo()" {{ $locked ? 'disabled' : '' }}
                        id="btnAddLogo"
                        class="flex items-center gap-1 px-2.5 py-1 bg-amber-500 text-white text-[11px] font-bold rounded-lg hover:bg-amber-600 transition {{ $locked ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-plus text-[9px]"></i>Add Logo
                    </button>
                </div>
                <div class="p-3 space-y-3">
                    {{-- Fixed: Event Logo --}}
                    <div class="flex gap-3 items-start p-2.5 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-[10px] font-black text-amber-700">1</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-bold text-gray-700 mb-1">Logo Event <span class="text-red-500">*</span></p>
                            <div id="evLogoDropzone"
                                class="relative h-16 border-2 border-dashed border-gray-300 rounded-lg bg-white overflow-hidden flex items-center justify-center cursor-pointer hover:border-amber-400 transition"
                                onclick="if(!isLocked) document.getElementById('evLogoFile').click()">
                                <img id="evLogoPreview" src="{{ $evLogoUrl ?? '' }}" class="{{ $evLogoUrl ? '' : 'hidden' }} absolute inset-0 w-full h-full object-contain p-1">
                                <div id="evLogoPh" class="{{ $evLogoUrl ? 'hidden' : '' }} text-center text-gray-300 pointer-events-none">
                                    <i class="fas fa-star text-xl"></i>
                                </div>
                            </div>
                            <input type="file" id="evLogoFile" accept="image/*" class="hidden" onchange="previewAsset('evLogoFile','evLogoPreview','evLogoPh'); doUploadAsset('event_logo');">
                            <button onclick="doUploadAsset('event_logo')" {{ $locked ? 'disabled' : '' }}
                                class="mt-1 w-full py-1.5 text-[10px] font-bold bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition">
                                <i class="fas fa-upload mr-1"></i>{{ $evLogoUrl ? 'Replace' : 'Upload' }} Event Logo
                            </button>
                        </div>
                    </div>
                    {{-- Fixed: Org Logo --}}
                    <div class="flex gap-3 items-start p-2.5 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-[10px] font-black text-emerald-700">2</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-bold text-gray-700 mb-1">Logo Organisasi</p>
                            <div id="orgLogoDropzone"
                                class="relative h-16 border-2 border-dashed border-gray-300 rounded-lg bg-white overflow-hidden flex items-center justify-center cursor-pointer hover:border-emerald-400 transition"
                                onclick="if(!isLocked) document.getElementById('orgLogoFile').click()">
                                <img id="orgLogoPreview" src="{{ $orgLogoUrl ?? '' }}" class="{{ $orgLogoUrl ? '' : 'hidden' }} absolute inset-0 w-full h-full object-contain p-1">
                                <div id="orgLogoPh" class="{{ $orgLogoUrl ? 'hidden' : '' }} text-center text-gray-300 pointer-events-none">
                                    <i class="fas fa-building text-xl"></i>
                                </div>
                            </div>
                            <input type="file" id="orgLogoFile" accept="image/*" class="hidden" onchange="previewAsset('orgLogoFile','orgLogoPreview','orgLogoPh'); doUploadAsset('org_logo');">
                            <button onclick="doUploadAsset('org_logo')" {{ $locked ? 'disabled' : '' }}
                                class="mt-1 w-full py-1.5 text-[10px] font-bold bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                                <i class="fas fa-upload mr-1"></i>{{ $orgLogoUrl ? 'Replace' : 'Upload' }} Org Logo
                            </button>
                        </div>
                    </div>
                    {{-- Dynamic extra logos --}}
                    <div id="extraLogoList"></div>
                    <p class="text-[10px] text-amber-700 text-center">Click <strong>Add Logo</strong> above for 3rd, 4th logo, etc.</p>
                </div>
            </div>

            {{-- ── SECTION 3: Penandatangan ── --}}
            <div class="border border-emerald-200 rounded-xl overflow-hidden">
                <div class="bg-emerald-50 px-3 py-2 flex items-center justify-between border-b border-emerald-200">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-pen-nib text-emerald-600"></i>
                        <span class="text-xs font-bold text-gray-700">Signatories</span>
                        <span class="text-[10px] text-emerald-700 bg-emerald-100 rounded-full px-2 py-0.5" id="signerCountBadge">0 / 3</span>
                    </div>
                </div>
                <div class="p-3 space-y-3">
                    {{-- Empty state --}}
                    <div id="signerEmptyState" class="text-center py-5">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-pen-nib text-emerald-400 text-lg"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-600">No signatories yet</p>
                        <p class="text-[11px] text-gray-400 mt-1">Click the button below to add a signatory</p>
                    </div>
                    {{-- Signer cards (dynamic) --}}
                    <div id="signerAssetList" class="space-y-3"></div>
                    {{-- Add button - always visible --}}
                    <button onclick="addSignature()" {{ $locked ? 'disabled' : '' }}
                        id="btnAddSigner"
                        class="w-full py-3 border-2 border-dashed border-emerald-300 text-emerald-700 rounded-xl text-sm font-bold hover:bg-emerald-50 hover:border-emerald-500 transition flex items-center justify-center gap-2 {{ $locked ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <div class="w-6 h-6 bg-emerald-600 text-white rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-[10px]"></i>
                        </div>
                        Add Signatory
                    </button>
                    <p class="text-[10px] text-gray-400 text-center">Max. 3 signatories per certificate. Position & size of signature blocks can be adjusted on the canvas.</p>
                </div>
            </div>

        </div>
        {{-- end tabs --}}

    </div><!-- /right panel -->

</div><!-- /editor -->

</div><!-- /page -->
</div>

{{-- ════ STYLES ════ --}}
<style>
.cert-element{position:absolute;border:2px dashed rgba(59,130,246,.5);cursor:move;user-select:none;overflow:hidden;background:rgba(255,255,255,.01);box-sizing:border-box;touch-action:none;}
.cert-element.selected{border:2px solid #3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.18);}
.cert-element.type-signature{border-color:rgba(16,185,129,.5);}
.cert-element.type-signature.selected{border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.18);}
.cert-element.type-text-template{border-color:rgba(99,102,241,.5);}
.cert-element.type-text-template.selected{border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,.18);}
.cert-element .resize-handle{position:absolute;width:10px;height:10px;background:#3b82f6;border:2px solid white;cursor:se-resize;bottom:-5px;right:-5px;z-index:20;border-radius:2px;}
.cert-element.type-signature .resize-handle{background:#10b981;}
.cert-element.type-text-template .resize-handle{background:#6366f1;}
.cert-element .elem-label{position:absolute;top:2px;left:3px;font-size:8px;font-weight:700;color:rgba(59,130,246,.9);background:rgba(255,255,255,.9);padding:1px 4px;border-radius:3px;pointer-events:none;z-index:15;white-space:nowrap;overflow:hidden;max-width:90%;}
.cert-element.type-signature .elem-label{color:rgba(16,185,129,.9);}
.cert-element.type-text-template .elem-label{color:rgba(99,102,241,.9);}
.cert-element .elem-content{width:100%;height:100%;display:flex;align-items:center;justify-content:center;overflow:hidden;pointer-events:none;}
.cert-element .trunc-badge{position:absolute;top:-17px;right:0;background:#f59e0b;color:#111;font-size:8px;font-weight:700;padding:1px 4px;border-radius:3px;z-index:25;pointer-events:none;}
.ph-token{color:#f59e0b;font-weight:700;}
#certCanvas{touch-action:none;}
#certGuides .g-grid{position:absolute;inset:0;background-image:linear-gradient(to right,rgba(59,130,246,.1) 1px,transparent 1px),linear-gradient(to bottom,rgba(59,130,246,.1) 1px,transparent 1px);background-size:5mm 5mm;}
#certGuides .g-margin{position:absolute;inset:10mm;border:1px dashed rgba(59,130,246,.45);}
#certGuides .g-border{position:absolute;inset:0;border:1px dashed rgba(59,130,246,.65);}
.tab-btn.active{background:#2563eb;color:#fff;}
.signer-asset-card{background:#f9fafb;border:1px solid #e5e7eb;border-radius:.75rem;padding:.75rem;space-y:.5rem;}
</style>

{{-- ════ SCRIPTS ════ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.27/interact.min.js"></script>
<script>
@verbatim
const MM2PX=3.77953, PX2MM=1/MM2PX, PT2PX=96/72;
let currentLayout=null, selectedElId=null;
@endverbatim
let globalEventLogoPath = '{!! addslashes($evLogoPath ?? "") !!}' || null;
let globalOrgLogoPath = '{!! addslashes($orgLogoPath ?? "") !!}' || null;
let isLocked = {{ $locked ? 'true' : 'false' }};
let activeLayoutId = {{ $activeLayout ? $activeLayout->id : 'null' }};
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
const ROUTES = {
    active   : '{{ route("admin.certificate-layouts.active") }}',
    save     : '{{ route("admin.certificate-layouts.save") }}',
    reset    : '{{ route("admin.certificate-layouts.reset-default") }}',
    preview  : '{{ route("admin.certificate-layouts.preview-sample") }}',
    publish  : '{{ $activeLayout ? route("admin.certificate-layouts.publish", $activeLayout) : "" }}',
    uploadBase: '/admin/certificate-layouts/',
    storageBase: '/media',
};
@verbatim
const LABELS={
    'text-volunteer-name':'Full Name',
    'text-volunteer-role':'Role / Position',
    'text-event-name':'Event Name',
    'text-event-period':'Event Period',
    'text-issue-date':'Issue Date',
    'event_logo':'Event Logo',
    'org_logo':'Org Logo',
    'qr_code':'QR Code',
    'qr':'QR Code',
    'text-template':'Certificate Text',
    'signature':'Signature',
};
const SAMPLE={
    'text-volunteer-name':'Volunteer Full Name',
    'text-volunteer-role':'Photographer / Media',
    'text-event-period':'01 Jan 2026 – 05 Jan 2026',
};
@endverbatim
const SAMPLE_EVENT  = '{{ addslashes($event->title) }}';
const SAMPLE_DATE   = '{{ now()->format("d F Y") }}';
@verbatim
SAMPLE['text-event-name']  = SAMPLE_EVENT;
SAMPLE['text-issue-date']  = SAMPLE_DATE;

// ════ INIT ════════════════════════════════════════════
document.addEventListener('DOMContentLoaded',()=>{
    scaleCanvas();
    window.addEventListener('resize', scaleCanvas);
    loadLayout();
    if(isLocked){
        const b=document.getElementById('btnSave');
        if(b){b.disabled=true;b.classList.add('opacity-50','cursor-not-allowed');}
    }
});

// ════ TABS ════════════════════════════════════════════
function switchTab(tab){
    document.querySelectorAll('.tab-panel').forEach(p=>p.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b=>{
        b.classList.remove('bg-blue-600','text-white','shadow-sm');
        b.classList.add('text-gray-500');
    });
    document.getElementById('tab-'+tab).classList.remove('hidden');
    const btn=document.getElementById('tab-btn-'+tab);
    if(btn){
        btn.classList.add('bg-blue-600','text-white','shadow-sm');
        btn.classList.remove('text-gray-500');
    }
}

// ════ CANVAS SCALE ════════════════════════════════════
function scaleCanvas(){
    const wrap=document.getElementById('canvasScaleWrap');
    const outer=document.getElementById('canvasOuter');
    if(!wrap||!outer)return;
    const cW=297*MM2PX, cH=210*MM2PX;
    const avail=outer.clientWidth-24;
    const scale=Math.min(1,avail/cW);
    wrap.style.transform=`scale(${scale})`;
    wrap.style.width=cW+'px';
    outer.style.minHeight=(cH*scale+24)+'px';
}

// ════ LOAD LAYOUT ═════════════════════════════════════
function loadLayout(){
    fetch(ROUTES.active)
        .then(r=>r.json())
        .then(d=>{
            isLocked=d.is_locked??false;
            activeLayoutId=d.id??null;
            currentLayout=d.layout??defaultLayout();

            // Helper to build asset URL
            const getAssetUrl = path => path ? `${ROUTES.storageBase}/${path}` : '';

            // Restore canvas background URL
            if(d.background_path){
                const bgUrlStr = getAssetUrl(d.background_path);
                const bg=document.getElementById('canvasBgImg');
                if(bg){bg.src=bgUrlStr;bg.classList.remove('hidden');}
                const prev=document.getElementById('bgPreview');
                if(prev){prev.src=bgUrlStr;prev.classList.remove('hidden');}
                const ph=document.getElementById('bgPh');
                if(ph) ph.classList.add('hidden');
            }
            // Restore logo previews
            if(d.event_logo_path){
                globalEventLogoPath = d.event_logo_path;
                const elUrlStr = getAssetUrl(d.event_logo_path);
                const p=document.getElementById('evLogoPreview');
                if(p){p.src=elUrlStr;p.classList.remove('hidden');}
                const ph=document.getElementById('evLogoPh');
                if(ph) ph.classList.add('hidden');
            }
            if(d.org_logo_path){
                globalOrgLogoPath = d.org_logo_path;
                const olUrlStr = getAssetUrl(d.org_logo_path);
                const p=document.getElementById('orgLogoPreview');
                if(p){p.src=olUrlStr;p.classList.remove('hidden');}
                const ph=document.getElementById('orgLogoPh');
                if(ph) ph.classList.add('hidden');
            }

            renderCanvas(); renderElementsList(); renderSignerAssets(); renderExtraLogos();
        })
        .catch(()=>{ currentLayout=defaultLayout(); renderCanvas(); renderElementsList(); renderSignerAssets(); renderExtraLogos(); });
}

// ════ DEFAULT LAYOUT ══════════════════════════════════
function defaultLayout(){
    return {
        schemaVersion:'1.0.0', canvasType:'certificate',
        contentArea:{xMm:0,yMm:0,wMm:297,hMm:210},
        elements:[
            {id:'event_logo',    type:'event_logo',         label:'Event Logo',      visible:true, rect:{xMm:20, yMm:15, wMm:35,hMm:35}, style:{objectFit:'contain'}},
            {id:'org_logo',      type:'org_logo',           label:'Org Logo',        visible:true, rect:{xMm:242,yMm:15, wMm:35,hMm:35}, style:{objectFit:'contain'}},
            {id:'event_name',    type:'text-event-name',    label:'Event Name',      visible:true, rect:{xMm:60, yMm:22, wMm:177,hMm:14},style:{fontSizePt:16,fontWeight:'bold',fontStyle:'normal',align:'center',color:'#1a1a2e'}},
            {id:'volunteer_name',type:'text-volunteer-name',label:'Full Name',        visible:true, rect:{xMm:40, yMm:92, wMm:217,hMm:20},style:{fontSizePt:26,fontWeight:'bold',fontStyle:'normal',align:'center',color:'#1a1a2e'}},
            {id:'volunteer_role',type:'text-volunteer-role',label:'Role / Position',  visible:true, rect:{xMm:40, yMm:114,wMm:217,hMm:12},style:{fontSizePt:13,fontWeight:'normal',fontStyle:'normal',align:'center',color:'#4b5563'}},
            {id:'cert_sentence', type:'text-template',      label:'Certificate Text', visible:true,rect:{xMm:30,yMm:60,wMm:237,hMm:22},
             style:{fontSizePt:11,fontWeight:'normal',fontStyle:'normal',align:'center',color:'#374151',lineHeight:1.5},
             template:'This certificate is awarded to {{name}} as {{role}} for their participation in {{event_name}} held on {{event_period}}.'},
            {id:'event_period',  type:'text-event-period',  label:'Event Period',    visible:true,rect:{xMm:40,yMm:130,wMm:217,hMm:10},style:{fontSizePt:11,fontWeight:'normal',fontStyle:'normal',align:'center',color:'#6b7280'}},
            {id:'issue_date',    type:'text-issue-date',    label:'Issue Date',      visible:true,rect:{xMm:20,yMm:190,wMm:80, hMm:9}, style:{fontSizePt:9,fontWeight:'normal',fontStyle:'normal',align:'left',color:'#9ca3af'}},
            {id:'qr_code',       type:'qr_code',            label:'QR Code',         visible:true,rect:{xMm:252,yMm:163,wMm:28,hMm:28},style:{}},
            {id:'signature-1',   type:'signature',          label:'Signatory 1',     visible:true,rect:{xMm:20,yMm:155,wMm:65,hMm:45},
             style:{fontSizePt:10,align:'center',color:'#1a1a2e'},signerName:'Signatory Name',signerTitle:'Title / Position',signatureImagePath:null},
        ]
    };
}

// ════ RENDER CANVAS ═══════════════════════════════════
function isTextType(t){return t&&t.startsWith('text-');}
function isImgType(t){return['event_logo','org_logo','qr','qr_code'].includes(t);}

function renderCanvas(){
    const ctr=document.getElementById('elementsContainer');
    ctr.innerHTML='';
    (currentLayout?.elements||[]).forEach(el=>{
        if(!el.visible)return;
        ctr.appendChild(makeCanvasEl(el));
    });
    checkTruncation();
}

function makeCanvasEl(data){
    const el=document.createElement('div');
    const typeCls=data.type.replace(/[^a-z0-9]/g,'-');
    el.className=`cert-element type-${typeCls}`;
    el.id='elem-'+data.id;
    el.dataset.elementId=data.id;
    const r=data.rect;
    el.style.cssText=`left:${r.xMm}mm;top:${r.yMm}mm;width:${r.wMm}mm;height:${r.hMm}mm;`;

    const s=data.style||{};
    const lbl=data.label||LABELS[data.type]||data.id;
    let content='';

    if(isTextType(data.type)&&data.type!=='text-template'){
        const fs=(s.fontSizePt||12)*PT2PX,fw=s.fontWeight||'normal',fi=s.fontStyle||'normal';
        const al=s.align||'left',col=s.color||'#1a1a2e';
        const jc=al==='center'?'center':al==='right'?'flex-end':'flex-start';
        const txt=escH(SAMPLE[data.type]||lbl);
        content=`<div class="trunc-check w-full h-full overflow-hidden flex items-center"
            style="font-size:${fs}px;font-weight:${fw};font-style:${fi};text-align:${al};color:${col};
                   justify-content:${jc};white-space:nowrap;padding:0 4px;line-height:1.2;">${txt}</div>`;
    } else if(data.type==='text-template'){
        const fs=(s.fontSizePt||11)*PT2PX,fw=s.fontWeight||'normal',fi=s.fontStyle||'normal';
        const al=s.align||'center',col=s.color||'#374151',lh=s.lineHeight||1.5;
        const tmpl=(data.template||'').replace(/\{\{(\w+)\}\}/g,function(m,p){return '<span class="ph-token">{{'+p+'}}</span>';});
        content=`<div class="trunc-check w-full h-full overflow-hidden"
            style="font-size:${fs}px;font-weight:${fw};font-style:${fi};text-align:${al};color:${col};
                   line-height:${lh};padding:4px 6px;white-space:normal;word-break:break-word;">${tmpl}</div>`;
    } else if(data.type==='event_logo'){
        content=globalEventLogoPath
            ? `<img src="${ROUTES.storageBase}/${globalEventLogoPath}" style="width:100%;height:100%;object-fit:contain;padding:2px;">`
            : `<i class="fas fa-star text-amber-400 opacity-60" style="font-size:22px;"></i>`;
    } else if(data.type==='org_logo'){
        content=globalOrgLogoPath
            ? `<img src="${ROUTES.storageBase}/${globalOrgLogoPath}" style="width:100%;height:100%;object-fit:contain;padding:2px;">`
            : `<i class="fas fa-building text-emerald-400 opacity-60" style="font-size:22px;"></i>`;
    } else if(data.type==='extra_logo'){
        content=data.imagePath
            ?`<img src="${ROUTES.storageBase}/${data.imagePath}" style="width:100%;height:100%;object-fit:contain;padding:2px;">`
            :`<i class="fas fa-image text-amber-300 opacity-60" style="font-size:22px;"></i>`;
    } else if(data.type==='qr'||data.type==='qr_code'){
        content=`<i class="fas fa-qrcode text-gray-400 opacity-80" style="font-size:22px;"></i>`;
    } else if(data.type==='signature'){
        const fs=(s.fontSizePt||10)*PT2PX,col=s.color||'#1a1a2e';
        const sigImg=data.signatureImagePath
            ?`<img src="${ROUTES.storageBase}/${data.signatureImagePath}" style="max-height:36%;object-fit:contain;margin-bottom:2px;">`
            :`<div style="height:28%;border-bottom:1.5px dashed #d1d5db;width:80%;margin-bottom:4px;"></div>`;
        content=`<div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;padding:4px 6px;">
            ${sigImg}
            <div style="border-top:1.5px solid #374151;width:90%;margin-bottom:3px;"></div>
            <div style="font-size:${fs}px;font-weight:bold;color:${col};text-align:center;line-height:1.2;">${escH(data.signerName||'Nama Penandatangan')}</div>
            <div style="font-size:${fs*.85}px;color:#6b7280;text-align:center;line-height:1.2;">${escH(data.signerTitle||'Jabatan')}</div>
        </div>`;
    }

    el.innerHTML=`<div class="elem-label">${escH(lbl)}</div><div class="elem-content">${content}</div><div class="resize-handle"></div>`;
    el.addEventListener('click',e=>{e.stopPropagation();selectEl(data.id);});

    if(!isLocked&&typeof interact!=='undefined'){
        interact(el)
        .draggable({inertia:false,modifiers:[interact.modifiers.restrictRect({restriction:'parent',endOnly:false})],
            listeners:{
                move(ev){
                    const cs=window.getComputedStyle(ev.target);
                    const lft=(parseFloat(cs.left)||0)+ev.dx;
                    const tp=(parseFloat(cs.top)||0)+ev.dy;
                    ev.target.style.left=lft+'px';ev.target.style.top=tp+'px';
                    updRectData(data.id,{xMm:lft*PX2MM,yMm:tp*PX2MM});
                },
                end(){syncPosList();}
            }
        })
        .resizable({edges:{right:true,bottom:true},modifiers:[interact.modifiers.restrictSize({min:{width:20,height:12}})],
            listeners:{
                move(ev){
                    ev.target.style.width=ev.rect.width+'px';ev.target.style.height=ev.rect.height+'px';
                    updRectData(data.id,{wMm:ev.rect.width*PX2MM,hMm:ev.rect.height*PX2MM});
                },
                end(){checkTruncation();syncPosList();}
            }
        });
    }
    return el;
}

// ════ ADD ELEMENTS ════════════════════════════════════
function addElement(type){
    if(isLocked){alert('Layout sudah terkunci.');return;}
    const nonRepeat=['text-volunteer-name','text-volunteer-role','text-event-name',
                     'text-event-period','text-issue-date','event_logo','org_logo','qr_code','qr'];
    if(nonRepeat.includes(type)){
        const ex=(currentLayout?.elements||[]).find(e=>e.type===type);
        if(ex){ex.visible=true;renderCanvas();renderElementsList();selectEl(ex.id);switchTab('properties');return;}
    }
    const id=type+'-'+Date.now();
    const base={id,type,label:LABELS[type]||type,visible:true};
    let newEl;
    switch(type){
        case 'text-volunteer-name': newEl={...base,rect:{xMm:40,yMm:92,wMm:217,hMm:20},style:{fontSizePt:26,fontWeight:'bold',fontStyle:'normal',align:'center',color:'#1a1a2e'}}; break;
        case 'text-volunteer-role': newEl={...base,rect:{xMm:40,yMm:114,wMm:217,hMm:12},style:{fontSizePt:13,fontWeight:'normal',fontStyle:'normal',align:'center',color:'#4b5563'}}; break;
        case 'text-event-name':    newEl={...base,rect:{xMm:60,yMm:22,wMm:177,hMm:14},style:{fontSizePt:16,fontWeight:'bold',fontStyle:'normal',align:'center',color:'#1a1a2e'}}; break;
        case 'text-event-period':  newEl={...base,rect:{xMm:40,yMm:130,wMm:217,hMm:10},style:{fontSizePt:11,fontWeight:'normal',fontStyle:'normal',align:'center',color:'#6b7280'}}; break;
        case 'text-issue-date':    newEl={...base,rect:{xMm:20,yMm:190,wMm:80,hMm:9},style:{fontSizePt:9,fontWeight:'normal',fontStyle:'normal',align:'left',color:'#9ca3af'}}; break;
        case 'text-template':      newEl={...base,label:'Certificate Text',rect:{xMm:30,yMm:60,wMm:237,hMm:22},style:{fontSizePt:11,fontWeight:'normal',fontStyle:'normal',align:'center',color:'#374151',lineHeight:1.5},template:'This certificate is awarded to {{name}} as {{role}} for their participation in {{event_name}} held on {{event_period}}.'}; break;
        case 'event_logo':         newEl={...base,rect:{xMm:20,yMm:15,wMm:35,hMm:35},style:{objectFit:'contain'}}; break;
        case 'org_logo':           newEl={...base,rect:{xMm:242,yMm:15,wMm:35,hMm:35},style:{objectFit:'contain'}}; break;
        case 'qr_code': case 'qr':newEl={...base,type:'qr_code',label:'QR Code',rect:{xMm:252,yMm:163,wMm:28,hMm:28},style:{}}; break;
        default: newEl={...base,rect:{xMm:50,yMm:50,wMm:80,hMm:20},style:{fontSizePt:12,align:'left',color:'#1a1a2e'}};
    }
    currentLayout.elements.push(newEl);
    renderCanvas();renderElementsList();renderSignerAssets();
    selectEl(newEl.id);switchTab('properties');
}

function addSignature(){
    if(isLocked){alert('This layout is locked. Please revert to draft first.');return;}
    const sigs=(currentLayout?.elements||[]).filter(e=>e.type==='signature');
    if(sigs.length>=3){alert('Maximum 3 signatories per certificate.');return;}
    const idx=sigs.length+1;
    const xPos=20+(idx-1)*80;
    const newEl={
        id:'signature-'+Date.now(), type:'signature', label:`Signatory ${idx}`, visible:true,
        rect:{xMm:xPos,yMm:155,wMm:65,hMm:45},
        style:{fontSizePt:10,align:'center',color:'#1a1a2e'},
        signerName:`Signatory ${idx}`, signerTitle:'Title / Position', signatureImagePath:null
    };
    currentLayout.elements.push(newEl);
    renderCanvas();renderElementsList();renderSignerAssets();
    selectEl(newEl.id);switchTab('assets');
}

// ════ ELEMENT LIST (Elemen tab) ════════════════════════
function renderElementsList(){
    const list=document.getElementById('elementsList');
    if(!list)return;
    list.innerHTML='';
    const els=currentLayout?.elements||[];
    const cnt=document.getElementById('elemCount');
    if(cnt) cnt.textContent=`${els.filter(e=>e.visible).length} / ${els.length} active`;

    els.forEach((el,idx)=>{
        const lbl=el.label||LABELS[el.type]||el.id;
        const item=document.createElement('div');
        item.id='sitem-'+el.id;
        item.className='flex items-center gap-2 px-2.5 py-2 bg-white rounded-lg border border-gray-200 cursor-pointer hover:border-blue-400 transition text-xs';
        item.innerHTML=`
            <input type="checkbox" ${el.visible?'checked':''} class="form-checkbox text-blue-600 w-3.5 h-3.5 flex-shrink-0"
                onclick="event.stopPropagation();toggleVis(${idx})" ${isLocked?'disabled':''}>
            <span class="flex-1 truncate font-medium text-gray-700">${escH(lbl)}</span>
            ${!isLocked&&el.type==='signature'?`<button onclick="event.stopPropagation();deleteEl('${el.id}')" class="text-red-400 hover:text-red-600 flex-shrink-0" title="Hapus"><i class="fas fa-trash-alt text-[10px]"></i></button>`:''}
        `;
        item.addEventListener('click',()=>{ selectEl(el.id); switchTab('properties'); });
        list.appendChild(item);
    });
}

function syncPosList(){
    (currentLayout?.elements||[]).forEach(el=>{
        const d=document.getElementById('spos-'+el.id);
        if(d) d.textContent=`${el.rect.xMm.toFixed(1)}×${el.rect.yMm.toFixed(1)} mm`;
    });
}

// ════ SIGNER ASSET CARDS (Aset tab) ═══════════════════
function renderSignerAssets(){
    const container=document.getElementById('signerAssetList');
    if(!container)return;
    const sigs=(currentLayout?.elements||[]).filter(e=>e.type==='signature');

    const badge=document.getElementById('signerCountBadge');
    if(badge) badge.textContent=`${sigs.length} / 3`;
    const emptyState=document.getElementById('signerEmptyState');
    if(emptyState) emptyState.style.display=sigs.length?'none':'block';
    const addBtn=document.getElementById('btnAddSigner');
    if(addBtn){addBtn.disabled=isLocked||(sigs.length>=3);}

    container.innerHTML='';
    const COLORS=['emerald','blue','violet'];
    sigs.forEach((sig,i)=>{
        const c=COLORS[i]||'gray';
        const card=document.createElement('div');
        card.className='signer-card-item rounded-xl overflow-hidden border-2';
        card.style.borderColor=({emerald:'#6ee7b7',blue:'#93c5fd',violet:'#c4b5fd'}[c]||'#d1d5db');
        card.innerHTML=`
            <div style="background:${{emerald:'#ecfdf5',blue:'#eff6ff',violet:'#f5f3ff'}[c]};border-bottom:1px solid ${{emerald:'#6ee7b7',blue:'#93c5fd',violet:'#c4b5fd'}[c]};" class="flex items-center gap-2 px-3 py-2">
                <div style="background:${{emerald:'#059669',blue:'#2563eb',violet:'#7c3aed'}[c]};" class="w-8 h-8 rounded-full text-white flex items-center justify-center font-black text-base flex-shrink-0">${i+1}</div>
                <span class="text-sm font-bold flex-1" style="color:${{emerald:'#065f46',blue:'#1e3a8a',violet:'#4c1d95'}[c]}">Signatory ${i+1}</span>
                ${!isLocked?`<button onclick="deleteEl('${sig.id}')" class="text-red-500 hover:text-red-700 text-xs font-bold px-2 py-1 bg-red-50 rounded-lg border border-red-200"><i class="fas fa-trash-alt mr-1"></i>Remove</button>`:''}
            </div>
            <div class="p-3 space-y-2.5 bg-white">
                <div>
                    <label class="text-[10px] font-bold text-gray-600 block mb-1">📷 Signature Photo</label>
                    <div class="relative h-20 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 overflow-hidden flex items-center justify-center cursor-pointer hover:border-gray-400 transition"
                         onclick="if(!isLocked) document.getElementById('sigFile-${sig.id}').click()">
                        ${sig.signatureImagePath
                            ?`<img src="${ROUTES.storageBase}/${escH(sig.signatureImagePath)}" class="absolute inset-0 w-full h-full object-contain p-1">`
                            :`<div class="text-center text-gray-300 pointer-events-none"><i class="fas fa-signature text-2xl block mb-1"></i><span class="text-[10px] font-medium text-gray-400">Click to upload signature photo</span><span class="block text-[9px] text-gray-300">Transparent PNG recommended</span></div>`}
                    </div>
                    <input type="file" id="sigFile-${sig.id}" accept="image/*" class="hidden" onchange="uploadSigImg('${sig.id}',this)">
                    ${!isLocked?`<button onclick="document.getElementById('sigFile-${sig.id}').click()"
                        style="background:${{emerald:'#059669',blue:'#2563eb',violet:'#7c3aed'}[c]}"
                        class="mt-1.5 w-full py-2 text-xs font-bold text-white rounded-lg hover:opacity-90 transition flex items-center justify-center gap-1.5">
                        <i class="fas fa-upload"></i>${sig.signatureImagePath?'Replace Signature Photo':'Upload Signature Photo'}
                    </button>`:''}
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-600 block mb-1">Signatory Name</label>
                    <input type="text" value="${escH(sig.signerName||'')}"
                        oninput="updField('${sig.id}','signerName',this.value)"
                        placeholder="Full name..."
                        class="w-full px-2.5 py-2 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
                        ${isLocked?'disabled':''}>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-600 block mb-1">Title / Position</label>
                    <input type="text" value="${escH(sig.signerTitle||'')}"
                        oninput="updField('${sig.id}','signerTitle',this.value)"
                        placeholder="Title / position..."
                        class="w-full px-2.5 py-2 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
                        ${isLocked?'disabled':''}>
                </div>
                <button onclick="selectEl('${sig.id}');switchTab('properties')"
                    class="w-full py-2 text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-200 transition flex items-center justify-center gap-1.5">
                    <i class="fas fa-arrows-alt"></i>Arrange on Canvas
                </button>
            </div>
        `;
        container.appendChild(card);
    });
}

// ════ EXTRA LOGOS (up to 4 total) ════════════════════
function addExtraLogo(){
    if(isLocked){alert('This layout is locked. Please revert to draft first.');return;}
    const extras=(currentLayout?.elements||[]).filter(e=>e.type==='extra_logo');
    if((2+extras.length)>=4){alert('Maximum 4 logos per certificate (including Event Logo & Org Logo).');return;}
    const num=3+extras.length;
    const newEl={
        id:'extra_logo-'+Date.now(), type:'extra_logo',
        label:`Logo ${num}`, visible:true,
        rect:{xMm:20+(num-1)*60,yMm:15,wMm:35,hMm:35},
        style:{objectFit:'contain'}, imagePath:null
    };
    currentLayout.elements.push(newEl);
    renderCanvas();renderElementsList();renderExtraLogos();
}

function renderExtraLogos(){
    const container=document.getElementById('extraLogoList');
    if(!container)return;
    container.innerHTML='';
    const extras=(currentLayout?.elements||[]).filter(e=>e.type==='extra_logo');
    const total=2+extras.length;
    const badge=document.getElementById('logoCountBadge');
    if(badge) badge.textContent=`${total} / 4 logo`;
    const addBtn=document.getElementById('btnAddLogo');
    if(addBtn) addBtn.disabled=isLocked||(total>=4);

    extras.forEach((logo,i)=>{
        const num=3+i;
        const d=document.createElement('div');
        d.className='flex gap-3 items-start p-2.5 bg-amber-50 rounded-lg border border-amber-200';
        d.innerHTML=`
            <div class="w-7 h-7 rounded-lg bg-amber-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                <span class="text-[10px] font-black text-amber-800">${num}</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[11px] font-bold text-gray-700">Extra Logo (no. ${num})</p>
                    ${!isLocked?`<button onclick="deleteEl('${logo.id}');renderExtraLogos();" class="text-red-400 hover:text-red-600 text-[10px] font-bold"><i class="fas fa-trash-alt"></i> Remove</button>`:''}
                </div>
                <div class="relative h-14 border-2 border-dashed border-gray-300 rounded-lg bg-white overflow-hidden flex items-center justify-center cursor-pointer hover:border-amber-400 transition"
                     onclick="if(!isLocked) document.getElementById('xLogoFile-${logo.id}').click()">
                    ${logo.imagePath
                        ?`<img src="${ROUTES.storageBase}/${escH(logo.imagePath)}" class="absolute inset-0 w-full h-full object-contain p-1">`
                        :`<div class="text-gray-300 pointer-events-none text-center"><i class="fas fa-image text-xl"></i></div>`}
                </div>
                <input type="file" id="xLogoFile-${logo.id}" accept="image/*" class="hidden" onchange="uploadExtraLogo('${logo.id}',this)">
                ${!isLocked?`<button onclick="document.getElementById('xLogoFile-${logo.id}').click()" class="mt-1 w-full py-1.5 text-[10px] font-bold bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition"><i class="fas fa-upload mr-1"></i>${logo.imagePath?'Replace':'Upload'} Logo ${num}</button>`:''}
                <button onclick="selectEl('${logo.id}');switchTab('properties')" class="mt-1 w-full py-1.5 text-[10px] font-bold bg-white text-amber-700 border border-amber-300 rounded-lg hover:bg-amber-50 transition"><i class="fas fa-arrows-alt mr-1"></i>Arrange on Canvas</button>
            </div>
        `;
        container.appendChild(d);
    });
}

function uploadExtraLogo(elementId, input){
    if(isLocked){alert('Layout sudah terkunci.');return;}
    const file=input.files[0];if(!file)return;
    if(!activeLayoutId){alert('Simpan layout dulu sebelum upload logo.');return;}
    const fd=new FormData();fd.append('element_id',elementId);fd.append('file',file);
    fetch(ROUTES.uploadBase+activeLayoutId+'/upload-signature',{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd})
    .then(r=>r.json()).then(d=>{
        if(d.success){
            const el=(currentLayout?.elements||[]).find(e=>e.id===elementId);
            if(el){el.imagePath=d.path;renderCanvas();renderExtraLogos();}
        } else alert('Error: '+(d.error||'Upload failed'));
    }).catch(()=>alert('Upload failed. Please try again.'));
}


// ════ SELECT + PROPERTIES ══════════════════════════════
function selectEl(id){
    document.querySelectorAll('.cert-element').forEach(e=>e.classList.remove('selected'));
    const dom=document.getElementById('elem-'+id);
    if(dom) dom.classList.add('selected');

    document.querySelectorAll('[id^="sitem-"]').forEach(e=>{
        e.classList.remove('border-blue-500','bg-blue-50');
        e.classList.add('border-gray-200');
    });
    const si=document.getElementById('sitem-'+id);
    if(si){si.classList.add('border-blue-500','bg-blue-50');si.classList.remove('border-gray-200');}

    selectedElId=id;
    renderProperties(id);
    switchTab('properties');
}

function renderProperties(id){
    const data=(currentLayout?.elements||[]).find(e=>e.id===id);
    if(!data)return;
    const s=data.style||{};
    const lbl=data.label||LABELS[data.type]||id;

    const ICON={
        'text-volunteer-name':'fa-user','text-volunteer-role':'fa-id-badge',
        'text-event-name':'fa-calendar-alt','text-event-period':'fa-clock',
        'text-issue-date':'fa-calendar-check','event_logo':'fa-star',
        'org_logo':'fa-building','qr_code':'fa-qrcode','qr':'fa-qrcode',
        'text-template':'fa-paragraph','signature':'fa-pen-nib'
    };
    const BADGE={'text-template':'bg-indigo-100 text-indigo-700',signature:'bg-emerald-100 text-emerald-700'};
    const badgeCls=BADGE[data.type]||'bg-blue-100 text-blue-700';

    let html=`<div class="space-y-3">
        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-gray-50 border border-gray-200">
            <div class="w-8 h-8 rounded-lg ${badgeCls} flex items-center justify-center flex-shrink-0">
                <i class="fas ${ICON[data.type]||'fa-square'} text-sm"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-gray-900">${escH(lbl)}</div>
                <div class="text-[10px] text-gray-400">${data.type}</div>
            </div>
        </div>`;

    // ── TEXT TEMPLATE ─────────────────────────
    if(data.type==='text-template'){
        const al=s.align||'center';
        html+=`
        <div>
            <label class="prop-label">Template Text</label>
            <textarea rows="5" id="tmplTA-${id}"
                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg font-mono resize-y focus:outline-none focus:ring-2 focus:ring-indigo-400"
                oninput="updTemplate('${id}',this.value)">${escH(data.template||'')}</textarea>
            <div class="mt-1 text-[10px] text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg px-2 py-1.5">
                <strong>Placeholder:</strong> @{{name}} @{{role}} @{{event_name}} @{{event_period}} @{{issue_date}}
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="prop-label">Font Size (pt)</label>
                <input type="number" min="6" max="48" value="${s.fontSizePt||11}"
                    onchange="updStyle('${id}','fontSizePt',this.value)" class="prop-input">
            </div>
            <div>
                <label class="prop-label">Line Height</label>
                <input type="number" min="1" max="3" step="0.1" value="${s.lineHeight||1.5}"
                    onchange="updStyle('${id}','lineHeight',this.value)" class="prop-input">
            </div>
        </div>
        <div>
            <label class="prop-label">Text Alignment</label>
            <div class="flex gap-1">${['left','center','right'].map(a=>`<button onclick="updStyle('${id}','align','${a}')"
                class="flex-1 py-1.5 text-[11px] border rounded-lg font-bold transition ${al===a?'bg-indigo-100 border-indigo-500 text-indigo-700':'border-gray-300 text-gray-500 hover:bg-gray-100'}">
                ${a==='left'?'Left':a==='center'?'Center':'Right'}</button>`).join('')}</div>
        </div>
        <div>
            <label class="prop-label">Text Color</label>
            <div class="flex items-center gap-2">
                <input type="color" value="${s.color||'#374151'}" oninput="updStyle('${id}','color',this.value)"
                    class="w-10 h-8 p-0.5 border border-gray-300 rounded-lg cursor-pointer">
                <input type="text" value="${s.color||'#374151'}" onblur="updStyle('${id}','color',this.value)"
                    class="flex-1 px-2 py-1.5 text-xs border border-gray-300 rounded-lg font-mono">
            </div>
        </div>`;
    }
    // ── SIGNATURE ─────────────────────────────
    else if(data.type==='signature'){
        html+=`
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 text-[11px] text-emerald-700 flex items-start gap-2">
            <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
            <span>Manage name, title & signature photo in the <strong>Assets</strong> tab. Use this panel to adjust position & size on the canvas.</span>
        </div>
        <div>
            <label class="prop-label">Name Font Size (pt)</label>
            <input type="number" min="6" max="24" value="${s.fontSizePt||10}"
                onchange="updStyle('${id}','fontSizePt',this.value)" class="prop-input">
        </div>`;
    }
    // ── REGULAR TEXT ──────────────────────────
    else if(isTextType(data.type)){
        const al=s.align||'left';
        html+=`
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="prop-label">Font Size (pt)</label>
                <input type="number" min="6" max="72" value="${s.fontSizePt||12}"
                    onchange="updStyle('${id}','fontSizePt',this.value)" class="prop-input">
            </div>
            <div>
                <label class="prop-label">Weight</label>
                <select onchange="updStyle('${id}','fontWeight',this.value)" class="prop-input">
                    <option value="normal" ${(s.fontWeight||'normal')==='normal'?'selected':''}>Normal</option>
                    <option value="bold"   ${s.fontWeight==='bold'?'selected':''}>Bold</option>
                    <option value="600"    ${s.fontWeight==='600'?'selected':''}>Semi-Bold</option>
                    <option value="300"    ${s.fontWeight==='300'?'selected':''}>Light</option>
                </select>
            </div>
        </div>
        <div>
            <label class="prop-label">Font Style</label>
            <select onchange="updStyle('${id}','fontStyle',this.value)" class="prop-input">
                <option value="normal" ${(s.fontStyle||'normal')==='normal'?'selected':''}>Normal</option>
                <option value="italic" ${s.fontStyle==='italic'?'selected':''}>Italic</option>
            </select>
        </div>
        <div>
            <label class="prop-label">Text Alignment</label>
            <div class="flex gap-1">${['left','center','right'].map(a=>`<button onclick="updStyle('${id}','align','${a}')"
                class="flex-1 py-1.5 text-[11px] border rounded-lg font-bold transition ${al===a?'bg-blue-100 border-blue-500 text-blue-700':'border-gray-300 text-gray-500 hover:bg-gray-100'}">
                ${a==='left'?'Left':a==='center'?'Center':'Right'}</button>`).join('')}</div>
        </div>
        <div>
            <label class="prop-label">Text Color</label>
            <div class="flex items-center gap-2">
                <input type="color" value="${s.color||'#1a1a2e'}" oninput="updStyle('${id}','color',this.value)"
                    class="w-10 h-8 p-0.5 border border-gray-300 rounded-lg cursor-pointer">
                <input type="text" value="${s.color||'#1a1a2e'}" onblur="updStyle('${id}','color',this.value)"
                    class="flex-1 px-2 py-1.5 text-xs border border-gray-300 rounded-lg font-mono">
            </div>
        </div>`;
    }
    // ── IMAGE ─────────────────────────────────
    else if(isImgType(data.type)){
        html+=`
        <div class="text-[11px] text-amber-700 bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2">
            <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
            <span>Upload the logo image in the <strong>Assets</strong> tab, then adjust its position here.</span>
        </div>`;
    }

    // ── Rect inputs ───────────────────────────
    html+=`<div class="border-t border-gray-100 pt-3">
        <label class="prop-label mb-2">Position & Size (mm)</label>
        <div class="grid grid-cols-2 gap-1.5">
            ${[['xMm','X (left)'],['yMm','Y (top)'],['wMm','Width'],['hMm','Height']].map(([k,l])=>`
            <div>
                <label class="text-[9px] text-gray-400 font-medium">${l}</label>
                <input type="number" step="0.5" value="${data.rect[k].toFixed(1)}"
                    onchange="updRectInput('${id}','${k}',this.value)"
                    class="w-full px-2 py-1 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-400">
            </div>`).join('')}
        </div>
    </div>`;

    if(data.type==='signature'&&!isLocked){
        html+=`<button onclick="deleteEl('${id}')"
            class="w-full py-2 text-xs font-bold text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
            <i class="fas fa-trash-alt mr-1"></i>Remove Signatory
        </button>`;
    }

    html+='</div>';
    document.getElementById('propertiesContent').innerHTML=html;
}

// ════ UPDATE HELPERS ══════════════════════════════════
function updRectData(id,upd){ const el=(currentLayout?.elements||[]).find(e=>e.id===id); if(el) Object.assign(el.rect,upd); }
function updRectInput(id,key,val){
    const el=(currentLayout?.elements||[]).find(e=>e.id===id);
    if(!el)return;
    el.rect[key]=parseFloat(val)||0;
    const dom=document.getElementById('elem-'+id);
    if(dom){
        if(key==='xMm')dom.style.left=el.rect.xMm+'mm';
        if(key==='yMm')dom.style.top=el.rect.yMm+'mm';
        if(key==='wMm')dom.style.width=el.rect.wMm+'mm';
        if(key==='hMm')dom.style.height=el.rect.hMm+'mm';
    }
    syncPosList();
}
function updStyle(id,key,val){
    const el=(currentLayout?.elements||[]).find(e=>e.id===id);
    if(!el)return;
    if(!el.style)el.style={};
    el.style[key]=['fontSizePt','lineHeight'].includes(key)?(parseFloat(val)||0):val;
    refreshCanvasEl(id);renderProperties(id);checkTruncation();
}
function updTemplate(id,val){
    const el=(currentLayout?.elements||[]).find(e=>e.id===id);
    if(!el)return; el.template=val; refreshCanvasEl(id); checkTruncation();
}
function updField(id,key,val){
    const el=(currentLayout?.elements||[]).find(e=>e.id===id);
    if(!el)return; el[key]=val; refreshCanvasEl(id);
}
function refreshCanvasEl(id){
    const data=(currentLayout?.elements||[]).find(e=>e.id===id);
    if(!data)return;
    const dom=document.getElementById('elem-'+id);
    if(!dom)return;
    const cDiv=dom.querySelector('.elem-content');
    if(!cDiv)return;
    const s=data.style||{};
    const fs=(s.fontSizePt||12)*PT2PX,fw=s.fontWeight||'normal',fi=s.fontStyle||'normal';
    const al=s.align||'left',col=s.color||'#1a1a2e';
    const jc=al==='center'?'center':al==='right'?'flex-end':'flex-start';

    if(data.type==='text-template'){
        const lh=s.lineHeight||1.5;
        const tmpl=(data.template||'').replace(/\{\{(\w+)\}\}/g,function(m,p){return '<span class="ph-token">{{'+p+'}}</span>';});
        cDiv.innerHTML=`<div class="trunc-check w-full h-full overflow-hidden"
            style="font-size:${fs}px;font-weight:${fw};font-style:${fi};text-align:${al};color:${col};
                   line-height:${lh};padding:4px 6px;white-space:normal;word-break:break-word;">${tmpl}</div>`;
    } else if(isTextType(data.type)){
        const txt=escH(SAMPLE[data.type]||data.label||data.id);
        cDiv.innerHTML=`<div class="trunc-check w-full h-full overflow-hidden flex items-center"
            style="font-size:${fs}px;font-weight:${fw};font-style:${fi};text-align:${al};color:${col};
                   justify-content:${jc};white-space:nowrap;padding:0 4px;line-height:1.2;">${txt}</div>`;
    } else if(data.type==='signature'){
        const sigImg=data.signatureImagePath
            ?`<img src="${ROUTES.storageBase}/${data.signatureImagePath}" style="max-height:36%;object-fit:contain;margin-bottom:2px;">`
            :`<div style="height:28%;border-bottom:1.5px dashed #d1d5db;width:80%;margin-bottom:4px;"></div>`;
        cDiv.innerHTML=`<div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;padding:4px 6px;">
            ${sigImg}
            <div style="border-top:1.5px solid #374151;width:90%;margin-bottom:3px;"></div>
            <div style="font-size:${fs}px;font-weight:bold;color:${col};text-align:center;line-height:1.2;">${escH(data.signerName||'Nama Penandatangan')}</div>
            <div style="font-size:${fs*.85}px;color:#6b7280;text-align:center;line-height:1.2;">${escH(data.signerTitle||'Jabatan')}</div>
        </div>`;
    }
}
function toggleVis(idx){
    if(isLocked)return;
    currentLayout.elements[idx].visible=!currentLayout.elements[idx].visible;
    renderCanvas();renderElementsList();
}
function deleteEl(id){
    if(isLocked)return;
    if(!confirm('Hapus elemen ini dari canvas?'))return;
    currentLayout.elements=currentLayout.elements.filter(e=>e.id!==id);
    renderCanvas();renderElementsList();renderSignerAssets();
    document.getElementById('propertiesContent').innerHTML=
        '<div class="text-center py-8 text-gray-300"><i class="fas fa-mouse-pointer text-2xl mb-2 block"></i><p class="text-sm text-gray-400">Klik elemen di canvas</p></div>';
    selectedElId=null;
}
function checkTruncation(){
    document.querySelectorAll('.cert-element').forEach(el=>{
        el.querySelectorAll('.trunc-badge').forEach(b=>b.remove());
        const t=el.querySelector('.trunc-check');
        if(!t)return;
        if(t.scrollWidth>t.clientWidth+2||t.scrollHeight>t.clientHeight+2){
            const b=document.createElement('div');
            b.className='trunc-badge';b.textContent='Terpotong';
            el.appendChild(b);
        }
    });
}

// ════ GUIDES ══════════════════════════════════════════
function toggleGuides(){
    const g=document.getElementById('certGuides');
    const btn=document.getElementById('btnGuides');
    const show=g.style.display!=='block';
    g.style.display=show?'block':'none';
    btn.classList.toggle('bg-blue-600',show);
    btn.classList.toggle('text-white',show);
    btn.classList.toggle('bg-gray-100',!show);
    btn.classList.toggle('text-gray-700',!show);
}

// ════ SAVE / RESET ════════════════════════════════════
function saveLayout(){
    if(isLocked){alert('Layout sudah terkunci.');return;}
    const btn=document.getElementById('btnSave');
    const stat=document.getElementById('saveStatus');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin mr-1.5"></i>Menyimpan...';
    fetch(ROUTES.save,{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body:JSON.stringify({layout_json:JSON.stringify(currentLayout),name:'Layout '+new Date().toLocaleDateString('id-ID')}),
    })
    .then(r=>r.json())
    .then(d=>{
        if(d.success){
            activeLayoutId=d.id;
            btn.innerHTML='<i class="fas fa-check mr-1.5"></i>Tersimpan!';
            btn.classList.replace('bg-blue-600','bg-green-600');
            if(stat) stat.textContent='Disimpan '+new Date().toLocaleTimeString('id-ID');
            setTimeout(()=>{ btn.innerHTML='<i class="fas fa-check-circle mr-1.5"></i>Simpan'; btn.classList.replace('bg-green-600','bg-blue-600'); btn.disabled=false; },2200);
        } else { alert('Error: '+(d.error||'Unknown error')); btn.innerHTML='<i class="fas fa-check-circle mr-1.5"></i>Save'; btn.disabled=false; }
    })
    .catch(()=>{ alert('Failed to save.'); btn.innerHTML='Save'; btn.disabled=false; });
}
function resetLayout(){
    if(isLocked){alert('This layout is locked.');return;}
    if(!confirm('Reset to default? Unsaved changes will be lost.'))return;
    fetch(ROUTES.reset,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF}})
    .then(r=>r.json())
    .then(d=>{
        if(d.layout){
            const lj=d.layout.layout_json;
            currentLayout=typeof lj==='string'?JSON.parse(lj):lj;
            activeLayoutId=d.layout.id;
            renderCanvas();renderElementsList();renderSignerAssets();
        }
    });
}
function openPreview(){ window.open(ROUTES.preview,'_blank'); }

// ════ PUBLISH & REVERT ════════════════════════════
function openRevertModal(){
    document.getElementById('revertModal').classList.remove('hidden');
}
function closeRevertModal(){
    document.getElementById('revertModal').classList.add('hidden');
}

function confirmPublish(){
    if(isLocked){alert('This layout is locked.');return;}
    if(!activeLayoutId){
        alert('Please save the layout before publishing.');
        return;
    }
    document.getElementById('publishModal').classList.remove('hidden');
}
function closePublishModal(){
    document.getElementById('publishModal').classList.add('hidden');
}
function doPublish(){
    if(!activeLayoutId){alert('No active layout to publish.');return;}
    const url=ROUTES.publish||(ROUTES.uploadBase+activeLayoutId+'/publish');
    const btn=document.getElementById('btnDoPublish');
    btn.disabled=true;
    btn.innerHTML='<i class="fas fa-spinner fa-spin mr-1"></i>Processing...';
    fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'},body:'{}'})
    .then(r=>r.json())
    .then(d=>{
        if(d.success||d.status==='published'){
            isLocked=true;
            const modalBox = document.querySelector('#publishModal .bg-white');
            if (modalBox) {
                modalBox.innerHTML = `
                    <div class="p-8 text-center bg-white flex flex-col items-center justify-center min-h-[250px]">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-check text-2xl text-emerald-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Successfully Published!</h3>
                        <p class="text-[13px] text-gray-500">Reloading page...</p>
                    </div>
                `;
            }
            setTimeout(() => window.location.reload(), 1200);
        } else {
            closePublishModal();
            alert('Failed to publish: '+(d.error||d.message||'Unknown error'));
            btn.disabled=false;
            btn.innerHTML='<i class="fas fa-lock mr-1"></i>Yes, Publish Now';
        }
    })
    .catch(err=>{
        closePublishModal();
        alert('Connection failed while publishing. Please try again.');
        btn.disabled=false;
        btn.innerHTML='<i class="fas fa-lock mr-1"></i>Yes, Publish Now';
    });
}

// ════ ASSET UPLOADS ═══════════════════════════════════
function previewAsset(fileInputId, previewId, placeholderId, canvasElementId = null){
    const fi=document.getElementById(fileInputId);
    if(!fi||!fi.files[0])return;
    const url=URL.createObjectURL(fi.files[0]);
    const prev=document.getElementById(previewId);
    const ph=document.getElementById(placeholderId);
    if(prev){prev.src=url;prev.classList.remove('hidden');}
    if(ph) ph.classList.add('hidden');
    
    if(canvasElementId) {
        const canvasEl=document.getElementById(canvasElementId);
        if(canvasEl){canvasEl.src=url;canvasEl.classList.remove('hidden');}
    }
}
const ASSET_FILE={background:'bgFile',event_logo:'evLogoFile',org_logo:'orgLogoFile'};
function doUploadAsset(type){
    if(isLocked){alert('This layout is locked.');return;}
    const fi=document.getElementById(ASSET_FILE[type]);
    if(!fi?.files[0]){fi?.click();return;}
    if(!activeLayoutId){alert('Please save the layout before uploading assets.');return;}
    const fd=new FormData();
    fd.append('type',type);fd.append('file',fi.files[0]);
    fetch(ROUTES.uploadBase+activeLayoutId+'/upload-asset',{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd})
    .then(r=>r.json())
    .then(d=>{
        if(d.success){
            // Use d.url from server — it already returns /media/... (Cloudflare-safe)
            const relativeUrl = d.url;
            if(type==='background'){
                const bg=document.getElementById('canvasBgImg');
                if(bg){bg.src=relativeUrl;bg.classList.remove('hidden');}
                // Also update the Aset tab preview
                const prev=document.getElementById('bgPreview');
                const ph=document.getElementById('bgPh');
                if(prev){prev.src=relativeUrl;prev.classList.remove('hidden');}
                if(ph) ph.classList.add('hidden');
            } else if(type==='event_logo'){
                globalEventLogoPath = d.path;
                const prev=document.getElementById('evLogoPreview');
                const ph=document.getElementById('evLogoPh');
                if(prev){prev.src=relativeUrl;prev.classList.remove('hidden');}
                if(ph) ph.classList.add('hidden');
                renderCanvas();
            } else if(type==='org_logo'){
                globalOrgLogoPath = d.path;
                const prev=document.getElementById('orgLogoPreview');
                const ph=document.getElementById('orgLogoPh');
                if(prev){prev.src=relativeUrl;prev.classList.remove('hidden');}
                if(ph) ph.classList.add('hidden');
                renderCanvas();
            }
            // Show brief success in save status bar
            const stat=document.getElementById('saveStatus');
            if(stat){stat.textContent='✅ Upload success';setTimeout(()=>stat.textContent='',2500);}
        } else alert('Error: '+(d.error||'Upload failed'));
    })
    .catch(()=>alert('Upload failed. Please try again.'));
}
function uploadSigImg(elementId,input){
    if(isLocked){alert('Layout sudah terkunci.');return;}
    const file=input.files[0];if(!file)return;
    if(!activeLayoutId){alert('Simpan layout dulu sebelum upload foto tanda tangan.');return;}
    const fd=new FormData();fd.append('element_id',elementId);fd.append('file',file);
    fetch(ROUTES.uploadBase+activeLayoutId+'/upload-signature',{method:'POST',headers:{'X-CSRF-TOKEN':CSRF},body:fd})
    .then(r=>r.json())
    .then(d=>{
        if(d.success){
            const el=(currentLayout?.elements||[]).find(e=>e.id===elementId);
            if(el){el.signatureImagePath=d.path;refreshCanvasEl(elementId);renderSignerAssets();}
            alert('✅ Foto tanda tangan berhasil diupload!');
        } else alert('Error: '+(d.error||'Upload gagal'));
    })
    .catch(()=>alert('Gagal upload.'));
}

// ════ UTILS ═══════════════════════════════════════════
function escH(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}

document.getElementById('certCanvas').addEventListener('click',function(e){
    if(e.target===this||e.target.id==='elementsContainer'){
        document.querySelectorAll('.cert-element').forEach(el=>el.classList.remove('selected'));
        selectedElId=null;
        document.getElementById('propertiesContent').innerHTML=
            '<div class="text-center py-8 text-gray-300"><div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fas fa-mouse-pointer text-2xl text-gray-300"></i></div><p class="text-sm font-medium text-gray-400">Belum ada elemen dipilih</p><p class="text-xs text-gray-300 mt-1">Klik elemen di canvas untuk mengedit</p></div>';
    }
});
@endverbatim
</script>

<style>
.prop-label{display:block;font-size:11px;font-weight:700;color:#4b5563;margin-bottom:4px;}
.prop-input{width:100%;padding:6px 8px;font-size:12px;border:1px solid #d1d5db;border-radius:8px;outline:none;}
.prop-input:focus{box-shadow:0 0 0 2px rgba(59,130,246,.3);}
</style>

@endsection
