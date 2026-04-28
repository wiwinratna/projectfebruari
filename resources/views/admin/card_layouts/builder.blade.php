@extends('layouts.app')

@section('content')
<div class="h-screen flex flex-col bg-gray-900">
    <!-- Header -->
    <div class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Card Builder</h1>
            <p class="text-sm text-gray-400">{{ $event->title }}</p>
        </div>
        <div class="flex gap-3">
            <button
                type="button"
                onclick="resetLayout()"
                class="px-4 py-2 bg-gray-700 text-gray-200 rounded hover:bg-gray-600 transition text-sm"
            >
                Reset Default
            </button>
            <button
                type="button"
                onclick="saveLayout()"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm"
            >
                Save Layout
            </button>
            <a
                href="{{ route('admin.card-layouts.preview-sample') }}"
                target="_blank"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm"
            >
                Preview Sample
            </a>
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <!-- Canvas Area -->
        <div class="flex-1 flex flex-col items-center justify-center bg-gray-900 p-8 overflow-auto">
            <div class="relative" id="canvas-container" style="width: 148mm; height: 210mm;">
                <!-- Background Template (if available) -->
                @if ($event->card_template_path)
                    <img
                        id="template-bg"
                        src="{{ asset('storage/' . ltrim($event->card_template_path, '/')) }}"
                        alt="Template"
                        class="absolute inset-0 w-full h-full object-cover rounded border border-gray-600"
                    />
                @else
                    <div class="absolute inset-0 bg-white rounded border-2 border-gray-600"></div>
                @endif

                <!-- Content Area (Visual Guide) -->
                <div
                    id="content-area"
                    class="absolute border-2 border-dashed border-yellow-400 opacity-50"
                    style="left: 10mm; top: 10mm; width: 130mm; height: 190mm;"
                >
                </div>

                <!-- Draggable Elements Container -->
                <div id="elements-container" class="absolute inset-0 rounded"></div>
            </div>

            <!-- Canvas Controls -->
            <div class="mt-6 flex gap-4 text-gray-400 text-sm">
                <label class="flex items-center gap-2 cursor-pointer hover:text-white">
                    <input type="checkbox" id="snap-grid" checked />
                    Grid Snap (10mm)
                </label>
                <label class="flex items-center gap-2 cursor-pointer hover:text-white">
                    <input type="checkbox" id="show-guides" checked />
                    Show Guides
                </label>
            </div>
        </div>

        <!-- Sidebar Elements -->
        <div class="w-80 bg-gray-800 border-l border-gray-700 p-6 overflow-y-auto">
            <h2 class="text-lg font-semibold text-white mb-4">Elements</h2>

            <div class="space-y-3" id="elements-list">
                <!-- Will be populated by JavaScript -->
            </div>

            <div class="mt-8 pt-6 border-t border-gray-700">
                <h3 class="text-sm font-semibold text-gray-300 mb-3">Layout Info</h3>
                <div id="layout-info" class="text-xs text-gray-400 space-y-1">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden input untuk store layout JSON -->
<input type="hidden" id="layout-json-input" />

<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>

<script>
const GRID_SIZE = 10; // mm
const PX_PER_MM = 3.779; // Standard DPI: 96px / 25.4mm
const MM_PER_PX = 1 / PX_PER_MM;

let currentLayout = null;
let selectedElement = null;

// ============ INIT ============
document.addEventListener('DOMContentLoaded', function() {
    loadLayout();
    renderElements();
    setupInteract();
    updateLayoutInfo();
});

// ============ LOAD LAYOUT ============
function loadLayout() {
    fetch('{{ route("admin.card-layouts.active") }}')
        .then(response => response.json())
        .then(data => {
            currentLayout = data.layout;
            renderElements();
            updateLayoutInfo();
        })
        .catch(error => console.error('Error loading layout:', error));
}

