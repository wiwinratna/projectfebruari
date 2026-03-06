@extends('layouts.app')

@section('title', 'Event Settings')
@section('page-title', 'Event Settings')

@section('content')
@php
    $logoPathRaw = $event->logo_path;
    $logoFilename = $logoPathRaw ? basename($logoPathRaw) : '';
    $logoExists = $logoPathRaw ? Storage::disk('public')->exists($logoPathRaw) : false;
    $logoPublicUrl = $logoPathRaw ? asset('storage/' . $logoPathRaw) : null;
    $templatePathRaw = $event->card_template_path;
    $templateFilename = $templatePathRaw ? basename($templatePathRaw) : '';
    $templateExists = $templatePathRaw ? Storage::disk('public')->exists($templatePathRaw) : false;
    $templatePublicUrl = $templatePathRaw ? asset('storage/' . $templatePathRaw) : null;
@endphp

<div class="container mx-auto max-w-screen-xl px-4 lg:px-6 py-6 space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h2>
        <form method="POST" action="{{ route('admin.event.settings.update') }}" class="space-y-3">
            @csrf
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Event Title</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $event->title) }}"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @error('title') border-red-500 @enderror"
                    placeholder="Event name"
                >
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                <i class="fas fa-save mr-1"></i> Save Event Info
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Event Logo</h2>
            <form method="POST" action="{{ route('admin.event.settings.update') }}" enctype="multipart/form-data" class="space-y-3 flex flex-col flex-1">
                @csrf
                <input type="file" id="logo" name="logo" accept="image/*" class="hidden @error('logo') border border-red-500 @enderror">
                <div id="logoDropzone" class="group relative h-122 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/70 hover:bg-blue-50/40 hover:border-blue-400 transition-all cursor-pointer flex flex-col items-center justify-center p-4">
                    <div id="logoPlaceholder" class="{{ ($logoExists || ($logoPathRaw && !$logoExists)) ? 'hidden' : 'text-center' }}">
                        <div class="w-12 h-12 rounded-xl bg-white border border-gray-200 mx-auto flex items-center justify-center text-gray-500 mb-3">
                            <i class="fas fa-cloud-upload-alt text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-700">Upload here</p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG, WebP up to 5MB</p>
                    </div>
                    <div id="logoPreviewWrap" class="{{ ($logoExists || ($logoPathRaw && !$logoExists)) ? '' : 'hidden' }} w-full h-full">
                        @if ($logoExists && $logoPublicUrl)
                            <img id="logoPreviewImage" src="{{ $logoPublicUrl }}" alt="Event Logo" class="w-full h-full object-contain rounded-lg border border-gray-200 bg-gray-50">
                        @else
                            <img id="logoPreviewImage" src="" alt="Event Logo Preview" class="hidden w-full h-full object-contain rounded-lg border border-gray-200 bg-gray-50">
                        @endif
                        <div id="logoMissingState" class="{{ ($logoPathRaw && !$logoExists) ? '' : 'hidden' }} w-full h-full rounded-lg border border-amber-200 bg-amber-50 text-amber-700 text-sm font-medium flex items-center justify-center">
                            Logo file missing
                        </div>
                    </div>
                </div>
                <div id="logoMeta" class="min-h-[1.25rem] mt-1">
                    <div id="logoFilename" class="max-w-full truncate text-xs text-gray-500" title="{{ $logoFilename }}">{{ $logoFilename }}</div>
                </div>
                <p class="text-xs text-gray-500">Click the box to choose a logo file.</p>
                @error('logo')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
                <div class="mt-auto pt-1">
                    <div class="flex flex-nowrap items-center gap-3">
                        <button type="submit" class="inline-flex items-center justify-center h-10 px-4 whitespace-nowrap bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                            Upload Logo
                        </button>
                        @if ($logoPathRaw)
                            <button type="button" id="btnRemoveLogo" class="inline-flex items-center justify-center h-10 px-4 whitespace-nowrap rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors text-sm font-semibold" onclick="removeLogo()">
                                <i class="fas fa-trash mr-1"></i> Remove Logo
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Card Template Background</h2>
            <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-lg px-3 py-2 text-xs mb-3 space-y-1">
                <div>Optional: Upload a background template if you want custom event branding.</div>
                <div>If you upload: background template will appear behind card elements and you can customize layout.</div>
                <div>If you don’t upload, we automatically use the default design (Mode 1).</div>
            </div>
            @if(config('app.debug'))
                <details class="mb-3 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2">
                    <summary class="cursor-pointer text-xs font-semibold text-gray-700">Debug</summary>
                    <div class="mt-2 text-[11px] text-gray-600 space-y-1">
                        <div>Template path raw: <span class="font-mono">{{ $templatePathRaw ?? 'NULL' }}</span></div>
                        <div>Exists on public disk: <span class="font-mono">{{ $templatePathRaw ? ($templateExists ? 'true' : 'false') : 'false' }}</span></div>
                        <div>Public URL: <span class="font-mono break-all">{{ $templatePublicUrl ?? 'N/A' }}</span></div>
                    </div>
                </details>
            @endif

            <form method="POST" action="{{ route('admin.event.settings.update') }}" enctype="multipart/form-data" class="space-y-3 flex flex-col flex-1">
                @csrf
                <input type="file" id="card_template" name="card_template" accept="image/*" class="hidden @error('card_template') border border-red-500 @enderror">
                <div id="templateDropzone" class="group relative h-96 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/70 hover:bg-green-50/40 hover:border-green-400 transition-all cursor-pointer flex flex-col items-center justify-center p-4">
                    <div id="templatePlaceholder" class="{{ ($templateExists || ($templatePathRaw && !$templateExists)) ? 'hidden' : 'text-center' }}">
                        <div class="w-12 h-12 rounded-xl bg-white border border-gray-200 mx-auto flex items-center justify-center text-gray-500 mb-3">
                            <i class="fas fa-file-image text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-700">Upload here</p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG, WebP up to 10MB</p>
                    </div>
                    <div id="templatePreviewWrap" class="{{ ($templateExists || ($templatePathRaw && !$templateExists)) ? '' : 'hidden' }} w-full h-full">
                        @if ($templateExists && $templatePublicUrl)
                            <img id="templatePreviewImage" src="{{ $templatePublicUrl }}" alt="Card Template" class="w-full h-full object-contain rounded-lg border border-gray-200 bg-white">
                        @else
                            <img id="templatePreviewImage" src="" alt="Template Preview" class="hidden w-full h-full object-contain rounded-lg border border-gray-200 bg-white">
                        @endif
                        <div id="templateMissingState" class="{{ ($templatePathRaw && !$templateExists) ? '' : 'hidden' }} w-full h-full rounded-lg border border-amber-200 bg-amber-50 text-amber-700 text-sm font-medium flex items-center justify-center">
                            Template file missing
                        </div>
                    </div>
                </div>
                <div id="templateMeta" class="mt-2">
                    <div id="templateFilename" class="max-w-full truncate text-xs text-gray-500" title="{{ $templateFilename }}">{{ $templateFilename }}</div>
                </div>
                <p class="text-xs text-gray-500">Click the box to choose a template file.</p>
                @error('card_template')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
                @if(!$templateExists)
                <div class="mt-3 bg-blue-50 border border-blue-200 text-blue-800 px-3 py-2 rounded-lg text-sm">
                    Template is optional. If you don’t upload, we automatically use the default design
                </div>
                @endif
                <div class="mt-auto pt-1">
                    <div class="flex flex-nowrap items-center gap-3">
                        <button type="submit" class="inline-flex items-center justify-center h-10 px-4 whitespace-nowrap bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                            Upload Template
                        </button>
                        @if ($templatePathRaw)
                            <button type="button" id="btnRemoveTemplate" class="inline-flex items-center justify-center h-10 px-4 whitespace-nowrap rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors text-sm font-semibold" onclick="removeTemplate()">
                                <i class="fas fa-trash mr-1"></i> Remove Template
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Card Layout Editor</h2>
        @if(!$templateExists)
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg text-sm font-medium mb-3">
                <i class="fas fa-info-circle mr-2"></i> Editing positions uses the default built-in card design without custom template background.
            </div>
        @endif
        <div class="flex flex-col lg:flex-row gap-6 items-start overflow-visible">
            <div class="flex flex-col gap-2 w-32 shrink-0 relative z-20">
                <button type="button" id="btnToggleGuides" onclick="toggleGuides()" class="px-3 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition-colors text-sm font-medium w-full"><i class="fas fa-border-all mr-1"></i> Guides</button>
                <button type="button" id="btnResetLayout" onclick="resetLayout()" class="px-3 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition-colors text-sm font-medium w-full"><i class="fas fa-undo mr-1"></i> Reset</button>
                <button type="button" id="btnPreviewSample" onclick="previewSample()" class="px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors text-sm font-medium w-full" style="display:block !important; visibility:visible !important; opacity:1 !important; background-color:#4f46e5; color:#ffffff; min-height:2.5rem;"><i class="fas fa-eye mr-1"></i> Preview</button>
                <button type="button" id="btnSaveLayout" onclick="saveLayout()" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors text-sm font-medium w-full"><i class="fas fa-check-circle mr-1"></i> Save</button>
            </div>

            <div class="flex-1 grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_20rem] gap-6 items-start">
                <div class="flex items-center justify-center">
                    <div class="bg-gray-50 rounded-lg overflow-auto p-4 border border-gray-200 w-full" style="max-height: 860px;">
                        <div id="cardCanvas" class="relative bg-white shadow-md" style="width: 148mm; height: 210mm;">
                            @if($templateExists && $templatePublicUrl)
                                <img src="{{ $templatePublicUrl }}" alt="Template Background" class="absolute inset-0 w-full h-full object-cover rounded pointer-events-none" style="z-index: 1;">
                            @endif
                            <div id="cardGuides" class="absolute inset-0 pointer-events-none" style="z-index: 2; display: none;">
                                <div class="w-full h-full border border-dashed border-gray-300" style="opacity: 0.3;"></div>
                            </div>
                            <div id="elementsContainer" class="absolute inset-0" style="z-index: 10;"></div>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <div class="bg-gray-50 rounded-lg p-4 space-y-4 border border-gray-200" style="max-height: 860px; overflow-y: auto;">
                        <h3 class="font-semibold text-gray-900">Elements</h3>
                        <div id="elementsList" class="space-y-2"></div>
                        <div id="elementProperties" class="border-t border-gray-200 pt-3">
                            <h4 class="font-semibold text-gray-900 text-sm mb-2">Properties</h4>
                            <div id="propertiesContent" class="text-xs text-gray-500">Select an element to edit properties</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="previewModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gray-900 text-white p-4 flex items-center justify-between">
            <h3 class="font-bold text-lg">Card Preview</h3>
            <button onclick="closePreview()" class="text-gray-300 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="previewContent" class="p-6"></div>
    </div>
