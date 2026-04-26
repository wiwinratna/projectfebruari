{{-- Tab 4: Applications History --}}
<div class="space-y-4">
    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 text-center">
            <p class="text-xl font-bold text-gray-800">{{ $user->applications->count() }}</p>
            <p class="text-xs text-gray-500">Total</p>
        </div>
        <div class="p-3 bg-yellow-50 rounded-xl border border-yellow-100 text-center">
            <p class="text-xl font-bold text-yellow-700">{{ $user->applications->where('status', 'pending')->count() }}</p>
            <p class="text-xs text-yellow-600">Pending</p>
        </div>
        <div class="p-3 bg-green-50 rounded-xl border border-green-100 text-center">
            <p class="text-xl font-bold text-green-700">{{ $user->applications->where('status', 'accepted')->count() }}</p>
            <p class="text-xs text-green-600">Accepted</p>
        </div>
        <div class="p-3 bg-red-50 rounded-xl border border-red-100 text-center">
            <p class="text-xl font-bold text-red-700">{{ $user->applications->where('status', 'rejected')->count() }}</p>
            <p class="text-xs text-red-600">Rejected</p>
        </div>
    </div>

    {{-- Application List --}}
    @if($user->applications->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reviewer</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($user->applications as $app)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $app->opening?->title ?? 'Unknown' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm text-gray-700">{{ $app->opening?->event?->title ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @if($app->opening?->jobCategory)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs">{{ $app->opening->jobCategory->name }}</span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $app->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $colors = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'accepted' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'reviewed' => 'bg-blue-100 text-blue-700',
                                    ];
                                    $icons = [
                                        'pending' => 'fa-hourglass-half',
                                        'accepted' => 'fa-check-circle',
                                        'rejected' => 'fa-times-circle',
                                        'reviewed' => 'fa-eye',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $colors[$app->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    <i class="fas {{ $icons[$app->status] ?? 'fa-circle' }} mr-1"></i> {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                @if($app->reviewer)
                                    {{ $app->reviewer->name }}
                                    @if($app->reviewed_at)
                                        <br><span class="text-xs">{{ $app->reviewed_at->format('M d, Y') }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 font-medium">No applications yet</p>
            <p class="text-sm text-gray-400 mt-1">This volunteer hasn't applied to any position</p>
        </div>
    @endif
</div>
