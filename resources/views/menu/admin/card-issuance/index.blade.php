@extends('layouts.app')

@section('title', 'Card Issuance - ARISE Admin')

@section('page-title')
    Card Issuance <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Card Issuance</h2>
            <p class="text-gray-600 mt-1">Set accreditation, customize access, issue cards, and batch print (A5)</p>
        </div>

        <a href="{{ route('admin.reviews.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center font-semibold">
            <i class="fas fa-arrow-left mr-2"></i> Back to Applications
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-start gap-2">
                <i class="fas fa-check-circle mt-0.5"></i>
                <div class="font-semibold">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-start gap-2">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <div class="font-semibold">{{ session('error') }}</div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-start gap-2">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <div class="font-semibold">{{ $errors->first() }}</div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Approved Applications</h3>
                <div class="text-gray-700 font-semibold">
                    Total: {{ $applications->count() }}
                    &nbsp;•&nbsp; Needs Accreditation: {{ $appsNotSet->count() }}
                    &nbsp;•&nbsp; Cards: {{ $appsSet->count() }}
                </div>
            </div>

            <form method="GET" action="{{ route('admin.card-issuance.index') }}" class="flex gap-2 items-center">
                <div class="relative">
                    <i class="fas fa-search text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Search name/email/opening..."
                        class="pl-9 pr-3 py-2 border border-gray-200 rounded-lg w-80 focus:ring-2 focus:ring-red-200 focus:border-red-300"
                    >
                </div>

                <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                    Search
                </button>

                @if(($search ?? '') !== '')
                    <a href="{{ route('admin.card-issuance.index') }}"
                       class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg font-semibold border border-gray-200">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="px-6 py-3 border-b border-gray-200 bg-gray-50 flex gap-3">
            <button type="button" class="tab-btn px-5 py-2 rounded-full font-bold border" data-tab="notset">
                Needs Accreditation ({{ $appsNotSet->count() }})
            </button>

            <button type="button" class="tab-btn px-5 py-2 rounded-full font-bold border" data-tab="set">
                Cards ({{ $appsSet->count() }})
            </button>
        </div>

        {{-- TAB 1: Needs Accreditation --}}
        <div id="tab-notset" class="tab-panel">

            <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="font-bold text-gray-800">Bulk Set Accreditation</div>

                <form id="bulk-accreditation-form"
                      method="POST"
                      action="{{ route('admin.card-issuance.set-accreditation-bulk') }}"
                      class="flex gap-2 items-center">
                    @csrf

                    <select name="accreditation_id"
                            class="border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-200 focus:border-red-300">
                        <option value="">Select Accreditation</option>
                        @foreach($accreditations as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->nama_akreditasi }}</option>
                        @endforeach
                    </select>

                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center font-semibold">
                        <i class="fas fa-certificate mr-2"></i> Apply
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="text-left px-6 py-3 font-semibold">
                                <input type="checkbox" id="cb-all-notset" class="rounded border-gray-300">
                            </th>
                            <th class="text-left px-6 py-3 font-semibold">Applicant</th>
                            <th class="text-left px-6 py-3 font-semibold">Opening</th>
                            <th class="text-left px-6 py-3 font-semibold">Accreditation</th>
                            <th class="text-right px-6 py-3 font-semibold">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($appsNotSet as $app)
                            @php
                                $photo = $app->user->profile->profile_photo ?? null;
                                $name  = $app->user->name ?? $app->user->username ?? ('User#'.$app->user_id);
                                $email = $app->user->email ?? null;

                                $openingTitle = $app->opening->title ?? ('Opening#'.$app->worker_opening_id);
                                $jobCategory  = $app->opening->jobCategory->name ?? null;
                            @endphp

                            <tr class="hover:bg-gray-50/70">
                                <td class="px-6 py-4">
                                    <input
                                        type="checkbox"
                                        name="application_ids[]"
                                        value="{{ $app->id }}"
                                        form="bulk-accreditation-form"
                                        class="cb-notset rounded border-gray-300"
                                    >
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center">
                                            @if($photo)
                                                <img src="{{ asset('storage/'.$photo) }}" class="w-full h-full object-cover" alt="photo">
                                            @else
                                                <i class="fas fa-user text-gray-400"></i>
                                            @endif
                                        </div>

                                        <div class="font-semibold text-gray-900">
                                            {{ $name }}
                                            @if($email)
                                                <span class="ml-2 text-gray-600 font-normal">{{ $email }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-gray-800 font-semibold">
                                    {{ $openingTitle }}
                                    @if($jobCategory)
                                        <span class="ml-2 text-gray-600 font-normal">{{ $jobCategory }}</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <form method="POST"
                                          action="{{ route('admin.card-issuance.set-accreditation-single', $app->id) }}"
                                          class="flex items-center gap-2">
                                        @csrf
                                        <select name="accreditation_id"
                                                class="border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-200 focus:border-red-300">
                                            <option value="">Select</option>
                                            @foreach($accreditations as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->nama_akreditasi }}</option>
                                            @endforeach
                                        </select>

                                        <button type="submit"
                                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold">
                                            <i class="fas fa-check mr-2"></i> Set
                                        </button>
                                    </form>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin.card-issuance.preview', $app->id) }}"
                                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold">
                                            <i class="fas fa-eye mr-2"></i> Preview
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-700 font-semibold">
                                    No applicants found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- TAB 2: Cards --}}
        <div id="tab-set" class="tab-panel hidden">

            <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="font-bold text-gray-800">Issue & Print</div>

                <div class="flex gap-2 flex-wrap">
                    <form id="bulk-issue-form" method="POST" action="{{ route('admin.card-issuance.issue-bulk') }}">
                        @csrf
                        <button type="submit"
                                class="bg-gray-900 hover:bg-gray-950 text-white px-4 py-2 rounded-lg flex items-center font-semibold">
                            <i class="fas fa-bolt mr-2"></i> Issue Selected Draft
                        </button>
                    </form>

                    <button type="button"
                            id="btn-print-selected"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center font-semibold">
                        <i class="fas fa-print mr-2"></i> Print Selected Issued (A5)
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="text-left px-6 py-3 font-semibold">Issue</th>
                            <th class="text-left px-6 py-3 font-semibold">Applicant</th>
                            <th class="text-left px-6 py-3 font-semibold">Accreditation</th>
                            <th class="text-left px-6 py-3 font-semibold">Card</th>
                            <th class="text-left px-6 py-3 font-semibold">Access Summary</th>
                            <th class="text-right px-6 py-3 font-semibold">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($appsSet as $app)
                            @php
                                $photo = $app->user->profile->profile_photo ?? null;
                                $name  = $app->user->name ?? $app->user->username ?? ('User#'.$app->user_id);
                                $email = $app->user->email ?? null;

                                $card  = $cardsByAppId[$app->id] ?? null;

                                $accName = $app->accreditation_id && isset($accreditationsById[$app->accreditation_id])
                                    ? $accreditationsById[$app->accreditation_id]->nama_akreditasi
                                    : ('Accreditation #'.$app->accreditation_id);

                                $snapshot = null;
                                if ($card && isset($card->id)) {
                                    $snapRow = \App\Models\IssuedCard::select('snapshot')->find($card->id);
                                    $snapshot = $snapRow?->snapshot;
                                }

                                $venuesText = '';
                                $zonesText  = '';

                                if ($snapshot) {
                                    $venues = collect(data_get($snapshot, 'venues', []))->pluck('name')->filter()->values()->all();
                                    $zones  = collect(data_get($snapshot, 'zones', []))->pluck('code')->filter()->values()->all();

                                    $venuesText = implode(', ', $venues);
                                    $zonesText  = implode(', ', $zones);
                                }
                            @endphp

                            <tr class="hover:bg-gray-50/70">
                                <td class="px-6 py-4">
                                    @if($card && $card->status === 'draft')
                                        <input type="checkbox"
                                               name="issued_card_ids[]"
                                               value="{{ $card->id }}"
                                               form="bulk-issue-form"
                                               class="cb-set rounded border-gray-300">
                                    @else
                                        <input type="checkbox" class="rounded border-gray-300" disabled>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center">
                                            @if($photo)
                                                <img src="{{ asset('storage/'.$photo) }}" class="w-full h-full object-cover" alt="photo">
                                            @else
                                                <i class="fas fa-user text-gray-400"></i>
                                            @endif
                                        </div>

                                        <div class="font-semibold text-gray-900">
                                            {{ $name }}
                                            @if($email)
                                                <span class="ml-2 text-gray-600 font-normal">{{ $email }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full font-bold bg-blue-100 text-blue-700">
                                        {{ $accName }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if(!$card)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full font-bold bg-gray-100 text-gray-700">
                                            No Card
                                        </span>
                                    @elseif($card->status === 'draft')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full font-bold bg-yellow-100 text-yellow-800">
                                            Draft
                                        </span>
                                    @elseif($card->status === 'issued')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full font-bold bg-green-100 text-green-700">
                                            Issued
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full font-bold bg-gray-100 text-gray-700">
                                            {{ strtoupper($card->status) }}
                                        </span>
                                    @endif

                                    @if($card && $card->card_number)
                                        <div class="font-semibold text-gray-800 mt-1">{{ $card->card_number }}</div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-gray-800 font-semibold">
                                    @if($venuesText)
                                        Venues: {{ $venuesText }}
                                    @endif
                                    @if($zonesText)
                                        <span class="ml-3">Zones: {{ $zonesText }}</span>
                                    @endif
                                    @if(!$venuesText && !$zonesText)
                                        -
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2 flex-wrap">
                                        <a href="{{ route('admin.card-issuance.preview', $app->id) }}"
                                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold">
                                            <i class="fas fa-eye mr-2"></i> Preview
                                        </a>

                                        @if($card && $card->status === 'draft')
                                            <a href="{{ route('admin.card-issuance.edit-access', $app->id) }}"
                                               class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-semibold border border-blue-100">
                                                <i class="fas fa-sliders-h mr-2"></i> Customize
                                            </a>

                                            <form method="POST" action="{{ route('admin.card-issuance.issue-single', $card->id) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="px-4 py-2 bg-gray-900 hover:bg-gray-950 text-white rounded-lg font-semibold">
                                                    <i class="fas fa-bolt mr-2"></i> Issue
                                                </button>
                                            </form>
                                        @endif

                                        @if($card && $card->status === 'issued')
                                            <label class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg font-semibold border border-red-100 cursor-pointer flex items-center gap-2">
                                                <input type="checkbox" class="cb-print" value="{{ $card->id }}">
                                                <span>Print</span>
                                            </label>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-700 font-semibold">
                                    No cards found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const panels = {
        notset: document.getElementById('tab-notset'),
        set: document.getElementById('tab-set'),
    };

    function activate(tab) {
        Object.keys(panels).forEach(k => panels[k].classList.toggle('hidden', k !== tab));

        tabButtons.forEach(btn => {
            const isActive = btn.dataset.tab === tab;

            btn.classList.toggle('bg-white', isActive);
            btn.classList.toggle('border-gray-200', isActive);
            btn.classList.toggle('text-gray-900', isActive);

            btn.classList.toggle('bg-transparent', !isActive);
            btn.classList.toggle('border-transparent', !isActive);
            btn.classList.toggle('text-gray-600', !isActive);
            btn.classList.toggle('hover:bg-white/60', !isActive);
        });
    }

    activate('notset');
    tabButtons.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.tab)));

    const cbAllNotSet = document.getElementById('cb-all-notset');
    cbAllNotSet?.addEventListener('change', () => {
        document.querySelectorAll('.cb-notset').forEach(cb => cb.checked = cbAllNotSet.checked);
    });

    const btnPrint = document.getElementById('btn-print-selected');
    btnPrint?.addEventListener('click', () => {
        const ids = Array.from(document.querySelectorAll('.cb-print:checked')).map(cb => cb.value);
        if (ids.length === 0) {
            alert('Please select at least one ISSUED card to print.');
            return;
        }
        window.open("{{ route('admin.card-issuance.print') }}" + "?ids=" + ids.join(','), '_blank');
    });
});
</script>

@endsection