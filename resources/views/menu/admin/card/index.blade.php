@extends('layouts.app')

@section('title', 'Cards Center')
@section('page-title')
    <div class="flex items-center gap-2">
        Cards Center
        <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">
            {{ session('admin_event_name', 'Event') }}
        </span>
    </div>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Flash message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header / Filter Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        {{-- Row 1: Title --}}
        {{-- Row 1: Title --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Approved Applicants</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Semua kandidat yang sudah diterima (approved) + kartu (draft/issued).
                </p>
            </div>
        </div>

        {{-- Row 2: Ultra Compact Toolbar (Search, Filter, Table Actions) --}}
        <div class="mt-4 flex flex-col xl:flex-row xl:items-center justify-between gap-3">
            
            {{-- LEFT: Search + Filter (GET Form) --}}
            <form class="flex-1" method="GET" action="{{ route('admin.cards.index') }}">
                <div class="flex items-center gap-2">
                    <input type="text" name="q" value="{{ $q }}" placeholder="Search..."
                           class="w-48 px-3 py-1.5 bg-white border border-gray-200 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 transition-all text-xs shadow-sm">
                    
                    <select name="card_status"
                            class="w-32 px-3 py-1.5 bg-white border border-gray-200 rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 transition-all text-xs shadow-sm">
                        <option value="">All Status</option>
                        <option value="draft" {{ $statusCard==='draft' ? 'selected' : '' }}>Draft</option>
                        <option value="issued" {{ $statusCard==='issued' ? 'selected' : '' }}>Issued</option>
                    </select>

                    <button class="px-3 py-1.5 rounded-lg bg-gray-900 text-white hover:bg-gray-800 font-bold text-xs transition-all shadow-sm">
                        Filter
                    </button>
                    <a href="{{ route('admin.cards.index') }}"
                       class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold text-xs transition-all shadow-sm">
                        Reset
                    </a>
                </div>
            </form>

            {{-- RIGHT: Actions --}}
            <div class="flex flex-wrap items-center justify-start xl:justify-end gap-2 w-full xl:w-auto mt-2 xl:mt-0">
                <button id="btnIssueSelected" type="button"
                        class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all shadow-sm">
                    <i class="fas fa-bolt mr-1"></i> Issue
                </button>
                <button id="btnPrintSelected" type="button"
                        class="px-3 py-1.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-bold transition-all shadow-sm">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
                <button id="btnDownloadSelected" type="button"
                        class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-sm">
                    <i class="fas fa-download mr-1"></i> Download
                </button>
                
                <div class="w-px h-4 bg-gray-300 mx-1 hidden sm:block"></div>
                
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" type="button"
                        class="px-3 py-1.5 rounded-lg bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 text-xs font-bold transition-all shadow-sm">
                    <i class="fas fa-file-excel mr-1"></i> Import
                </button>
                <a href="{{ route('admin.cards.previewAll', request()->query()) }}"
                   class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-800 text-xs font-bold transition-all shadow-sm">
                    <i class="fas fa-grid-2 mr-1"></i> Preview
                </a>
            </div>
        </div>
    </div>

    {{-- 1 FORM UNTUK SEMUA (issue batch & print batch) --}}
    <form id="cardsBatchForm" method="POST" action="{{ route('admin.cards.issueBatch') }}">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-center px-4 py-3 w-10">
                            <input type="checkbox" id="checkAll" class="rounded border-gray-300 mx-auto">
                            </th>
                            <th class="text-center px-4 py-3 font-bold">Applicant</th>
                            <th class="text-center px-4 py-3 font-bold">Opening</th>
                            <th class="text-center px-4 py-3 font-bold">Job Category</th>
                            <th class="text-center px-4 py-3 font-bold">Accreditation</th>
                            <th class="text-center px-4 py-3 font-bold">Card</th>
                            <th class="text-center px-4 py-3 font-bold whitespace-nowrap">Actions</th>
                        </tr>
                        </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($apps as $a)
                            @php
                                $card = $cardsByAppId->get($a->application_id);
                                $jobCat = $jobCategories->get($a->job_category_id);
                                $map = $mappingByJobCategory->get($a->job_category_id);
                                $mappingName = $map->nama_akreditasi ?? null; // contoh: "D"
                                $mappingColor = $map->warna ?? '#e5e7eb';
                            @endphp

                            <tr class="hover:bg-gray-50/70">
                                <td class="px-4 py-4 align-top">
                                    @if($card)
                                        <input type="checkbox"
                                               name="card_ids[]"
                                               value="{{ $card->id }}"
                                               class="rowCheck rounded border-gray-300"
                                               data-status="{{ $card->status }}">
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 align-top whitespace-nowrap">
                                    @php
                                        $photoPath =
                                            $a->profile_photo
                                            ?? $a->profile_photo_path
                                            ?? $a->photo
                                            ?? $a->applicant_photo
                                            ?? null;

                                        $photoUrl = $photoPath
                                            ? (str_starts_with($photoPath, 'http') ? $photoPath : asset('storage/' . ltrim($photoPath, '/')))
                                            : null;

                                        $initial = strtoupper(substr($a->applicant_name ?? 'U', 0, 1));
                                    @endphp

                                    <div class="flex items-center gap-3">
                                        <div class="relative w-10 h-10 flex-shrink-0">
                                            @if($photoUrl)
                                                <img src="{{ $photoUrl }}"
                                                     alt="Photo"
                                                     class="w-10 h-10 rounded-full object-cover border-2 border-gray-50 shadow-sm">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-extrabold text-gray-400 border-2 border-gray-50">
                                                    {{ $initial }}
                                                </div>
                                            @endif

                                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="font-bold text-gray-900 leading-tight truncate">
                                                {{ $a->applicant_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5 truncate">
                                                {{ $a->applicant_email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 align-top whitespace-nowrap">
                                    <div class="font-semibold text-gray-800 leading-tight">
                                        {{ $a->opening_title }}
                                    </div>
                                </td>

                                <td class="px-4 py-4 align-top text-gray-800 font-medium whitespace-nowrap">
                                    {{ $jobCat->name ?? ('JobCategory #'.$a->job_category_id) }}
                                </td>

                                <td class="px-4 py-4 align-top whitespace-nowrap">
                                    @if($mappingName)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide border"
                                              style="background: {{ $mappingColor }}20; border-color: {{ $mappingColor }};">
                                            {{ $mappingName }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200 text-xs font-bold">
                                            Not set
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 align-top whitespace-nowrap">
                                    @if($card)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide border
                                            {{ $card->status==='issued'
                                                ? 'bg-green-50 text-green-700 border-green-200'
                                                : 'bg-yellow-50 text-yellow-700 border-yellow-200' }}">
                                            {{ $card->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-50 text-red-700 border border-red-200 text-xs font-bold">
                                            Not created
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 align-top">
                                    <div class="flex flex-nowrap gap-2">
                                        @if($card)
                                            <a href="{{ route('admin.cards.access.edit', $card) }}"
                                               title="Customize Access"
                                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-900 text-white hover:bg-gray-800 transition-all">
                                                <i class="fas fa-cog text-xs"></i>
                                            </a>

                                            <a href="{{ route('admin.cards.preview', $card) }}"
                                               title="Preview Card"
                                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>

                                            @if($card->status === 'issued')
                                                <a href="{{ route('admin.cards.print.html.single', $card) }}"
                                                   target="_blank"
                                                   title="Print Single"
                                                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
                                                    <i class="fas fa-print text-xs"></i>
                                                </a>
                                                <button type="button" disabled
                                                        title="Issued"
                                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-400 cursor-not-allowed">
                                                    <i class="fas fa-check text-xs"></i>
                                                </button>
                                            @else
                                                <button type="button" disabled
                                                        title="Cannot print until issued"
                                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-300 cursor-not-allowed">
                                                    <i class="fas fa-print text-xs"></i>
                                                </button>
                                                <button type="button"
                                                        title="Issue Card"
                                                        data-applicant="{{ $a->applicant_name }}"
                                                        data-action="{{ route('admin.cards.issue', $card) }}"
                                                        class="btnIssueRow w-8 h-8 flex items-center justify-center rounded-lg bg-red-600 text-white hover:bg-red-700 transition-all">
                                                    <i class="fas fa-bolt text-xs"></i>
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @foreach($importedCards as $card)
                            @php
                                $map = $card->mapping;
                                $mappingName = $map->nama_akreditasi ?? null;
                                $mappingColor = $map->warna ?? '#e5e7eb';
                            @endphp

                            <tr class="hover:bg-gray-50/70">
                                <td class="px-4 py-4 align-top">
                                    <input type="checkbox"
                                           name="card_ids[]"
                                           value="{{ $card->id }}"
                                           class="rowCheck rounded border-gray-300"
                                           data-status="{{ $card->status }}">
                                </td>

                                <td class="px-4 py-4 align-top">
                                    @php
                                        $initial = strtoupper(substr($card->cardRecipient->name ?? 'U', 0, 1));
                                    @endphp

                                    <div class="flex items-center gap-3">
                                        <div class="relative w-10 h-10 flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-extrabold text-gray-400 border-2 border-gray-50">
                                                {{ $initial }}
                                            </div>
                                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="font-bold text-gray-900 leading-tight truncate">
                                                {{ $card->cardRecipient->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5 truncate">
                                                (Imported Data)
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 align-top">
                                    <div class="font-semibold text-gray-800 leading-tight">
                                        {{ $card->cardRecipient->category ?? 'Direct Import' }}
                                    </div>
                                </td>

                                <td class="px-4 py-4 align-top text-gray-800 font-medium">
                                    {{ $card->cardRecipient->population ?? 'Not set' }}
                                </td>

                                <td class="px-4 py-4 align-top">
                                    @if($mappingName)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide border"
                                              style="background: {{ $mappingColor }}20; border-color: {{ $mappingColor }};">
                                            {{ $mappingName }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200 text-xs font-bold">
                                            Not set
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 align-top">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide border
                                        {{ $card->status==='issued'
                                            ? 'bg-green-50 text-green-700 border-green-200'
                                            : 'bg-yellow-50 text-yellow-700 border-yellow-200' }}">
                                        {{ $card->status }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 align-top">
                                    <div class="flex flex-nowrap gap-2">
                                        <a href="{{ route('admin.cards.access.edit', $card) }}"
                                           title="Customize Access"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-900 text-white hover:bg-gray-800 transition-all">
                                            <i class="fas fa-cog text-xs"></i>
                                        </a>

                                        <a href="{{ route('admin.cards.preview', $card) }}"
                                           title="Preview Card"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>

                                        @if($card->status === 'issued')
                                            <a href="{{ route('admin.cards.print.html.single', $card) }}"
                                               target="_blank"
                                               title="Print Single"
                                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
                                                <i class="fas fa-print text-xs"></i>
                                            </a>
                                            <button type="button" disabled
                                                    title="Issued"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-400 cursor-not-allowed">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        @else
                                            <button type="button" disabled
                                                    title="Cannot print until issued"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-300 cursor-not-allowed">
                                                <i class="fas fa-print text-xs"></i>
                                            </button>
                                            <button type="button"
                                                    title="Issue Card"
                                                    data-applicant="{{ $card->cardRecipient->name }}"
                                                    data-action="{{ route('admin.cards.issue', $card) }}"
                                                    class="btnIssueRow w-8 h-8 flex items-center justify-center rounded-lg bg-red-600 text-white hover:bg-red-700 transition-all">
                                                <i class="fas fa-bolt text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if(count($apps) === 0 && count($importedCards) === 0)
                            
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                                    Tidak ada applicant approved untuk event ini.
                                </td>
                            </tr>
                        
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <form id="issueSingleForm" method="POST" action="">
        @csrf
    </form>

</div>

{{-- Center Modal --}}
<div id="confirmModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-5 border-b border-gray-100">
                <div class="text-lg font-bold text-gray-900" id="modalTitle">Confirm</div>
                <div class="text-sm text-gray-500 mt-1" id="modalDesc">...</div>
            </div>

            <div class="p-5" id="modalWarnWrap">
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl text-sm font-medium" id="modalWarn">
                    ...
                </div>
            </div>

            <div class="p-5 pt-0 flex justify-end gap-2">
                <button type="button" id="btnCancel"
                        class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold">
                    Cancel
                </button>
                <button type="button" id="btnConfirm"
                        class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold">
                    Yes, Continue
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div id="importModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('importModal').classList.add('hidden')"></div>
    <div class="relative min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <form action="{{ route('admin.cards.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <div class="text-lg font-bold text-gray-900">Import Excel</div>
                        <div class="text-sm text-gray-500 mt-1">Upload file Excel berisi data penerima kartu.</div>
                    </div>
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-5">
                    <a href="{{ route('admin.cards.import.template') }}" class="text-blue-600 text-sm font-semibold hover:underline mb-4 inline-block">
                        <i class="fas fa-download mr-1"></i> Download Template Excel
                    </a>
                    <div class="mt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File (XLSX, XLS, CSV)</label>
                        <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                    </div>
                </div>
                <div class="p-5 pt-0 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white font-bold">
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- MODAL DOWNLOAD --}}
    <div id="downloadModal" class="fixed inset-0 z-[100] hidden">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity download-modal-backdrop"></div>
        <div class="relative min-h-full flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md relative z-10 transform scale-95 opacity-0 transition-all duration-300">
            <div class="p-6">
                <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-download text-indigo-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Download Cards</h3>
                <p class="text-sm text-gray-500 mb-5">Pilih format dan output untuk kartu terpilih.</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Format File</label>
                        <select id="dlFormat" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                            <option value="pdf">PDF (Print Ready)</option>
                            <option value="png">PNG (Image)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Struktur Output</label>
                        <select id="dlStructure" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                            <option value="single">Combined (1 File berisi semua kartu)</option>
                            <option value="zip">ZIP (Banyak file, 1 file per kartu)</option>
                        </select>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div id="dlProgressContainer" class="hidden mt-5">
                    <div class="flex justify-between text-xs text-indigo-700 font-bold mb-1">
                        <span id="dlProgressLabel">Processing...</span>
                        <span id="dlProgressText">0%</span>
                    </div>
                    <div class="w-full bg-indigo-100 rounded-full h-2">
                        <div id="dlProgressBar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" class="btnCancelDownload px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm transition-colors">
                        Batal
                    </button>
                    <button type="button" id="btnStartDownload" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-colors flex items-center">
                        <i class="fas fa-play mr-2"></i> Start
                    </button>
                </div>
            </div>
        </div>
        </div>
    </div>

    {{-- HIDDEN IFRAME FOR CLIENT SIDE RENDER --}}
    {{-- Opacity MUST be 1 so Chrome does not defer Google Fonts loading! --}}
    <iframe name="hidden_download_iframe" id="hidden_download_iframe" class="fixed pointer-events-none" style="width: 1920px; height: 2000px; left: 0; top: 0; z-index: -9999; opacity: 1;"></iframe>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script>
(function () {
  const checkAll = document.getElementById('checkAll');
  const btnIssueSelected = document.getElementById('btnIssueSelected');
  const btnPrintSelected = document.getElementById('btnPrintSelected');

  const modal = document.getElementById('confirmModal');
  const btnCancel = document.getElementById('btnCancel');
  const btnConfirm = document.getElementById('btnConfirm');
  const modalTitle = document.getElementById('modalTitle');
  const modalDesc = document.getElementById('modalDesc');
  const modalWarn = document.getElementById('modalWarn');

  let pendingAction = null;

  function openModal(title, descText, warnText, onConfirm) {
    modalTitle.textContent = title;
    modalDesc.textContent = descText;
    modalWarn.textContent = warnText || '';
    pendingAction = onConfirm;

    btnConfirm.classList.toggle('hidden', typeof onConfirm !== 'function');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.classList.add('hidden');
    document.body.style.overflow = '';
    pendingAction = null;
  }

  // --- DOWNLOAD MODAL LOGIC ---
  const btnDownloadSelected = document.getElementById('btnDownloadSelected');
  const downloadModal = document.getElementById('downloadModal');
  const btnStartDownload = document.getElementById('btnStartDownload');
  const dlFormat = document.getElementById('dlFormat');
  const dlStructure = document.getElementById('dlStructure');
  const dlProgressContainer = document.getElementById('dlProgressContainer');
  const dlProgressBar = document.getElementById('dlProgressBar');
  const dlProgressText = document.getElementById('dlProgressText');
  const dlProgressLabel = document.getElementById('dlProgressLabel');
  const hiddenIframe = document.getElementById('hidden_download_iframe');

  function openDownloadModal() {
    const checked = selectedChecks();
    const issued = checked.filter(cb => cb.dataset.status === 'issued');

    if (issued.length === 0) {
      openModal("Download Selected", "Please select at least one ISSUED card to download.", "Tip: pilih card yang statusnya ISSUED.", null);
      return;
    }
    checked.forEach(cb => { if (cb.dataset.status !== 'issued') cb.checked = false; });
    
    // reset UI
    dlProgressContainer.classList.add('hidden');
    dlProgressBar.style.width = '0%';
    dlProgressText.textContent = '0%';
    btnStartDownload.disabled = false;
    btnStartDownload.innerHTML = '<i class="fas fa-play mr-2"></i> Start';

    downloadModal.classList.remove('hidden');
    // small delay for transition
    setTimeout(() => {
      downloadModal.querySelector('.scale-95').classList.remove('scale-95', 'opacity-0');
      downloadModal.querySelector('.scale-95')?.classList.add('scale-100', 'opacity-100');
    }, 10);
  }

  function closeDownloadModal() {
    downloadModal.classList.add('hidden');
    downloadModal.querySelector('.scale-100')?.classList.remove('scale-100', 'opacity-100');
    downloadModal.querySelector('.scale-100')?.classList.add('scale-95', 'opacity-0');
  }

  document.querySelectorAll('.btnCancelDownload').forEach(btn => btn.addEventListener('click', closeDownloadModal));
  if (btnDownloadSelected) btnDownloadSelected.addEventListener('click', (e) => { e.preventDefault(); openDownloadModal(); });

  // Handle PNG + Single combination (not logical, so force ZIP)
  dlFormat.addEventListener('change', function() {
    if (this.value === 'png') {
        dlStructure.value = 'zip';
        dlStructure.querySelector('option[value="single"]').disabled = true;
    } else {
        dlStructure.querySelector('option[value="single"]').disabled = false;
    }
  });

  if (btnStartDownload) {
    btnStartDownload.addEventListener('click', function() {
      const format = dlFormat.value;
      const structure = dlStructure.value;
      
      btnStartDownload.disabled = true;
      btnStartDownload.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading Data...';

      if (format === 'pdf' && structure === 'single') {
          // Khusus PDF Combined (Satu File), kita lempar ke halaman HTML dengan autoPrint
          // untuk memanfaatkan engine render print native browser (Chrome/Edge).
          btnStartDownload.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating PDF...';
          const form = document.getElementById('cardsBatchForm');
          form.action = @json(route('admin.cards.print.html.batch'));
          
          form.setAttribute('target', '_blank');
          form.submit();
          form.removeAttribute('target');
          
          setTimeout(() => {
              closeDownloadModal();
              btnStartDownload.disabled = false;
              btnStartDownload.innerHTML = '<i class="fas fa-download mr-2"></i> Start Download';
          }, 1500);
          return;
      }

      // === PROSES BROWSER (html2canvas) & API (Browsershot) ===
      dlProgressContainer.classList.remove('hidden');
      dlProgressBar.style.width = '10%';
      dlProgressText.textContent = '10%';
      dlProgressLabel.textContent = 'Fetching cards data...';

      // Submit form to hidden iframe
      const form = document.getElementById('cardsBatchForm');
      form.action = @json(route('admin.cards.print.html.batch'));
      form.setAttribute('target', 'hidden_download_iframe');
      
      let noPrintInput = document.createElement('input');
      noPrintInput.type = 'hidden';
      noPrintInput.name = 'no_print';
      noPrintInput.value = '1';
      form.appendChild(noPrintInput);

      form.submit();
      form.removeAttribute('target');
      form.removeChild(noPrintInput);
      
      // Wait for iframe to load
      hiddenIframe.onload = async function() {
        try {
            dlProgressBar.style.width = '30%';
            dlProgressText.textContent = '30%';
            dlProgressLabel.textContent = 'Rendering cards...';

            const iframeDoc = hiddenIframe.contentDocument || hiddenIframe.contentWindow.document;
            
            // Wait for fonts in the iframe to fully load to prevent fallback font metrics breaking the layout!
            if (iframeDoc.fonts) {
                await iframeDoc.fonts.ready;
            }

            // Fallback delay to ensure Chrome has actually painted the font
            await new Promise(resolve => setTimeout(resolve, 1500));

            const cardElements = iframeDoc.querySelectorAll('.page');
            
            if (!cardElements || cardElements.length === 0) {
                alert("Gagal mengambil data kartu. Pastikan session belum habis.");
                closeDownloadModal();
                return;
            }

            const total = cardElements.length;
            let zip = structure === 'zip' ? new JSZip() : null;

            for (let i = 0; i < total; i++) {
                const el = cardElements[i];
                const fileName = el.dataset.filename || `card_${i+1}`;
                const cardId = el.dataset.cardId;

                if (format === 'png') {
                    // we need to wait slightly so fonts can load in iframe, but usually it's fast
                    const canvas = await html2canvas(el, {
                        scale: 2, // High quality
                        useCORS: true,
                        logging: false,
                        backgroundColor: null
                    });
                    
                    const imgData = canvas.toDataURL('image/png');
                    if (zip) zip.file(`${fileName}.png`, imgData.split(',')[1], {base64: true});
                } else if (format === 'pdf' && structure === 'zip') {
                    dlProgressLabel.textContent = `Generating PDF ${i+1} of ${total}...`;
                    
                    // Fetch perfect PDF from server (Browsershot)
                    const res = await fetch(`/admin/cards/${cardId}/print-pdf`);
                    if (res.ok) {
                        const blob = await res.blob();
                        zip.file(`${fileName}.pdf`, blob);
                    }
                }

                // Update progress
                let progress = 30 + Math.floor(((i + 1) / total) * 60);
                dlProgressBar.style.width = `${progress}%`;
                dlProgressText.textContent = `${progress}%`;
            }

            dlProgressBar.style.width = '95%';
            dlProgressText.textContent = '95%';
            dlProgressLabel.textContent = 'Zipping files...';

            // Generate output (PNG ZIP atau PDF ZIP)
            if (structure === 'zip') {
                const content = await zip.generateAsync({ type: "blob" });
                saveAs(content, `Arise_Cards_Batch.zip`);
            }

            dlProgressBar.style.width = '100%';
            dlProgressText.textContent = '100%';
            dlProgressLabel.textContent = 'Done!';
            
            setTimeout(() => {
                closeDownloadModal();
            }, 1000);

        } catch (error) {
            console.error(error);
            alert("Terjadi kesalahan saat memproses gambar.");
            closeDownloadModal();
        }
      };
    });
  }

  function selectedChecks() {
    return Array.from(document.querySelectorAll('.rowCheck:checked'));
  }

  if (checkAll) {
    checkAll.addEventListener('change', () => {
      document.querySelectorAll('.rowCheck').forEach(cb => cb.checked = checkAll.checked);
    });
  }

  // Issue Selected -> only draft
  if (btnIssueSelected) {
    btnIssueSelected.addEventListener('click', function (e) {
      e.preventDefault();

      const checked = selectedChecks();
      const draft = checked.filter(cb => cb.dataset.status === 'draft');

      if (draft.length === 0) {
        openModal(
          "Issue Selected",
          "Please select at least one DRAFT card to issue.",
          "Tip: pilih card yang statusnya DRAFT.",
          null
        );
        return;
      }

      checked.forEach(cb => { if (cb.dataset.status !== 'draft') cb.checked = false; });

      openModal(
        "Confirm Issue",
        `Issue ${draft.length} card(s)?`,
        "After issuing, the card will be locked and you won’t be able to edit its access settings anymore.",
        () => {
          const form = document.getElementById('cardsBatchForm');
          form.action = @json(route('admin.cards.issueBatch'));
          form.removeAttribute('target');
          form.submit();
        }
      );
    });
  }

  // Print Selected -> only issued + open new tab
  if (btnPrintSelected) {
    btnPrintSelected.addEventListener('click', function (e) {
      e.preventDefault();

      const checked = selectedChecks();
      const issued = checked.filter(cb => cb.dataset.status === 'issued');

      if (issued.length === 0) {
        openModal(
          "Print Selected",
          "Please select at least one ISSUED card to print.",
          "Tip: pilih card yang statusnya ISSUED.",
          null
        );
        return;
      }

      checked.forEach(cb => { if (cb.dataset.status !== 'issued') cb.checked = false; });

      openModal(
        "Confirm Print",
        `Print ${issued.length} issued card(s) in a new tab?`,
        "This will open the browser print dialog in a new tab.",
        () => {
          const form = document.getElementById('cardsBatchForm');
          form.action = @json(route('admin.cards.print.html.batch'));
          form.setAttribute('target', '_blank');
          form.submit();
          form.removeAttribute('target');
        }
      );
    });
  }

  // Issue per-row confirm (FIX: no nested form)
  document.querySelectorAll('.btnIssueRow').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();

      const name = this.getAttribute('data-applicant') || 'this candidate';
      const actionUrl = this.getAttribute('data-action');

      openModal(
        "Confirm Issue",
        `Are you sure you want to issue the card for ${name}?`,
        "After issuing, the card will be locked and you won’t be able to edit its access settings anymore.",
        () => {
          const f = document.getElementById('issueSingleForm');
          f.action = actionUrl;
          f.removeAttribute('target');
          f.submit();
        }
      );
    });
  });

  btnCancel.addEventListener('click', closeModal);
  modal.addEventListener('click', function(e){
    if (e.target === modal || e.target.classList.contains('bg-black/50')) closeModal();
  });

  btnConfirm.addEventListener('click', function () {
    if (typeof pendingAction === 'function') pendingAction();
    closeModal();
  });
})();
</script>
@endsection
