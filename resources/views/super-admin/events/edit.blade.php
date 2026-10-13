@extends('layouts.app')

@section('title', 'Edit Event - ARISE Super Admin')
@section('page-title')
    Edit Event <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header with Back Button --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Event: {{ $event->title }}</h2>
            <p class="text-sm text-gray-500 mt-1">Super admin event edit form</p>
        </div>
        <a href="{{ route('super-admin.events.show', $event) }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors shadow-xs">
            <i class="fas fa-arrow-left mr-2"></i> Back to Event
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
        <form method="POST" action="{{ route('super-admin.events.update', $event) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('menu.events.partials.form-fields')

            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                {{-- Delete button triggers the separate delete form below --}}
                <button type="button"
                        onclick="if(confirm('Are you sure you want to delete this event? This action cannot be undone.')) { document.getElementById('delete-event-form').submit(); }"
                        class="px-4 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 font-medium flex items-center transition-colors">
                    <i class="fas fa-trash mr-2"></i> Delete Event
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('super-admin.events.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors flex items-center shadow-xs">
                        <i class="fas fa-save mr-2"></i> Update Event
                    </button>
                </div>
            </div>
        </form>

        {{-- Separate delete form — must NOT be nested inside the update form --}}
        <form id="delete-event-form" method="POST" action="{{ route('super-admin.events.delete', $event) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
