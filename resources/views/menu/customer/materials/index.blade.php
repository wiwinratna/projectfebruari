@extends('layouts.public')

@section('title', 'Training Materials - NOCIS')

@push('styles')
<style>
    .materials-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .materials-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    .materials-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
    .materials-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush

@section('content')
<!-- Modern Web3 Customer Dashboard Style -->
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="relative z-10 pt-24 sm:pt-32 pb-20 sm:pb-16">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            
            <!-- Welcome Header -->
            <div class="mb-5 sm:mb-8">
                <h1 class="text-2xl sm:text-4xl font-bold text-gray-900 mb-1 sm:mb-2 tracking-tight">
                    Training <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-400">Materials</span>
                </h1>
                <p class="text-xs sm:text-base text-gray-600">Akses materi pelatihan dan video pembekalan untuk event di mana Anda telah disetujui (Approved).</p>
            </div>

            <!-- Navigation Tabs (Hidden on mobile) -->
            @include('partials.customer-nav-tabs')

            <!-- Events List (Satu Baris Per Event) -->
            <div class="bg-white/70 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/60 shadow-lg sm:shadow-xl overflow-hidden">
                <div class="px-4 sm:px-8 py-3.5 sm:py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-3 bg-white/40">
                    <div>
                        <h3 class="text-base sm:text-xl font-bold text-gray-900">Event Terdaftar</h3>
                        <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Pilih event untuk membuka dokumen dan materi pelatihan Anda.</p>
                    </div>
                </div>

                <div class="p-3.5 sm:p-6 space-y-3">
                    @forelse($events as $event)
                        @php
                            $materialCount = $event->trainingMaterials->count();
                        @endphp
                        
                        {{-- Baris Event --}}
                        <div class="bg-white rounded-xl sm:rounded-2xl p-3.5 sm:p-5 border border-gray-200/80 shadow-xs hover:shadow-md hover:border-red-200 transition-all flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
                            <div class="flex items-start gap-3 sm:gap-4 min-w-0 flex-1">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-50 text-red-600 rounded-xl sm:rounded-2xl flex items-center justify-center shrink-0 shadow-xs">
                                    <i class="fas fa-calendar-check text-base sm:text-xl"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-1">
                                        <h4 class="text-sm sm:text-lg font-bold text-gray-900 truncate">{{ $event->title }}</h4>
                                        @if($materialCount > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-bold bg-red-50 text-red-600 border border-red-200/80">
                                                <i class="fas fa-book-reader text-[9px]"></i> {{ $materialCount }} Materi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-gray-100 text-gray-500">
                                                Belum Ada Materi
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-y-1 gap-x-3 text-[11px] sm:text-xs text-gray-500 font-medium">
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i> {{ $event->city_name ?? 'Lokasi belum ditentukan' }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="far fa-calendar-alt text-gray-400"></i> 
                                            {{ $event->start_at->format('d M Y') }} - {{ $event->end_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="shrink-0 md:text-right pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">
                                <button type="button" 
                                        onclick="openMaterialsModal({{ $event->id }})" 
                                        class="inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold transition-all shadow-sm hover:shadow-md w-full md:w-auto gap-2">
                                    <i class="fas fa-folder-open text-xs"></i>
                                    <span>Lihat Materi</span>
                                    <span class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-white/20 text-white text-[10px] sm:text-[11px] flex items-center justify-center font-bold">{{ $materialCount }}</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                <i class="fas fa-folder-open text-3xl text-gray-300"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Event Aktif</h4>
                            <p class="text-gray-500 max-w-md mx-auto text-sm">
                                Anda belum memiliki event dengan status lamaran yang disetujui (Approved). Materi akan muncul setelah admin event menyetujui lamaran Anda.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODALS SECTION: Scrollable, Bulletproof Pop-Up Dialogs --}}
<div id="materials-modals-container">
    @foreach($events as $event)
        @php
            $materialCount = $event->trainingMaterials->count();
        @endphp
        <div id="materials-modal-{{ $event->id }}" 
             class="materials-modal" 
             style="display: none; position: fixed; inset: 0; width: 100%; height: 100%; z-index: 99999; overflow-y: auto;"
             role="dialog" 
             aria-modal="true">
            
            {{-- Dark Blur Backdrop --}}
            <div style="position: fixed; inset: 0; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); z-index: 99999;" 
                 onclick="closeMaterialsModal({{ $event->id }})"></div>

            {{-- Centered Dialog Wrapper --}}
            <div style="position: relative; z-index: 100000; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; pointer-events: none;">
                
                {{-- Dialog Box (Sleek, Scrollable & Pinned Header/Footer) --}}
                <div style="pointer-events: auto; width: 100%; max-width: 32rem; background: #ffffff; border-radius: 1.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #f1f5f9; overflow: hidden; display: flex; flex-direction: column; max-height: 85vh;"
                     onclick="event.stopPropagation()">
                    
                    {{-- Modal Header (Pinned at Top) --}}
                    <div style="padding: 1.25rem 1.5rem; background: #fafafa; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                            <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem; border: 1px solid #fee2e2;">
                                <i class="fas fa-book-reader"></i>
                            </div>
                            <div style="min-width: 0; padding-right: 0.5rem;">
                                <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $event->title }}">
                                    {{ $event->title }}
                                </h4>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><i class="fas fa-map-marker-alt" style="color: #94a3b8; margin-right: 0.25rem;"></i>{{ $event->city_name ?? 'Event' }}</span>
                                    <span>•</span>
                                    <span style="font-weight: 600; color: #dc2626;">{{ $materialCount }} Materi</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" 
                                onclick="closeMaterialsModal({{ $event->id }})" 
                                style="width: 2.25rem; height: 2.25rem; border-radius: 9999px; background: #f1f5f9; border: none; color: #64748b; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: all 0.2s;"
                                onmouseover="this.style.background='#fee2e2'; this.style.color='#dc2626';"
                                onmouseout="this.style.background='#f1f5f9'; this.style.color='#64748b';"
                                title="Tutup">
                            <i class="fas fa-times" style="font-size: 0.875rem;"></i>
                        </button>
                    </div>

                    {{-- Modal Body (Scrollable List with Custom Scrollbar) --}}
                    <div class="materials-scroll" style="padding: 1.25rem 1.5rem; overflow-y: auto; display: flex; flex-direction: column; gap: 0.875rem; flex: 1; max-height: calc(85vh - 130px); background: #f8fafc;">
                        @forelse($event->trainingMaterials as $material)
                            <div style="background: #ffffff; border-radius: 1rem; padding: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                                <div style="display: flex; align-items: flex-start; gap: 0.875rem;">
                                    {{-- File or Video Icon --}}
                                    @if($material->link_url && !$material->file_path)
                                        <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem; border: 1px solid #dbeafe;">
                                            <i class="fas fa-video"></i>
                                        </div>
                                    @else
                                        <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem; border: 1px solid #fee2e2;">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                    @endif
                                    
                                    {{-- Info --}}
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.25rem;">
                                            <h5 style="font-size: 0.875rem; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.3;" title="{{ $material->title }}">
                                                {{ $material->title }}
                                            </h5>
                                            @if($material->link_url)
                                                <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.1rem 0.4rem; border-radius: 0.375rem; background: #eff6ff; color: #1d4ed8; font-size: 0.65rem; font-weight: 700;">
                                                    <i class="fas fa-play" style="font-size: 0.55rem;"></i> Video / Link
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($material->description)
                                            <p style="font-size: 0.75rem; color: #475569; margin: 0 0 0.5rem 0; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $material->description }}">
                                                {{ $material->description }}
                                            </p>
                                        @endif

                                        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.375rem; font-size: 0.6875rem; margin-bottom: 0.75rem;">
                                            @if($material->file_path)
                                                <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.125rem 0.5rem; border-radius: 0.375rem; background: #f1f5f9; color: #475569; max-width: 14rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $material->original_filename }}">
                                                    <i class="fas fa-paperclip" style="font-size: 0.625rem; color: #94a3b8;"></i>
                                                    <span style="overflow: hidden; text-overflow: ellipsis;">{{ $material->original_filename }}</span>
                                                </span>
                                            @endif
                                            @if($material->available_from || $material->available_until)
                                                <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.125rem 0.5rem; border-radius: 0.375rem; background: #ecfdf5; color: #047857;">
                                                    <i class="fas fa-clock" style="font-size: 0.625rem;"></i>
                                                    {{ $material->available_from ? $material->available_from->format('d M') : 'Awal' }} - {{ $material->available_until ? $material->available_until->format('d M Y') : 'Seterusnya' }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Actions (Download & Buka Video) --}}
                                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; padding-top: 0.5rem; border-top: 1px solid #f1f5f9; flex-wrap: wrap;">
                                            @if($material->link_url)
                                                <a href="{{ $material->link_url }}" 
                                                   target="_blank" 
                                                   rel="noopener noreferrer" 
                                                   style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.4rem 0.875rem; border-radius: 0.75rem; background: #eff6ff; color: #1d4ed8; font-size: 0.75rem; font-weight: 700; text-decoration: none; border: 1px solid #bfdbfe; transition: all 0.2s;"
                                                   onmouseover="this.style.background='#dbeafe';"
                                                   onmouseout="this.style.background='#eff6ff';">
                                                    <i class="fas fa-play" style="font-size: 0.65rem;"></i>
                                                    <span>Buka Video / Link</span>
                                                    <i class="fas fa-external-link-alt" style="font-size: 0.6rem; opacity: 0.7;"></i>
                                                </a>
                                            @endif
                                            @if($material->file_path)
                                                <a href="{{ route('customer.materials.download', $material->id) }}" 
                                                   style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.4rem 0.875rem; border-radius: 0.75rem; background: linear-gradient(135deg, #dc2626, #ef4444); color: #ffffff; font-size: 0.75rem; font-weight: 700; text-decoration: none; box-shadow: 0 1px 2px rgba(220, 38, 38, 0.2); transition: all 0.2s;"
                                                   onmouseover="this.style.boxShadow='0 4px 6px -1px rgba(220, 38, 38, 0.3)';"
                                                   onmouseout="this.style.boxShadow='0 1px 2px rgba(220, 38, 38, 0.2)';">
                                                    <i class="fas fa-download" style="font-size: 0.6875rem;"></i>
                                                    <span>Download File</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="padding: 2.5rem 1rem; text-align: center; background: #ffffff; border-radius: 1rem; border: 1px solid #e2e8f0;">
                                <div style="width: 3.5rem; height: 3.5rem; background: #f8fafc; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem auto; color: #94a3b8; font-size: 1.5rem;">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h5 style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0 0 0.25rem 0;">Belum Ada Materi</h5>
                                <p style="font-size: 0.75rem; color: #64748b; margin: 0 auto; max-width: 16rem;">
                                    Materi pelatihan untuk event ini belum diunggah oleh admin.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Modal Footer (Pinned at Bottom) --}}
                    <div style="padding: 0.875rem 1.5rem; background: #ffffff; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;">
                        <span style="font-size: 0.75rem; color: #94a3b8;">
                            Tekan <kbd style="padding: 0.125rem 0.375rem; border-radius: 0.25rem; background: #f1f5f9; color: #475569; font-family: monospace; font-size: 0.625rem; border: 1px solid #e2e8f0;">ESC</kbd> untuk menutup
                        </span>
                        <button type="button" 
                                onclick="closeMaterialsModal({{ $event->id }})" 
                                style="padding: 0.4rem 1rem; background: #f1f5f9; border: none; color: #334155; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#e2e8f0';"
                                onmouseout="this.style.background='#f1f5f9';">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    function openMaterialsModal(eventId) {
        const modal = document.getElementById(`materials-modal-${eventId}`);
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeMaterialsModal(eventId) {
        const modal = document.getElementById(`materials-modal-${eventId}`);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.materials-modal').forEach(m => {
                m.style.display = 'none';
            });
            document.body.style.overflow = '';
        }
    });
</script>
@endpush
@endsection