// ============ RENDER ELEMENTS ============
function renderElements() {
    if (!currentLayout) return;

    const container = document.getElementById('elements-container');
    const sidebar = document.getElementById('elements-list');

    container.innerHTML = '';
    sidebar.innerHTML = '';

    currentLayout.elements.forEach((element, index) => {
        if (!element.visible) return;

        // Create draggable element in canvas
        const el = document.createElement('div');
        el.dataset.elementId = element.id;
        el.dataset.index = index;
        el.className = 'absolute cursor-move border-2 border-blue-500 bg-blue-200 bg-opacity-30 rounded p-2 text-xs font-semibold text-white select-none touch-none';
        
        const rect = element.rect;
        el.style.left = `${rect.xMm}mm`;
        el.style.top = `${rect.yMm}mm`;
        el.style.width = `${rect.wMm}mm`;
        el.style.height = `${rect.hMm}mm`;
        
        el.textContent = element.label || element.id;
        
        el.addEventListener('click', () => selectElement(element, el));
        el.addEventListener('touchstart', () => selectElement(element, el));

        container.appendChild(el);

        // Create sidebar item
        const item = document.createElement('div');
        item.className = 'p-3 bg-gray-700 rounded cursor-pointer hover:bg-gray-600 transition';
        item.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <label class="flex items-center gap-2 font-medium text-white text-sm flex-1">
                    <input
                        type="checkbox"
                        ${element.visible ? 'checked' : ''}
                        onchange="toggleElement(${index})"
                        class="form-checkbox"
                    />
                    ${element.label || element.id}
                </label>
            </div>
            <div class="text-xs text-gray-400 space-y-1">
                <div>Type: ${element.type}</div>
                <div>Position: ${rect.xMm.toFixed(1)}mm, ${rect.yMm.toFixed(1)}mm</div>
                <div>Size: ${rect.wMm.toFixed(1)}mm × ${rect.hMm.toFixed(1)}mm</div>
            </div>
        `;
        
        item.addEventListener('click', () => selectElement(element, el));
        sidebar.appendChild(item);
    });
}

// ============ SELECT ELEMENT ============
function selectElement(element, domElement) {
    // Remove previous selection
    document.querySelectorAll('#elements-container > div').forEach(el => {
        el.classList.remove('border-green-500');
        el.classList.add('border-blue-500');
    });

    // Select new
    domElement.classList.remove('border-blue-500');
    domElement.classList.add('border-green-500');
    selectedElement = { element, domElement };
}

// ============ TOGGLE ELEMENT ============
function toggleElement(index) {
    currentLayout.elements[index].visible = !currentLayout.elements[index].visible;
    renderElements();
}

// ============ SETUP INTERACT.JS ============
function setupInteract() {
    interact('#elements-container').on('tap', function(event) {
        // Handle clicks
    });

    // Delegated drag for all elements inside container
    interact('#elements-container').resizable({
        // Allow from any edge/corner
        edges: {
            left: true,
            right: true,
            bottom: true,
            top: true,
        },
        listeners: {
            move(event) {
                let rect = event.target.getBoundingClientRect();
                let containerRect = document.getElementById('elements-container').getBoundingClientRect();

                let x = event.pageX - containerRect.left;
                let y = event.pageY - containerRect.top;

                let xMm = Math.round(x * MM_PER_MM / GRID_SIZE) * GRID_SIZE;
                let yMm = Math.round(y * MM_PER_MM / GRID_SIZE) * GRID_SIZE;
                let wMm = Math.round(event.rect.width * MM_PER_MM / GRID_SIZE) * GRID_SIZE;
                let hMm = Math.round(event.rect.height * MM_PER_MM / GRID_SIZE) * GRID_SIZE;

                event.target.style.left = `${xMm}mm`;
                event.target.style.top = `${yMm}mm`;
                event.target.style.width = `${wMm}mm`;
                event.target.style.height = `${hMm}mm`;

                // Update layout
                const index = event.target.dataset.index;
                currentLayout.elements[index].rect = { xMm, yMm, wMm, hMm };
            }
        },
        modifiers: [
            interact.modifiers.restrictSize({
                min: {
                    width: 10,
                    height: 10,
                },
            }),
        ],
    });

    // Drag
    interact('#elements-container > div').draggable({
        inertia: false,
        modifiers: [
            interact.modifiers.restrictRect({
                restriction: 'parent',
            }),
        ],
        listeners: {
            move(event) {
                let target = event.target;
                let x = parseFloat(target.getAttribute('data-x')) || 0;
                let y = parseFloat(target.getAttribute('data-y')) || 0;

                x += event.deltaX;
                y += event.deltaY;

                const snapGrid = document.getElementById('snap-grid').checked;
                if (snapGrid) {
                    x = Math.round(x / (GRID_SIZE * PX_PER_MM)) * (GRID_SIZE * PX_PER_MM);
                    y = Math.round(y / (GRID_SIZE * PX_PER_MM)) * (GRID_SIZE * PX_PER_MM);
                }

                target.style.transform = `translate(${x}px, ${y}px)`;
                target.setAttribute('data-x', x);
                target.setAttribute('data-y', y);

                // Update layout
                const xMm = (parseFloat(target.style.left) || 0) + x * MM_PER_MM;
                const yMm = (parseFloat(target.style.top) || 0) + y * MM_PER_MM;
                
                const index = target.dataset.index;
                currentLayout.elements[index].rect.xMm = Math.round(xMm);
                currentLayout.elements[index].rect.yMm = Math.round(yMm);
            },
        },
    });
}

// ============ SAVE LAYOUT ============
function saveLayout() {
    if (!currentLayout) {
        alert('Layout belum dimuat');
        return;
    }

    const layoutJson = JSON.stringify(currentLayout);
    
    fetch('{{ route("admin.card-layouts.save") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            layout_json: layoutJson,
            name: `Layout ${new Date().toLocaleDateString('id-ID')}`,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.layout) {
            alert('Layout berhasil disimpan!');
            loadLayout();
        } else if (data.error) {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal menyimpan layout');
    });
}

// ============ RESET DEFAULT ============
function resetLayout() {
    if (!confirm('Yakin ingin me-reset layout ke default? Data layout sebelumnya akan diganti.')) {
        return;
    }

    fetch('{{ route("admin.card-layouts.reset-default") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.layout) {
            alert('Layout berhasil di-reset!');
            loadLayout();
        } else if (data.error) {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal me-reset layout');
    });
}

// ============ UPDATE LAYOUT INFO ============
function updateLayoutInfo() {
    if (!currentLayout) return;

    const info = document.getElementById('layout-info');
    info.innerHTML = `
        <div>Schema: v${currentLayout.schemaVersion}</div>
        <div>Elements: ${currentLayout.elements.length}</div>
        <div>Visible: ${currentLayout.elements.filter(e => e.visible).length}</div>
        <div class="mt-2 pt-2 border-t border-gray-600">
            <div>Content Area:</div>
            <div>${currentLayout.contentArea.xMm}mm, ${currentLayout.contentArea.yMm}mm</div>
            <div>${currentLayout.contentArea.wMm}mm × ${currentLayout.contentArea.hMm}mm</div>
        </div>
    `;
}

// Show/hide guides
document.addEventListener('change', function(e) {
    if (e.target.id === 'show-guides') {
        const guide = document.getElementById('content-area');
        guide.style.display = e.target.checked ? 'block' : 'none';
    }
});
</script>

<style>
#canvas-container {
    display: block;
    background: white;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
}

#elements-container > div {
    transition: border-color 0.2s;
}

#elements-container > div.dragging {
    opacity: 0.8;
    z-index: 100;
}
</style>
@endsection
