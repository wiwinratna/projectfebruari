{{-- Tab 2: Personal Info --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Account Data --}}
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4"><i class="fas fa-user-circle mr-1"></i> Account Data</p>
        <dl class="space-y-3">
            <div class="flex justify-between"><dt class="text-sm text-gray-500">User ID</dt><dd class="text-sm font-semibold text-gray-900">#{{ $user->id }}</dd></div>
            <div class="flex justify-between"><dt class="text-sm text-gray-500">Full Name</dt><dd class="text-sm font-semibold text-gray-900">{{ $user->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-sm text-gray-500">Username</dt><dd class="text-sm font-semibold text-gray-900">{{ $user->username }}</dd></div>
            <div class="flex justify-between"><dt class="text-sm text-gray-500">Email</dt><dd class="text-sm font-semibold text-gray-900">{{ $user->email }}</dd></div>
            <div class="flex justify-between"><dt class="text-sm text-gray-500">Role</dt><dd class="text-sm font-semibold text-gray-900">{{ ucfirst($user->role) }}</dd></div>
            <div class="flex justify-between"><dt class="text-sm text-gray-500">Registered</dt><dd class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</dd></div>
            <div class="flex justify-between"><dt class="text-sm text-gray-500">Last Updated</dt><dd class="text-sm font-semibold text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</dd></div>
        </dl>
    </div>

    {{-- Personal Details --}}
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4"><i class="fas fa-id-card mr-1"></i> Personal Details</p>
        <dl class="space-y-3">
            <div class="flex justify-between"><dt class="text-sm text-gray-500">Phone</dt><dd class="text-sm font-semibold text-gray-900">{{ $user->profile?->phone ?? '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-sm text-gray-500">Date of Birth</dt><dd class="text-sm font-semibold text-gray-900">{{ $user->profile?->date_of_birth?->format('M d, Y') ?? '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-sm text-gray-500">Headline</dt><dd class="text-sm font-semibold text-gray-900">{{ $user->profile?->professional_headline ?? '—' }}</dd></div>
        </dl>
    </div>

    {{-- Address --}}
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4"><i class="fas fa-map-marker-alt mr-1"></i> Address</p>
        <p class="text-sm text-gray-700">{{ $user->profile?->address ?? 'No address provided' }}</p>
    </div>

    {{-- Social Media --}}
    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4"><i class="fas fa-share-alt mr-1"></i> Social Media</p>
        <div class="space-y-2">
            @php
                $socials = [
                    ['icon' => 'fab fa-linkedin', 'value' => $user->profile?->linkedin, 'color' => 'text-blue-600', 'label' => 'LinkedIn', 'url' => $user->profile?->linkedin],
                    ['icon' => 'fab fa-instagram', 'value' => $user->profile?->instagram, 'color' => 'text-pink-600', 'label' => $user->profile?->instagram, 'url' => 'https://instagram.com/' . $user->profile?->instagram],
                    ['icon' => 'fab fa-twitter', 'value' => $user->profile?->twitter, 'color' => 'text-sky-500', 'label' => $user->profile?->twitter, 'url' => 'https://twitter.com/' . $user->profile?->twitter],
                    ['icon' => 'fab fa-tiktok', 'value' => $user->profile?->tiktok, 'color' => 'text-gray-800', 'label' => $user->profile?->tiktok, 'url' => 'https://tiktok.com/@' . $user->profile?->tiktok],
                    ['icon' => 'fas fa-globe', 'value' => $user->profile?->website, 'color' => 'text-emerald-600', 'label' => 'Website', 'url' => $user->profile?->website],
                ];
                $hasSocial = false;
            @endphp
            @foreach($socials as $s)
                @if($s['value'])
                    @php $hasSocial = true; @endphp
                    <a href="{{ $s['url'] }}" target="_blank" class="flex items-center text-sm {{ $s['color'] }} hover:opacity-75 transition-opacity">
                        <i class="{{ $s['icon'] }} w-5 mr-2 text-center"></i> {{ $s['label'] }}
                        <i class="fas fa-external-link-alt ml-auto text-xs opacity-50"></i>
                    </a>
                @endif
            @endforeach
            @if(!$hasSocial)
                <p class="text-sm text-gray-400 italic">No social media added</p>
            @endif
        </div>
    </div>

    {{-- Password Reset --}}
    <div class="lg:col-span-2 p-5 bg-amber-50 rounded-2xl border border-amber-200">
        <p class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-4"><i class="fas fa-lock mr-1"></i> Reset Password</p>
        <form method="POST" action="{{ route('super-admin.volunteers.reset-password', $user) }}" class="flex flex-col sm:flex-row gap-3 items-end">
            @csrf
            <div class="flex-1">
                <label class="block text-xs text-gray-600 mb-1">New Password</label>
                <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex-1">
                <label class="block text-xs text-gray-600 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required minlength="8" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
            </div>
            <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-semibold text-sm transition-colors whitespace-nowrap"><i class="fas fa-key mr-1"></i> Reset</button>
        </form>
    </div>
</div>
