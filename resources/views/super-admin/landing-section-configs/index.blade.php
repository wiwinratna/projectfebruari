@extends('layouts.app')

@section('title', 'Landing Section Copy - NOCIS')
@section('page-title')
    Landing Section Copy <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
@endsection

@section('content')
@php
    $sectionLabels = [
        'about' => 'About',
        'flow' => 'Flow',
        'features' => 'Features',
    ];
@endphp

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Landing About Copy</h2>
            <p class="text-gray-600 mt-1">Edit all text content in About section only.</p>
        </div>
        <a href="{{ route('super-admin.landing-section-configs.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Copy
        </a>
    </div>

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">About List</h3>
            <span class="text-sm text-gray-500">{{ $configs->total() }} total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Badge</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtitle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($configs as $config)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $sectionLabels[$config->section] ?? ucfirst($config->section) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $config->badge_text ?: '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $config->title_text ?: '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-lg">{{ $config->subtitle_text ?: '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('super-admin.landing-section-configs.edit', $config) }}"
                                   class="text-blue-600 hover:text-blue-900 font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('super-admin.landing-section-configs.destroy', $config) }}"
                                      class="inline-block"
                                      onsubmit="return confirm('Delete this section copy? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-pen-nib text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-sm">No section copy data yet. Add your first one.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($configs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $configs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
