@extends('layouts.app')

@section('title', 'Manage Hero Slides - NOCIS')
@section('page-title')
    Hero Slides <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Hero Slides</h2>
            <p class="text-gray-600 mt-1">Manage slideshow images and text shown on the landing hero section.</p>
        </div>
        <a href="{{ route('super-admin.hero-slides.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Slide
        </a>
    </div>

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">All Slides</h3>
            <span class="text-sm text-gray-500">{{ $heroSlides->total() }} total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($heroSlides as $slide)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($slide->image_url)
                                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}"
                                         class="w-20 h-12 object-cover rounded border border-gray-200 bg-gray-50">
                                @else
                                    <div class="w-20 h-12 rounded border border-gray-200 bg-gray-50 flex items-center justify-center text-xs text-gray-500">
                                        No image
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">{{ $slide->title }}</p>
                                @if ($slide->subtitle)
                                    <p class="text-xs text-gray-500 mt-1">{{ $slide->subtitle }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-md">
                                {{ $slide->description ?: '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                {{ $slide->sort_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($slide->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <i class="fas fa-circle text-green-500 mr-1" style="font-size:7px"></i> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        <i class="fas fa-circle text-gray-400 mr-1" style="font-size:7px"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('super-admin.hero-slides.edit', $slide) }}"
                                   class="text-blue-600 hover:text-blue-900 font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('super-admin.hero-slides.destroy', $slide) }}"
                                      class="inline-block"
                                      onsubmit="return confirm('Delete this slide? This cannot be undone.');">
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-images text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-sm">No hero slides yet. Add your first slide.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($heroSlides->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $heroSlides->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
