@extends('layouts.app')

@section('title', 'Manage Partners - NOCIS')
@section('page-title')
    Our Partners <span class="bg-purple-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Our Partners</h2>
            <p class="text-gray-600 mt-1">Manage partners displayed on the landing page</p>
        </div>
        <a href="{{ route('super-admin.partners.create') }}"
           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Partner
        </a>
    </div>

    {{-- Flash Messages --}}
    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">All Partners</h3>
            <span class="text-sm text-gray-500">{{ $partners->total() }} total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($partners as $partner)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($partner->logo_url)
                                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}"
                                         class="w-12 h-12 object-contain rounded border border-gray-200 bg-gray-50 p-1">
                                @else
                                    <div class="w-12 h-12 rounded border border-gray-200 bg-purple-50 flex items-center justify-center">
                                        <span class="text-purple-600 font-bold text-sm">{{ strtoupper(substr($partner->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">{{ $partner->name }}</p>
                                @if ($partner->description)
                                    <p class="text-xs text-gray-500 mt-1 max-w-xs truncate">{{ $partner->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if ($partner->website)
                                    <a href="{{ $partner->website }}" target="_blank" rel="noopener"
                                       class="text-purple-600 hover:underline truncate max-w-xs block">
                                        {{ $partner->website }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                {{ $partner->sort_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($partner->is_active)
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
                                <a href="{{ route('super-admin.partners.edit', $partner) }}"
                                   class="text-purple-600 hover:text-purple-900 font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('super-admin.partners.destroy', $partner) }}"
                                      class="inline-block"
                                      onsubmit="return confirm('Delete this partner? This cannot be undone.');">
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
                                <i class="fas fa-handshake text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-sm">No partners yet. Add your first partner.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($partners->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $partners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