</div>

<style>
    .card-element {
        position: absolute;
        border: 2px dashed rgba(59, 130, 246, 0.5);
        cursor: move;
        user-select: none;
        transition: border-color 0.2s;
        overflow: hidden;
        background: rgba(255,255,255,0.01);
    }
    .card-element.selected {
        border: 2px solid rgb(59, 130, 246);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    .card-element .resize-handle {
        position: absolute;
        width: 10px;
        height: 10px;
        background: rgb(59, 130, 246);
        border: 1px solid white;
        cursor: se-resize;
        bottom: -5px;
        right: -5px;
        z-index: 20;
    }
    .card-element .clipped-content {
        width: 100%;
        height: 100%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #cardCanvas {
        touch-action: none;
    }
    .truncation-badge {
        position: absolute;
        top: -18px;
        right: 0;
        background: #f59e0b;
        color: #111827;
        border: 1px solid #d97706;
        border-radius: 4px;
        padding: 1px 5px;
        font-size: 10px;
        font-weight: 700;
        z-index: 30;
        pointer-events: none;
    }
    #btnPreviewSample {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative;
        z-index: 30;
    }
</style>

<script>
    let currentLayout = null;
    let selectedElement = null;
    const px2mm = 0.264583;
    const editorEnabled = true;

    document.addEventListener('DOMContentLoaded', function() {
        initUploadDropzones();
        if (!editorEnabled) return;
        loadLayout();
    });

    function initUploadDropzones() {
        bindDropzone({
            dropzoneId: 'logoDropzone',
            inputId: 'logo',
            previewWrapId: 'logoPreviewWrap',
            previewImageId: 'logoPreviewImage',
            placeholderId: 'logoPlaceholder',
            filenameId: 'logoFilename',
            missingStateId: 'logoMissingState'
        });

        bindDropzone({
            dropzoneId: 'templateDropzone',
            inputId: 'card_template',
            previewWrapId: 'templatePreviewWrap',
            previewImageId: 'templatePreviewImage',
            placeholderId: 'templatePlaceholder',
            filenameId: 'templateFilename',
            missingStateId: 'templateMissingState'
        });
    }

    function bindDropzone({ dropzoneId, inputId, previewWrapId, previewImageId, placeholderId, filenameId, missingStateId }) {
        const dropzone = document.getElementById(dropzoneId);
        const input = document.getElementById(inputId);
        const previewWrap = document.getElementById(previewWrapId);
        const previewImage = document.getElementById(previewImageId);
        const placeholder = document.getElementById(placeholderId);
        const filename = document.getElementById(filenameId);
        const missingState = document.getElementById(missingStateId);

        if (!dropzone || !input) return;

        dropzone.addEventListener('click', () => input.click());

        input.addEventListener('change', () => {
            const file = input.files && input.files[0] ? input.files[0] : null;
            if (!file) return;

            const objectUrl = URL.createObjectURL(file);
            if (previewImage) {
                previewImage.src = objectUrl;
                previewImage.classList.remove('hidden');
            }
            if (previewWrap) previewWrap.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
            if (filename) filename.textContent = file.name;
            if (filename) filename.title = file.name;
            if (missingState) missingState.classList.add('hidden');
        });
    }

    function loadLayout() {
        fetch('{{ route("admin.card-layouts.active") }}')
            .then(r => r.json())
            .then(data => {
                console.log('[CardLayout] active layout id:', data.id ?? null);
                console.log('[CardLayout] legacy conversion happened:', !!data.converted_legacy);
                currentLayout = data.layout ? data.layout : createDefaultLayout();
                renderCanvas();
            })
            .catch(() => {
                currentLayout = createDefaultLayout();
                renderCanvas();
            });
    }

    function createDefaultLayout() {
        return {
            schemaVersion: "1.0.0",
            contentArea: { xMm: 0, yMm: 0, wMm: 148, hMm: 210 },
            elements: [
                { id: "photo", type: "photo", visible: true, rect: { xMm: 20, yMm: 20, wMm: 40, hMm: 50 }, style: {} },
                { id: "qr", type: "qr", visible: true, rect: { xMm: 75, yMm: 20, wMm: 40, hMm: 40 }, style: {} },
                { id: "text-name", type: "text-name", visible: true, rect: { xMm: 20, yMm: 75, wMm: 95, hMm: 10 }, style: { fontSizePt: 16, fontWeight: "bold", align: "left" } },
                { id: "text-job", type: "text-job", visible: true, rect: { xMm: 20, yMm: 87, wMm: 95, hMm: 8 }, style: { fontSizePt: 12, fontWeight: "normal", align: "left" } },
                { id: "text-accreditation", type: "text-accreditation", visible: true, rect: { xMm: 20, yMm: 97, wMm: 95, hMm: 6 }, style: { fontSizePt: 10, fontWeight: "normal", align: "left" } },
                { id: "group-badges", type: "group-badges", visible: true, rect: { xMm: 20, yMm: 105, wMm: 95, hMm: 30 }, style: { lineClamp: 3 } },
                { id: "group-chips", type: "group-chips", visible: true, rect: { xMm: 20, yMm: 137, wMm: 95, hMm: 60 }, style: { lineClamp: 5 } }
            ]
        };
    }

    function renderCanvas() {
        if (!currentLayout || !editorEnabled) return;

        const container = document.getElementById('elementsContainer');
        if (!container) return;
        container.innerHTML = '';

        currentLayout.elements.forEach(element => {
            if (!element.visible) return;
            const el = createCanvasElement(element);
            container.appendChild(el);
        });

        updateTruncationWarnings();
    }

    function createCanvasElement(data) {
        const el = document.createElement('div');
        el.className = 'card-element';
        el.id = 'elem-' + data.id;
        el.dataset.elementId = data.id;
        el.style.left = data.rect.xMm + 'mm';
        el.style.top = data.rect.yMm + 'mm';
        el.style.width = data.rect.wMm + 'mm';
        el.style.height = data.rect.hMm + 'mm';
        el.dataset.elementType = data.type;

        let content = '';
        switch(data.type) {
            case 'photo':
                content = '<i class="fas fa-image text-blue-400 text-2xl"></i>';
                break;
            case 'qr':
                content = '<i class="fas fa-qrcode text-green-400 text-2xl"></i>';
                break;
            case 'text-name':
                content = `<span class="trunc-check text-xs text-gray-600" style="font-size:${data.style.fontSizePt||16}px;font-weight:${data.style.fontWeight||'bold'};text-align:${data.style.align||'left'};width:100%;display:-webkit-box;overflow:hidden;text-overflow:ellipsis;-webkit-line-clamp:2;-webkit-box-orient:vertical;line-height:1.2;">Sample Very Long Participant Name For Overflow Check</span>`;
                break;
            case 'text-job':
                content = `<span class="trunc-check text-xs text-gray-600" style="font-size:${data.style.fontSizePt||12}px;font-weight:${data.style.fontWeight||'normal'};text-align:${data.style.align||'left'};width:100%;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;line-height:1.2;">Very Long Job Title For Overflow Check</span>`;
                break;
            case 'text-accreditation':
                content = `<span class="trunc-check text-xs text-gray-600" style="font-size:${data.style.fontSizePt||10}px;font-weight:${data.style.fontWeight||'normal'};text-align:${data.style.align||'left'};width:100%;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;line-height:1.2;">Accreditation Label Preview</span>`;
                break;
            case 'group-badges':
                content = `<div class='clipped-content' style='overflow:hidden;max-height:100%;width:100%;height:100%;'><span class="text-xs text-gray-600">Badges</span></div>`;
                break;
            case 'group-chips':
                content = `<div class='clipped-content' style='overflow:hidden;max-height:100%;width:100%;height:100%;'><span class="text-xs text-gray-600">Chips/Zones</span></div>`;
                break;
        }

        el.innerHTML = `
            <div class="w-full h-full flex items-center justify-center">${content}</div>
            <div class="resize-handle"></div>
        `;

        el.addEventListener('click', (e) => {
            e.stopPropagation();
            selectElement(data.id);
        });

        if (typeof interact !== 'undefined') {
            interact(el).draggable({
                inertia: false,
                modifiers: [
                    interact.modifiers.restrictRect({
                        restriction: 'parent',
                        endOnly: false
                    })
                ],
                listeners: {
                    move(event) {
                        const style = window.getComputedStyle(event.target);
                        let left = parseFloat(style.left) || 0;
                        let top = parseFloat(style.top) || 0;
                        left += event.dx;
                        top += event.dy;
                        event.target.style.left = left + 'px';
                        event.target.style.top = top + 'px';
                        updateElementInLayout(data.id, {
                            xMm: left * px2mm,
                            yMm: top * px2mm
                        });
                    }
                }
            });
            interact(el).resizable({
                edges: { right: true, bottom: true },
                listeners: {
                    move(event) {
                        event.target.style.width = event.rect.width + 'px';
                        event.target.style.height = event.rect.height + 'px';
                        updateElementInLayout(data.id, {
                            wMm: event.rect.width * px2mm,
                            hMm: event.rect.height * px2mm
                        });
                        updateTruncationWarnings();
                    }
                },
                modifiers: [
                    interact.modifiers.restrictSize({
                        min: { width: 40, height: 40 }
                    })
                ]
            });
        }
        return el;
    }

    function selectElement(elementId) {
        document.querySelectorAll('.card-element.selected').forEach(el => {
            el.classList.remove('selected');
        });

        const el = document.getElementById('elem-' + elementId);
        if (el) {
            el.classList.add('selected');
        }

        selectedElement = elementId;
        renderElementProperties(elementId);
    }

    function renderElementProperties(elementId) {
        const element = currentLayout.elements.find(e => e.id === elementId);
        if (!element) return;

        let propertiesHtml = `<div class="space-y-3"><small class="text-gray-600">ID: ${element.id}</small>`;

        if (element.type.startsWith('text-') || element.type.startsWith('group-')) {
            propertiesHtml += `
                <div>
                    <label class="text-xs font-bold text-gray-700">Font Size (pt)</label>
                    <input type="number" min="6" max="48" value="${element.style.fontSizePt || 12}"
                        onchange="updateElementStyle('${elementId}', 'fontSizePt', this.value)"
                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded">
                </div>
            `;
        }

        if (element.type.startsWith('text-')) {
            propertiesHtml += `
                <div>
                    <label class="text-xs font-bold text-gray-700">Font Weight</label>
                    <select onchange="updateElementStyle('${elementId}', 'fontWeight', this.value)"
                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded">
                        <option value="normal" ${element.style.fontWeight === 'normal' ? 'selected' : ''}>Normal</option>
                        <option value="bold" ${element.style.fontWeight === 'bold' ? 'selected' : ''}>Bold</option>
                    </select>
                </div>
            `;
        }

        if (element.type.startsWith('text-')) {
            propertiesHtml += `
                <div>
                    <label class="text-xs font-bold text-gray-700">Align</label>
                    <div class="flex gap-1">
                        <button class="flex-1 px-2 py-1 text-xs border rounded ${element.style.align === 'left' ? 'bg-blue-100 border-blue-500' : 'border-gray-300'}"
                            onclick="updateElementStyle('${elementId}', 'align', 'left')">L</button>
                        <button class="flex-1 px-2 py-1 text-xs border rounded ${element.style.align === 'center' ? 'bg-blue-100 border-blue-500' : 'border-gray-300'}"
                            onclick="updateElementStyle('${elementId}', 'align', 'center')">C</button>
                        <button class="flex-1 px-2 py-1 text-xs border rounded ${element.style.align === 'right' ? 'bg-blue-100 border-blue-500' : 'border-gray-300'}"
                            onclick="updateElementStyle('${elementId}', 'align', 'right')">R</button>
                    </div>
                </div>
            `;
        }

        if (element.type.startsWith('group-')) {
            propertiesHtml += `
                <div>
                    <label class="text-xs font-bold text-gray-700">Lines Max</label>
                    <input type="number" min="1" max="10" value="${element.style.lineClamp || 3}"
                        onchange="updateElementStyle('${elementId}', 'lineClamp', this.value)"
                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded">
                </div>
            `;
        }

        propertiesHtml += '</div>';
        const target = document.getElementById('propertiesContent');
        if (target) target.innerHTML = propertiesHtml;
    }

    function updateElementStyle(elementId, key, value) {
        const element = currentLayout.elements.find(e => e.id === elementId);
        if (element) {
            element.style[key] = isNaN(value) ? value : parseFloat(value);
            renderCanvas();
            renderElementProperties(elementId);
            updateTruncationWarnings();
        }
    }

    function updateTruncationWarnings() {
        document.querySelectorAll('.card-element').forEach((element) => {
            element.querySelectorAll('.truncation-badge').forEach((badge) => badge.remove());

            const textNode = element.querySelector('.trunc-check');
            if (!textNode) return;

            const isTruncated =
                textNode.scrollHeight > textNode.clientHeight + 1 ||
                textNode.scrollWidth > textNode.clientWidth + 1;

            if (isTruncated) {
                const badge = document.createElement('div');
                badge.className = 'truncation-badge';
                badge.textContent = 'Truncated';
                badge.title = 'Text is cut off. Increase box size or reduce font.';
                element.appendChild(badge);
            }
        });
    }

    function updateElementInLayout(elementId, updates) {
        const element = currentLayout.elements.find(e => e.id === elementId);
        if (element) {
            Object.assign(element.rect, updates);
        }
    }

    function toggleGuides() {
        if (!editorEnabled) return;
        const guides = document.getElementById('cardGuides');
        guides.style.display = guides.style.display === 'none' ? 'block' : 'none';
        document.getElementById('btnToggleGuides').classList.toggle('bg-blue-600');
        document.getElementById('btnToggleGuides').classList.toggle('text-white');
        document.getElementById('btnToggleGuides').classList.toggle('bg-gray-200');
        document.getElementById('btnToggleGuides').classList.toggle('text-gray-800');
    }

    function resetLayout() {
        if (!editorEnabled) return;
        if (!confirm('Reset layout to default (Mode 1)? This cannot be undone.')) return;

        fetch('{{ route("admin.card-layouts.reset-default") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                loadLayout();
            } else {
                alert('Error resetting default layout');
            }
        })
        .catch(err => {
            alert('Error: ' + err.message);
        });
    }

    function saveLayout() {
        if (!editorEnabled) return;
        const layoutName = prompt('Enter layout name (optional):', 'Custom Layout');

        fetch('{{ route("admin.card-layouts.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                layout_json: JSON.stringify(currentLayout),
                name: layoutName || 'Custom Layout'
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                console.log('[CardLayout] saved layout id:', data.id ?? null);
                console.log('[CardLayout] save normalized legacy conversion:', !!data.converted_legacy);
                alert('Layout saved successfully!');
            } else {
                alert('Error saving layout: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            alert('Error: ' + err.message);
        });
    }

    function previewSample() {
        if (!editorEnabled) return;
        window.open('{{ route("admin.card-layouts.preview-sample") }}', '_blank');
    }

    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
    }

    function removeLogo() {
        if (confirm('Remove logo?')) {
            fetch('{{ route("admin.event.settings.logo.remove") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success || data.message) {
                    location.reload();
                }
            });
        }
    }

    function removeTemplate() {
        if (confirm('Remove template?')) {
            fetch('{{ route("admin.event.settings.template.remove") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success || data.message) {
                    location.reload();
                }
            });
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
@endsection
