@extends('layouts.app')

@section('title', 'Worker Details - NOCIS Admin')
@section('page-title')
    <div class="flex items-center">
        <a href="{{ route('admin.workers.index') }}" class="mr-4 text-gray-500 hover:text-gray-700 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        Worker Details <span class="bg-red-500 text-white text-sm px-2 py-1 rounded-full ml-2">Admin</span>
    </div>
@endsection

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $opening->title }}</h2>
                    <div class="flex items-center text-sm text-gray-500 gap-4">
                        <span><i class="fas fa-building mr-1"></i> {{ $opening->event->title ?? 'No Event' }}</span>
                        <span><i class="fas fa-tag mr-1"></i> {{ $opening->jobCategory->name ?? 'No Category' }}</span>
                        <span><i class="fas fa-calendar mr-1"></i> Deadline: {{ \Carbon\Carbon::parse($opening->application_deadline)->format('M d, Y') }}</span>
                    </div>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($opening->status === 'open') bg-green-100 text-green-800
                        @elseif($opening->status === 'closed') bg-gray-100 text-gray-800
                        @else bg-blue-100 text-blue-800 @endif">
                        {{ ucfirst($opening->status) }}
                    </span>
                </div>
            </div>
            
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">Slots Status</p>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-bold text-gray-800">{{ $opening->slots_filled }}</span>
                        <span class="text-gray-500 mb-1">/ {{ $opening->slots_total }} Filled</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(100, ($opening->slots_filled / $opening->slots_total) * 100) }}%"></div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">Total Applicants</p>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-bold text-gray-800">{{ $applications->count() }}</span>
                        <span class="text-gray-500 mb-1">Candidates</span>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-center">
                    <a href="{{ route('admin.workers.edit', $opening) }}" class="flex items-center justify-center w-full h-full text-gray-600 hover:text-red-600 font-medium transition-colors border border-dashed border-gray-300 rounded-lg hover:border-red-300 hover:bg-red-50">
                        <i class="fas fa-edit mr-2"></i> Edit Opening
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Applicants List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Applicants List</h3>
        </div>
        
        @if($applications->isEmpty())
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-users-slash text-4xl text-gray-300 mb-3"></i>
                <p>No applications received yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">Applicant</th>
                            <th class="px-6 py-3">Applied Date</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($applications as $app)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($app->user->profile && $app->user->profile->profile_photo)
                                            <img src="{{ asset('storage/' . $app->user->profile->profile_photo) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200" alt="">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                                {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="ml-3">
                                            <p class="text-sm font-semibold text-gray-800">{{ $app->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $app->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $app->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($app->status === 'approved') bg-green-100 text-green-700
                                        @elseif($app->status === 'rejected') bg-red-100 text-red-700
                                        @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.applications.show', $app->id) }}" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                        Review Application &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

<!-- Review Modal (Existing Logic reused from dashboard if available or partial) -->
<!-- Ideally, link to a review page or reuse the modal logic from dashboard -->

@endsection
