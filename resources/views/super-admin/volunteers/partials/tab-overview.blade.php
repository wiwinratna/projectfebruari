{{-- Tab 1: Overview --}}
@php $completion = $user->profile_completion; @endphp
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left: Quick Info --}}
    <div class="space-y-4">
        <div class="text-center p-6 bg-gray-50 rounded-2xl border border-gray-100">
            <div class="w-24 h-24 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-3 overflow-hidden">
                @if($user->profile?->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-emerald-700 text-3xl font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                @endif
            </div>
            <h3 class="font-bold text-gray-900 text-lg">{{ $user->name }}</h3>
            <p class="text-sm text-gray-500">{{ $user->profile?->professional_headline ?? '—' }}</p>
            <div class="mt-3 flex items-center justify-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $completion >= 80 ? 'bg-emerald-100 text-emerald-700' : ($completion >= 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ $completion }}% Complete
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                    {{ $user->applications->count() }} Applications
                </span>
            </div>
        </div>

        {{-- Contact Quick View --}}
        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-2">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Contact</p>
            <div class="flex items-center text-sm text-gray-700"><i class="fas fa-envelope w-5 text-gray-400 mr-2"></i>{{ $user->email }}</div>
            <div class="flex items-center text-sm text-gray-700"><i class="fas fa-phone w-5 text-gray-400 mr-2"></i>{{ $user->profile?->phone ?? '—' }}</div>
            <div class="flex items-center text-sm text-gray-700"><i class="fas fa-map-marker-alt w-5 text-gray-400 mr-2"></i>{{ Str::limit($user->profile?->address, 40) ?? '—' }}</div>
        </div>
    </div>

    {{-- Right: Summary + Stats --}}
    <div class="lg:col-span-2 space-y-4">
        {{-- Summary --}}
        <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3"><i class="fas fa-align-left mr-1"></i> Summary</p>
            @if($user->profile?->summary)
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $user->profile->summary }}</p>
            @else
                <p class="text-sm text-gray-400 italic">No summary provided</p>
            @endif
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-4 bg-emerald-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-emerald-700">{{ $completion }}%</p>
                <p class="text-xs text-emerald-600">Profile</p>
            </div>
            <div class="p-4 bg-purple-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-purple-700">{{ $user->applications->count() }}</p>
                <p class="text-xs text-purple-600">Applications</p>
            </div>
            <div class="p-4 bg-green-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-green-700">{{ $user->applications->where('status', 'accepted')->count() }}</p>
                <p class="text-xs text-green-600">Accepted</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-xl text-center">
                <p class="text-2xl font-bold text-blue-700">{{ $user->certificates?->count() ?? 0 }}</p>
                <p class="text-xs text-blue-600">Certificates</p>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3"><i class="fas fa-clock mr-1"></i> Recent Applications</p>
            @forelse($user->applications->take(3) as $app)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $app->opening?->title ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500">{{ $app->opening?->event?->title ?? '' }} • {{ $app->created_at->format('M d, Y') }}</p>
                    </div>
                    @php
                        $sc = ['pending'=>'bg-yellow-100 text-yellow-700','accepted'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700'];
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $sc[$app->status] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst($app->status) }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400 italic">No applications yet</p>
            @endforelse
        </div>

        {{-- Account Info --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-xs text-gray-500">Registered</p>
                <p class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-xs text-gray-500">Last Updated</p>
                <p class="text-sm font-semibold text-gray-900">{{ $user->updated_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
