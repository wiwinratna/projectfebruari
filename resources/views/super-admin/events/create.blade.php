@extends('layouts.app')

@section('title', 'Create Event - ARISE Super Admin')
@section('page-title')
    Create Event <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header with Back Button --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Create New Event</h2>
            <p class="text-sm text-gray-500 mt-1">Super admin event creation form</p>
        </div>
        <a href="{{ route('super-admin.events.index') }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors shadow-xs">
            <i class="fas fa-arrow-left mr-2"></i> Back to Events
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
        <form method="POST" action="{{ route('super-admin.events.store') }}" class="space-y-6">
            @csrf
            @include('menu.events.partials.form-fields')

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('super-admin.events.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors flex items-center shadow-xs">
                    <i class="fas fa-save mr-2"></i> Save Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
