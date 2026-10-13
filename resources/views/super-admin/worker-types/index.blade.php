@extends('layouts.app')

@section('title', 'Manage Worker Types - ARISE')
@section('page-title')
    Manage Worker Types <span class="bg-blue-600 text-white text-sm px-2.5 py-0.5 rounded-full ml-2">Super Admin</span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header with Action --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Worker Types</h2>
            <p class="text-sm text-gray-500 mt-1">Manage worker classification types (e.g. Volunteer, LO, Staff) used across events and job categories</p>
        </div>
        <a href="{{ route('super-admin.worker-types.create') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors shadow-xs text-sm">
            <i class="fas fa-plus mr-2"></i> Create Worker Type
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-id-badge"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Types</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $workerTypes->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Active Types</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $workerTypes->where('is_active', true)->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Categories Linked</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $workerTypes->sum('job_categories_count') }}</p>
            </div>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3 text-sm shadow-xs" role="alert">
            <i class="fas fa-check-circle text-green-500 shrink-0"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3 text-sm shadow-xs" role="alert">
            <i class="fas fa-exclamation-triangle text-red-500 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Main Table Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">All Worker Types</h3>
            <span class="text-xs text-gray-500">{{ $workerTypes->count() }} type(s) configured</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3.5">Code / Name</th>
                        <th class="px-6 py-3.5">Description</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Linked Job Categories</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($workerTypes as $type)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($type->name, 0, 2)) }}
                                    </span>
                                    <span>{{ $type->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                                {{ $type->description ?: '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($type->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                    <i class="fas fa-tags mr-1.5 text-gray-400"></i>
                                    {{ $type->job_categories_count }} Categories
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('super-admin.worker-types.edit', $type) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>

                                    <form method="POST" action="{{ route('super-admin.worker-types.destroy', $type) }}"
                                          class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete worker type \'{{ $type->name }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-id-badge text-4xl mb-3 text-gray-300"></i>
                                <p class="text-base font-medium text-gray-700">No worker types configured yet</p>
                                <p class="text-xs text-gray-500 mt-1">Get started by creating a new worker type.</p>
                                <a href="{{ route('super-admin.worker-types.create') }}"
                                   class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-medium transition-colors">
                                    <i class="fas fa-plus mr-1.5"></i> Add First Worker Type
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
