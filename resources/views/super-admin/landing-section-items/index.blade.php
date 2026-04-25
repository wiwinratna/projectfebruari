@extends('layouts.app')

@section('title', 'Landing Section Content - NOCIS')
@section('page-title')
    Landing Section Content <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
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
            <h2 class="text-2xl font-bold text-gray-800">Landing Section Content</h2>
            <p class="text-gray-600 mt-1">Manage content for About, Flow, and Features sections on the public landing page.</p>
        </div>
        <a href="{{ route('super-admin.landing-section-items.create', ['section' => $section]) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Item
        </a>
    </div>

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex flex-wrap gap-2">
            @foreach ($sections as $sectionKey)
                <a href="{{ route('super-admin.landing-section-items.index', ['section' => $sectionKey]) }}"
                    class="px-4 py-2 rounded-lg border font-semibold text-sm transition-colors {{ $section === $sectionKey ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:border-blue-300 hover:text-blue-700' }}">
                    {{ $sectionLabels[$sectionKey] ?? ucfirst($sectionKey) }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">{{ $sectionLabels[$section] ?? ucfirst($section) }} Items</h3>
            <span class="text-sm text-gray-500">{{ $items->total() }} total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Emoji</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Highlight</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($items as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->sort_order }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-2xl">{{ $item->emoji ?: '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ $item->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-lg">{{ $item->description ?: '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->highlight ?: '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($item->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('super-admin.landing-section-items.edit', $item) }}"
                                    class="text-blue-600 hover:text-blue-900 font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('super-admin.landing-section-items.destroy', $item) }}"
                                    class="inline-block"
                                    onsubmit="return confirm('Delete this item? This cannot be undone.');">
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-layer-group text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-sm">No items yet for this section.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
