@extends('layouts.app')

@section('title', 'Edit Landing Section Copy - NOCIS')
@section('page-title')
    Edit Landing Copy <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
@endsection

@section('content')
@php
    $sectionLabels = [
        'about' => 'About',
        'flow' => 'Flow',
        'features' => 'Features',
    ];
@endphp

<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('super-admin.landing-section-configs.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit About Copy</h2>
            <p class="text-gray-600 text-sm mt-1">Update {{ $sectionLabels[$config->section] ?? ucfirst($config->section) }} text content.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('super-admin.landing-section-configs.update', $config) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="section" class="block text-sm font-semibold text-gray-700 mb-1">Section <span class="text-red-500">*</span></label>
                <select name="section" id="section" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($sections as $sectionKey)
                        <option value="{{ $sectionKey }}" {{ old('section', $config->section) === $sectionKey ? 'selected' : '' }}>
                            {{ $sectionLabels[$sectionKey] ?? ucfirst($sectionKey) }}
                        </option>
                    @endforeach
                </select>
            </div>

            @include('super-admin.landing-section-configs.partials.form-fields', ['config' => $config])

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold text-sm transition-colors">
                    <i class="fas fa-save mr-2"></i> Update Copy
                </button>
                <a href="{{ route('super-admin.landing-section-configs.index') }}"
                    class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
