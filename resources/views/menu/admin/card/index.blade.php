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
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Approved Applicants</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Semua kandidat yang sudah diterima (approved) + kartu (draft/issued).
                </p>
            </div>
        </div>

        {{-- Row 2: LEFT = Search/Filter | RIGHT = Actions --}}
        <div class="mt-5 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">

            {{-- LEFT: Search + select + Filter --}}
            <form class="w-full xl:w-auto" method="GET" action="{{ route('admin.cards.index') }}">
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    <div class="w-full sm:w-80">
                        <input type="text" name="q" value="{{ $q }}" placeholder="Search name/email/opening..."
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl
                                      focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                    </div>

                    <div class="w-full sm:w-44">
                        <select name="card_status"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl
                                       focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm">
                            <option value="">All Cards</option>
                            <option value="draft" {{ $statusCard==='draft' ? 'selected' : '' }}>Draft</option>
                            <option value="issued" {{ $statusCard==='issued' ? 'selected' : '' }}>Issued</option>
                        </select>
                    </div>

                    <button class="w-full sm:w-auto px-5 py-3 rounded-xl bg-gray-900 text-white hover:bg-gray-800 font-bold text-sm transition-all">
                        Filter
                    </button>
                </div>
            </form>

            {{-- RIGHT: Actions --}}
            <div class="w-full xl:w-auto flex flex-wrap xl:justify-end items-center gap-2">
                <a href="{{ route('admin.cards.index') }}"
                   class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm font-bold text-gray-700 transition-colors whitespace-nowrap">
                    Reset
                </a>

                <button id="btnIssueSelected" type="button"
                        class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition-all whitespace-nowrap">
                    <i class="fas fa-bolt mr-2"></i> Issue Selected
                </button>

                <button id="btnPrintSelected" type="button"
                        class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold transition-all whitespace-nowrap">
                    <i class="fas fa-print mr-2"></i> Print Selected Issued
                </button>

                <a href="{{ route('admin.cards.previewAll', request()->query()) }}"
                   class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm font-bold text-gray-700 transition-colors whitespace-nowrap">
                    <i class="fas fa-grid-2 mr-2"></i> Preview All
                </a>
            </div>
        </div>

        <div class="mt-3 text-xs text-gray-500">
            Tips: centang beberapa baris lalu klik <b>Issue Selected</b> (untuk Draft) atau <b>Print Selected Issued</b> (untuk Issued).
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
                        @forelse($apps as $a)
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

                                <td class="px-4 py-4 align-top">
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

                                <td class="px-4 py-4 align-top">
                                    <div class="font-semibold text-gray-800 leading-tight">
                                        {{ $a->opening_title }}
                                    </div>
                                </td>

                                <td class="px-4 py-4 align-top text-gray-800 font-medium">
                                    {{ $jobCat->name ?? ('JobCategory #'.$a->job_category_id) }}
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
                                    <div class="flex flex-wrap gap-2">
                                        @if($card)
                                            <a href="{{ route('admin.cards.access.edit', $card) }}"
                                               class="px-3 py-2 rounded-xl bg-gray-900 text-white hover:bg-gray-800 text-xs font-bold transition-all whitespace-nowrap">
                                                Customize Access
                                            </a>

                                            <a href="{{ route('admin.cards.preview', $card) }}"
                                               class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-bold text-gray-700 transition-colors whitespace-nowrap">
                                                Preview
                                            </a>

                                            @if($card->status === 'issued')
                                                <a href="{{ route('admin.cards.print.html.single', $card) }}"
                                                   target="_blank"
                                                   class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-bold text-gray-700 transition-colors whitespace-nowrap">
                                                    Print Single
                                                </a>
                                            @else
                                                <button type="button" disabled
                                                        class="px-3 py-2 rounded-xl bg-gray-100 text-gray-400 text-xs font-bold cursor-not-allowed whitespace-nowrap">
                                                    Print Single
                                                </button>
                                            @endif

                                            @if($card->status === 'issued')
                                                <button type="button" disabled
                                                        class="px-3 py-2 rounded-xl bg-gray-100 text-gray-400 text-xs font-bold cursor-not-allowed whitespace-nowrap">
                                                    Issued
                                                </button>
                                            @else
                                                <button type="button"
                                                        data-applicant="{{ $a->applicant_name }}"
                                                        data-action="{{ route('admin.cards.issue', $card) }}"
                                                        class="btnIssueRow px-3 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700 text-xs font-bold transition-all whitespace-nowrap">
                                                    Issue
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                                    Tidak ada applicant approved untuk event ini.
                                </td>
                            </tr>
                        @endforelse
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