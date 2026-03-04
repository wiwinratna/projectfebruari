@extends('layouts.app')

@section('title', 'Customize Access - Card Issuance')
@section('page-title')
    Customize Access <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Customize Access</h2>
            <p class="text-gray-600 mt-1">Override default access package before issuing the card</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.card-issuance.preview', $application->id) }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center font-semibold">
                <i class="fas fa-eye mr-2"></i> Preview
            </a>

            <a href="{{ route('admin.card-issuance.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center font-semibold">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-start gap-2">
                <i class="fas fa-check-circle mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-start gap-2">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex items-start gap-2">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <div class="text-sm font-medium">{{ $errors->first() }}</div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-lg font-semibold text-gray-800">
                        Applicant: {{ $application->user->name ?? $application->user->username ?? ('User#'.$application->user_id) }}
                    </div>
                    <div class="text-gray-600">
                        Application ID: <b>{{ $application->id }}</b>
                        • Card Status: <b>{{ strtoupper($card->status) }}</b>
                        @if($card->card_number)
                            • Card No: <b>{{ $card->card_number }}</b>
                        @endif
                    </div>
                </div>

                @if($card->status === 'issued')
                    <span class="inline-flex items-center px-3 py-1 rounded-full font-bold bg-green-100 text-green-700">
                        ISSUED (Locked)
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full font-bold bg-yellow-100 text-yellow-800">
                        DRAFT (Editable)
                    </span>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('admin.card-issuance.update-access', $application->id) }}" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Transport & Accommodation</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Transportation Code</label>
                            <select name="transportation_code_id"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-200 focus:border-red-300"
                                    @disabled($card->status === 'issued')>
                                <option value="">None</option>
                                @foreach($transportationCodes as $t)
                                    <option value="{{ $t->id }}" @selected((int)$selectedTransportId === (int)$t->id)>
                                        #{{ $t->id }} {{ $t->code ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Accommodation Code</label>
                            <select name="accommodation_code_id"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-200 focus:border-red-300"
                                    @disabled($card->status === 'issued')>
                                <option value="">None</option>
                                @foreach($accommodationCodes as $a)
                                    <option value="{{ $a->id }}" @selected((int)$selectedAccomId === (int)$a->id)>
                                        #{{ $a->id }} {{ $a->code ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Venue Access</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($venues as $v)
                            <label class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-3 py-3">
                                <input type="checkbox"
                                       name="venue_access_ids[]"
                                       value="{{ $v->id }}"
                                       class="rounded border-gray-300"
                                       @checked(in_array((int)$v->id, array_map('intval', $selectedVenueIds)))
                                       @disabled($card->status === 'issued')>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $v->nama_vanue ?? $v->nama_venue ?? $v->name ?? ('Venue#'.$v->id) }}</div>
                                    <div class="text-gray-600">{{ $v->keterangan ?? '' }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Zone Access</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach($zones as $z)
                            <label class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-3 py-3">
                                <input type="checkbox"
                                       name="zone_access_code_ids[]"
                                       value="{{ $z->id }}"
                                       class="rounded border-gray-300"
                                       @checked(in_array((int)$z->id, array_map('intval', $selectedZoneIds)))
                                       @disabled($card->status === 'issued')>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $z->kode_zona ?? ('ZONE#'.$z->id) }}</div>
                                    <div class="text-gray-600">{{ $z->keterangan ?? '' }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.card-issuance.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                    Cancel
                </a>

                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg font-semibold"
                        @disabled($card->status === 'issued')>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection