@extends('layouts.public')

@section('title', 'My Applications - NOCIS')

@section('content')
<!-- Modern Web3 Customer Applications -->
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <!-- Main Content Wrapper with Top Padding -->
    <div class="relative z-10 pt-24 sm:pt-32 pb-20 sm:pb-16">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-5 sm:mb-8">
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 tracking-tight">My Applications</h1>
                <p class="text-xs sm:text-base text-gray-600">Track the progress of your career opportunities.</p>
            </div>

            <!-- Navigation Tabs (Hidden on mobile) -->
            @include('partials.customer-nav-tabs')

            <!-- Applications List -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/60 shadow-lg sm:shadow-xl overflow-hidden">
                <div class="px-4 sm:px-8 py-3.5 sm:py-5 border-b border-gray-100 flex justify-between items-center bg-white/40">
                    <h3 class="text-base sm:text-xl font-bold text-gray-900">Application History</h3>
                    <div class="bg-white/70 px-2.5 py-1 rounded-lg border border-gray-100 text-xs sm:text-sm font-medium text-gray-600">
                        Total: <span class="font-bold text-gray-900">{{ $applications->total() }}</span>
                    </div>
                </div>

                <div class="p-3.5 sm:p-6 space-y-3">
                    @forelse($applications as $application)
                    @php
                        $card = $application->card;
                        $cardIssued = $card && $card->status === 'issued';
                        $cert = $application->certificate;
                        $certPublished = $cert && $cert->status === 'published';
                    @endphp
                    <div class="group relative bg-white border border-gray-200/80 hover:border-red-200 rounded-xl sm:rounded-2xl p-3.5 sm:p-5 transition-all duration-200 hover:shadow-md flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4">
                        <!-- Left: Trophy + Content (Event Eyebrow, Title, Date & Notice) -->
                        <div class="flex items-start gap-3 sm:gap-4.5 min-w-0 flex-1">
                            <!-- Sleek Trophy Icon -->
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-br from-red-50 to-white border border-red-100 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200 mt-0.5">
                                <i class="fas fa-trophy text-lg sm:text-xl text-red-500/90 group-hover:text-red-600 transition-colors"></i>
                            </div>

                            <div class="min-w-0 flex-1">
                                <!-- Event Badge (Independent top line - long event names fit with ease!) -->
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-red-600 bg-red-50 px-2.5 py-0.5 rounded-md border border-red-100 truncate max-w-full">
                                        {{ $application->opening->event->title ?? 'EVENT' }}
                                    </span>
                                </div>

                                <!-- Job Title -->
                                <h4 class="text-base sm:text-lg font-bold text-gray-900 group-hover:text-red-600 transition-colors truncate mb-1">
                                    {{ $application->opening->title }}
                                </h4>

                                <!-- Sub-info: Applied Date & Card pending notice -->
                                <div class="flex flex-wrap items-center gap-3 text-xs">
                                    <div class="flex items-center gap-1.5 text-gray-500 font-medium">
                                        <i class="far fa-calendar-alt text-gray-400 text-xs"></i>
                                        <span>Applied on <strong class="text-gray-700 font-semibold">{{ $application->created_at->format('d M Y, H:i') }}</strong></span>
                                    </div>

                                    @if($application->status === 'approved' && !$cardIssued)
                                        <span class="inline-flex items-center gap-1.5 text-xs text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200/70 font-medium">
                                            <i class="fas fa-info-circle text-[11px] text-amber-500"></i>
                                            <span>Card will be issued by admin</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right: Status Badge (Top-Right) & 4 Action Buttons (Bottom-Right) -->
                        <div class="flex flex-col sm:items-end justify-between gap-2.5 shrink-0 pt-3 lg:pt-0 border-t lg:border-t-0 border-gray-100">
                            <!-- Status Badge (Fixed at top-right of every card) -->
                            <div class="flex items-center sm:justify-end">
                                @if($application->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold shadow-2xs">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                        </span>
                                        <span>Pending Review</span>
                                    </span>
                                @elseif($application->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold shadow-2xs">
                                        <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                                        <span>Approved</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold shadow-2xs">
                                        <i class="fas fa-times-circle text-rose-500 text-xs"></i>
                                        <span>Not Selected</span>
                                    </span>
                                @endif
                            </div>

                            <!-- 4 Action Buttons (Bottom-Right) -->
                            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2">
                                {{-- Button 1: Card --}}
                                @if($application->status === 'approved' && $cardIssued)
                                    <a href="{{ route('customer.applications.card', $application) }}"
                                       target="_blank"
                                       class="h-8 sm:h-9 px-2.5 sm:px-3.5 rounded-lg sm:rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-[11px] sm:text-xs inline-flex items-center justify-center gap-1.5 shadow-xs hover:shadow transition-all text-center whitespace-nowrap">
                                        <i class="fas fa-id-card text-xs"></i>
                                        <span>View Card</span>
                                    </a>
                                @elseif($application->status === 'approved')
                                    <button type="button" disabled
                                            class="h-8 sm:h-9 px-2.5 sm:px-3.5 rounded-lg sm:rounded-xl bg-gray-100 text-gray-500 font-bold text-[11px] sm:text-xs inline-flex items-center justify-center gap-1.5 border border-gray-200 cursor-not-allowed text-center whitespace-nowrap"
                                            title="Your card will be available after the admin issues it.">
                                        <i class="fas fa-hourglass-half text-[10px] sm:text-[11px] text-gray-400"></i>
                                        <span>Card Pending</span>
                                    </button>
                                @else
                                    <button type="button" disabled
                                            class="h-8 sm:h-9 px-2.5 sm:px-3.5 rounded-lg sm:rounded-xl bg-gray-50 text-gray-400 font-bold text-[11px] sm:text-xs inline-flex items-center justify-center gap-1.5 border border-gray-100 cursor-not-allowed text-center whitespace-nowrap">
                                        <i class="fas fa-id-card text-[10px] sm:text-[11px] text-gray-300"></i>
                                        <span>Card</span>
                                    </button>
                                @endif

                                {{-- Button 2: Certificate --}}
                                @if($certPublished)
                                    <a href="{{ url('/sertifikat/verify/' . $cert->qr_token) }}"
                                       target="_blank"
                                       class="h-8 sm:h-9 px-2.5 sm:px-3.5 rounded-lg sm:rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-[11px] sm:text-xs inline-flex items-center justify-center gap-1.5 shadow-xs hover:shadow transition-all text-center whitespace-nowrap">
                                        <i class="fas fa-certificate text-xs"></i>
                                        <span>Certificate</span>
                                    </a>
                                @else
                                    <button type="button" disabled
                                            class="h-8 sm:h-9 px-2.5 sm:px-3.5 rounded-lg sm:rounded-xl bg-gray-50 text-gray-400 font-bold text-[11px] sm:text-xs inline-flex items-center justify-center gap-1.5 border border-gray-200/80 cursor-not-allowed text-center whitespace-nowrap"
                                            title="Certificate will be available after the event.">
                                        <i class="fas fa-certificate text-[10px] sm:text-[11px] text-gray-300"></i>
                                        <span>Certificate</span>
                                    </button>
                                @endif

                                {{-- Button 3: View Job --}}
                                <a href="{{ route('jobs.show', $application->opening) }}"
                                   class="h-8 sm:h-9 px-2.5 sm:px-3.5 rounded-lg sm:rounded-xl bg-white hover:bg-gray-50 text-gray-700 hover:text-red-600 font-bold text-[11px] sm:text-xs inline-flex items-center justify-center gap-1.5 border border-gray-200 hover:border-red-200 shadow-2xs transition-all text-center whitespace-nowrap">
                                    <i class="fas fa-arrow-up-right-from-square text-[9px] sm:text-[10px] text-gray-400"></i>
                                    <span>View Job</span>
                                </a>

                                {{-- Button 4: Details --}}
                                <button type="button" 
                                        onclick="openAppDetailsModal({{ $application->id }})"
                                        id="details-btn-{{ $application->id }}"
                                        data-title="{{ $application->opening->title }}"
                                        data-event="{{ $application->opening->event->title ?? 'NOC Indonesia' }}"
                                        data-status="{{ $application->status }}"
                                        data-date="{{ $application->created_at->format('d M Y, H:i') }}"
                                        data-motivation="{{ $application->motivation }}"
                                        data-experience="{{ $application->experience ?? '' }}"
                                        class="h-8 sm:h-9 px-2.5 sm:px-3.5 rounded-lg sm:rounded-xl bg-white hover:bg-gray-50 text-gray-600 hover:text-gray-900 font-bold text-[11px] sm:text-xs inline-flex items-center justify-center gap-1.5 border border-gray-200 shadow-2xs transition-all text-center whitespace-nowrap cursor-pointer">
                                    <i class="fas fa-circle-info text-xs text-gray-400"></i>
                                    <span>Details</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-16 px-4">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-folder-open text-3xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No Applications Yet</h3>
                        <p class="text-gray-500 mb-8 max-w-sm mx-auto">You haven't applied for any positions yet. Start your journey by browsing open roles.</p>
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 hover:shadow-lg hover:shadow-red-500/30 transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-search mr-2"></i> Browse Opportunities
                        </a>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($applications->hasPages())
                <div class="bg-gray-50/50 px-8 py-4 border-t border-gray-100">
                    {{ $applications->links('pagination::tailwind') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Application Details Modal -->
<div id="appDetailsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative border border-gray-100">
        <button onclick="closeAppDetailsModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition-colors cursor-pointer">
            <i class="fas fa-times text-sm"></i>
        </button>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-lg shrink-0">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 leading-tight" id="modalAppTitle">Application Details</h3>
                <p class="text-xs text-gray-500" id="modalAppEvent"></p>
            </div>
        </div>

        <div class="space-y-3.5">
            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                <p class="font-bold text-gray-400 uppercase text-[10px] mb-1">Status</p>
                <div id="modalAppStatus"></div>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                <p class="font-bold text-gray-400 uppercase text-[10px] mb-1">Applied Date</p>
                <p class="font-semibold text-gray-800 text-xs" id="modalAppDate"></p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                <p class="font-bold text-gray-400 uppercase text-[10px] mb-1">Motivation Letter</p>
                <p class="text-gray-700 text-xs whitespace-pre-line leading-relaxed" id="modalAppMotivation"></p>
            </div>
            <div id="modalAppExperienceBox" class="bg-gray-50 rounded-xl p-3 border border-gray-100 hidden">
                <p class="font-bold text-gray-400 uppercase text-[10px] mb-1">Experience</p>
                <p class="text-gray-700 text-xs whitespace-pre-line leading-relaxed" id="modalAppExperience"></p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" onclick="closeAppDetailsModal()" class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition-colors cursor-pointer">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openAppDetailsModal(id) {
        const btn = document.getElementById(`details-btn-${id}`);
        if (!btn) return;
        
        document.getElementById('modalAppTitle').textContent = btn.dataset.title;
        document.getElementById('modalAppEvent').textContent = btn.dataset.event;
        document.getElementById('modalAppDate').textContent = btn.dataset.date;
        document.getElementById('modalAppMotivation').textContent = btn.dataset.motivation || '-';

        const status = btn.dataset.status;
        let statusBadge = '';
        if (status === 'approved') {
            statusBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-green-50 text-green-700 border border-green-200 text-xs font-bold"><i class="fas fa-check-circle text-green-500"></i> Approved</span>';
        } else if (status === 'pending') {
            statusBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-yellow-50 text-yellow-700 border border-yellow-200 text-xs font-bold">Pending Review</span>';
        } else {
            statusBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-red-50 text-red-700 border border-red-200 text-xs font-bold">Not Selected</span>';
        }
        document.getElementById('modalAppStatus').innerHTML = statusBadge;

        const expBox = document.getElementById('modalAppExperienceBox');
        if (btn.dataset.experience && btn.dataset.experience.trim()) {
            document.getElementById('modalAppExperience').textContent = btn.dataset.experience;
            expBox.classList.remove('hidden');
        } else {
            expBox.classList.add('hidden');
        }

        const modal = document.getElementById('appDetailsModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeAppDetailsModal() {
        const modal = document.getElementById('appDetailsModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAppDetailsModal();
        }
    });

    document.getElementById('appDetailsModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAppDetailsModal();
        }
    });
</script>
@endsection
