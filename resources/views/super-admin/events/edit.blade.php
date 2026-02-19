@extends('layouts.app')

@section('title', 'Edit Event - NOCIS Super Admin')
@section('page-title')
    Edit Event <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <a href="{{ route('super-admin.events.show', $event) }}"
       class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold">
        <i class="fas fa-arrow-left mr-2"></i> Back to Event
    </a>

    {{-- Form Card --}}
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Event: {{ $event->title }}</h2>

        <form method="POST" action="{{ route('super-admin.events.update', $event) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('menu.events.partials.form-fields')

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <form action="{{ route('super-admin.events.delete', $event) }}" method="POST" style="display:inline;"
                      onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 font-semibold flex items-center">
                        <i class="fas fa-trash mr-2"></i> Delete Event
                    </button>
                </form>

                <div class="flex items-center gap-3">
                    <a href="{{ route('super-admin.events.index') }}"
                       class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-blue-500 text-white font-semibold hover:bg-blue-600 flex items-center">
                        <i class="fas fa-save mr-2"></i> Update Event
                    </button>
                </div>
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
