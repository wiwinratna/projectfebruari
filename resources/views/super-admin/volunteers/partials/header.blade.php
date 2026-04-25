{{-- Profile Header Card --}}
@php $completion = $user->profile_completion; @endphp
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center overflow-hidden border-2 border-white/30 flex-shrink-0">
                @if($user->profile?->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-white text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                <p class="text-emerald-100 text-sm mt-1">{{ $user->profile?->professional_headline ?? 'No headline set' }}</p>
                <div class="flex flex-wrap items-center gap-3 mt-2">
                    <span class="text-emerald-100 text-xs"><i class="fas fa-envelope mr-1"></i> {{ $user->email }}</span>
                    <span class="text-emerald-100 text-xs"><i class="fas fa-at mr-1"></i> {{ $user->username }}</span>
                    @if($user->profile?->phone)
                        <span class="text-emerald-100 text-xs"><i class="fas fa-phone mr-1"></i> {{ $user->profile->phone }}</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('super-admin.volunteers.edit', $user) }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl text-sm font-semibold hover:bg-white/30 transition-colors border border-white/20"><i class="fas fa-edit mr-1"></i> Edit</a>
                <form method="POST" action="{{ route('super-admin.volunteers.delete', $user) }}" class="inline-block" onsubmit="return confirm('Are you sure?');">@csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500/80 text-white rounded-xl text-sm font-semibold hover:bg-red-600 transition-colors"><i class="fas fa-trash mr-1"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
    <div class="px-6 py-3 bg-gray-50 border-b border-gray-100">
        <div class="flex items-center justify-between mb-1">
            <span class="text-xs font-medium text-gray-600">Profile Completion</span>
            <span class="text-xs font-bold {{ $completion >= 80 ? 'text-emerald-600' : ($completion >= 50 ? 'text-yellow-600' : 'text-red-500') }}">{{ $completion }}%</span>
        </div>
        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-500 {{ $completion >= 80 ? 'bg-emerald-500' : ($completion >= 50 ? 'bg-yellow-500' : 'bg-red-400') }}" style="width: {{ $completion }}%"></div>
        </div>
    </div>
</div>
