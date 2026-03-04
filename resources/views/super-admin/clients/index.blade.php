@extends('layouts.app')

@section('title', 'Manage Clients - NOCIS')
@section('page-title')
    Our Clients <span class="bg-blue-500 text-white text-sm px-2 py-1 rounded-full ml-2">Landing Page</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Our Clients</h2>
            <p class="text-gray-600 mt-1">Manage clients displayed on the landing page</p>
        </div>
        <a href="{{ route('super-admin.clients.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i> Add Client
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
            <h3 class="text-lg font-semibold text-gray-800">All Clients</h3>
            <span class="text-sm text-gray-500">{{ $clients->total() }} total</span>
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
                    @forelse ($clients as $client)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($client->logo_url)
                                    <img src="{{ $client->logo_url }}" alt="{{ $client->name }}"
                                         class="w-12 h-12 object-contain rounded border border-gray-200 bg-gray-50 p-1">
                                @else
                                    <div class="w-12 h-12 rounded border border-gray-200 bg-blue-50 flex items-center justify-center">
                                        <span class="text-blue-600 font-bold text-sm">{{ strtoupper(substr($client->name, 0, 2)) }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">{{ $client->name }}</p>
                                @if ($client->description)
                                    <p class="text-xs text-gray-500 mt-1 max-w-xs truncate">{{ $client->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if ($client->website)
                                    <a href="{{ $client->website }}" target="_blank" rel="noopener"
                                       class="text-blue-600 hover:underline truncate max-w-xs block">
                                        {{ $client->website }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                {{ $client->sort_order }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($client->is_active)
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
                                <a href="{{ route('super-admin.clients.edit', $client) }}"
                                   class="text-blue-600 hover:text-blue-900 font-semibold">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('super-admin.clients.destroy', $client) }}"
                                      class="inline-block"
                                      onsubmit="return confirm('Delete this client? This cannot be undone.');">
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
                                <i class="fas fa-building text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-sm">No clients yet. Add your first client.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($clients->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
