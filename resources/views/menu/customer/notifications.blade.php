@extends('layouts.public')

@section('title', 'Notifications - NOCIS')

@section('content')
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <div class="relative z-10 pt-32 pb-16">
        <div class="container mx-auto px-4 lg:px-6 max-w-7xl">
            <div class="mb-10">
                <h1 class="text-4xl font-bold text-gray-900 mb-2 tracking-tight">Notifications</h1>
                <p class="text-gray-600 text-lg">Stay updated with your applications and card status.</p>
            </div>

            <div class="bg-white/60 backdrop-blur-xl rounded-2xl p-2 border border-white/50 shadow-sm mb-10 inline-flex flex-wrap gap-2">
                <a href="{{ route('customer.dashboard') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-white/50 transition-all">
                    <i class="fas fa-th-large mr-2"></i> Dashboard
                </a>
                <a href="{{ route('customer.applications') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-white/50 transition-all">
                    <i class="fas fa-file-alt mr-2"></i> My Applications
                </a>
                <a href="{{ route('customer.saved-jobs') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-white/50 transition-all">
                    <i class="fas fa-bookmark mr-2"></i> Saved Jobs
                </a>
                <a href="{{ route('customer.notifications.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-white text-red-600 shadow-md transition-all">
                    <i class="fas fa-bell mr-2"></i> Notifications
                </a>
            </div>

            @if(session('status'))
                <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/60 shadow-xl overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white/40">
                    <h3 class="text-xl font-bold text-gray-900">Latest Updates</h3>
                    <form method="POST" action="{{ route('customer.notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-black transition-colors">
                            Mark All Read
                        </button>
                    </form>
                </div>

                <div class="p-6 space-y-4">
                    @forelse($notifications as $notification)
                        @php
                            $data = $notification->data ?? [];
                            $isUnread = is_null($notification->read_at);
                            $link = $data['link'] ?? route('customer.applications');
                        @endphp
                        <div class="rounded-2xl border p-4 transition-all {{ $isUnread ? 'bg-red-50/60 border-red-100' : 'bg-white/70 border-gray-100' }}">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $data['title'] ?? 'Notification' }}</p>
                                        @if($isUnread)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">
                                                Unread
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $data['message'] ?? '' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Event: {{ $data['event_title'] ?? '-' }} | Opening: {{ $data['opening_title'] ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at?->diffForHumans() }}</p>
                                </div>
                                <div class="flex shrink-0 items-center gap-2">
                                    <a href="{{ $link }}" class="px-3 py-1.5 rounded-lg border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                        Open
                                    </a>
                                    @if($isUnread)
                                        <form method="POST" action="{{ route('customer.notifications.read', $notification->id) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-blue-600 text-xs font-semibold text-white hover:bg-blue-700">
                                                Mark Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 px-4">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-bell-slash text-3xl text-gray-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No notifications yet</h3>
                            <p class="text-gray-500 max-w-sm mx-auto">You will see updates here when your application status changes or your card is issued.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="bg-gray-50/50 px-8 py-4 border-t border-gray-100">
                        {{ $notifications->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
