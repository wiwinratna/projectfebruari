@extends('layouts.app')

@section('title', 'Create Event - NOCIS Super Admin')
@section('page-title')
    Create Event <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <a href="{{ route('super-admin.events.index') }}"
       class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold">
        <i class="fas fa-arrow-left mr-2"></i> Back to Events
    </a>

    {{-- Form Card --}}
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Create New Event</h2>

        <form method="POST" action="{{ route('super-admin.events.store') }}" class="space-y-6">
            @csrf
            @include('menu.events.partials.form-fields')

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('super-admin.events.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-600 flex items-center">
                    <i class="fas fa-save mr-2"></i> Save Event
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Fix form fields color scheme from red to blue */
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="date"]:focus,
    input[type="datetime-local"]:focus,
    select:focus,
    textarea:focus {
        --tw-ring-color: #3b82f6;
    }
</style>
@endsection
