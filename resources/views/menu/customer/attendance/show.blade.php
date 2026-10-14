@extends('layouts.public')

@section('title', 'Absensi: ' . $event->title . ' - ARISE')

@push('styles')
<style>
    @keyframes shutter-flash {
        0% { opacity: 0; }
        50% { opacity: 0.85; }
        100% { opacity: 0; }
    }
    .flash-active {
        animation: shutter-flash 0.3s ease-out;
    }
</style>
@endpush

@section('content')
@php
    $isCheckIn = !$attendance;
    $isCheckOut = $attendance && !$attendance->check_out_at;
    $isComplete = $attendance && $attendance->check_out_at;
    $typeLabel = $isCheckIn ? 'Check-in' : ($isCheckOut ? 'Check-out' : 'Selesai');
    $formAction = $isCheckIn ? route('customer.attendance.checkin', $event->id) : route('customer.attendance.checkout', $event->id);
@endphp
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <!-- Background Blurs -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <div class="relative z-10 pt-24 sm:pt-32 pb-20 sm:pb-16">
        <div class="w-full px-3 sm:px-6 lg:px-8 max-w-2xl mx-auto">
            
            <div class="mb-4 sm:mb-6">
                <a href="{{ route('customer.attendance.index') }}" class="inline-flex items-center text-xs sm:text-sm font-semibold text-gray-500 hover:text-red-600 mb-2 sm:mb-3 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Event
                </a>
                <h1 class="text-xl sm:text-3xl font-bold text-gray-900 mb-1 tracking-tight">
                    Absensi Kehadiran Event
                </h1>
                <p class="text-gray-600 text-xs sm:text-sm font-medium">{{ $event->title }} • {{ $event->city_name ?? 'Lokasi Event' }}</p>
            </div>

            <!-- Alerts -->
            @if(session('error'))
                <div class="mb-4 sm:mb-5 bg-red-50 border border-red-200 text-red-700 px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl shadow-sm text-xs sm:text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-red-500 text-base sm:text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 sm:mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl shadow-sm text-xs sm:text-sm font-medium flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-500 text-base sm:text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 sm:mb-5 bg-red-50 border border-red-200 text-red-700 px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl sm:rounded-2xl shadow-sm text-xs sm:text-sm font-medium">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/60 shadow-xl overflow-hidden p-4 sm:p-6 md:p-8">
                
                <!-- Status Panel -->
                <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-3.5 rounded-2xl border {{ $attendance && $attendance->check_in_at ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-50 border-gray-200' }}">
                        <p class="text-[11px] font-bold uppercase tracking-wider {{ $attendance && $attendance->check_in_at ? 'text-emerald-600' : 'text-gray-500' }} mb-0.5">Status Check-in</p>
                        @if($attendance && $attendance->check_in_at)
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                                <span class="text-base font-bold text-gray-900">{{ $attendance->check_in_at->format('H:i:s') }} WIB</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-0.5">{{ $attendance->check_in_at->format('d M Y') }}</p>
                        @else
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-clock text-amber-500 text-sm"></i>
                                <span class="text-sm font-bold text-gray-700">Belum Check-in</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-3.5 rounded-2xl border {{ $attendance && $attendance->check_out_at ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-50 border-gray-200' }}">
                        <p class="text-[11px] font-bold uppercase tracking-wider {{ $attendance && $attendance->check_out_at ? 'text-emerald-600' : 'text-gray-500' }} mb-0.5">Status Check-out</p>
                        @if($attendance && $attendance->check_out_at)
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                                <span class="text-base font-bold text-gray-900">{{ $attendance->check_out_at->format('H:i:s') }} WIB</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-0.5">{{ $attendance->check_out_at->format('d M Y') }}</p>
                        @else
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-clock text-gray-400 text-sm"></i>
                                <span class="text-sm font-bold text-gray-700">Belum Check-out</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if(!$isComplete)

                    <!-- PERINGATAN KETENTUAN FOTO ABSENSI -->
                    <div class="mb-5 bg-gradient-to-r from-amber-50 via-orange-50/60 to-amber-50 border border-amber-200 rounded-2xl p-4 shadow-sm flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-amber-500/20 mt-0.5 text-base">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="text-xs space-y-1 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md bg-amber-200/80 text-amber-900 font-bold text-[10px] uppercase tracking-wider">Perhatian</span>
                                <h4 class="font-bold text-gray-900 text-sm">Ketentuan Foto Absensi</h4>
                            </div>
                            <ul class="text-gray-700 leading-relaxed space-y-1 pt-0.5">
                                <li class="flex items-start gap-1.5">
                                    <span class="text-amber-600 font-bold">•</span>
                                    <span>Wajib mengambil foto <strong>langsung di lokasi event</strong> (bukan di rumah/luar venue).</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-amber-600 font-bold">•</span>
                                    <span>Pastikan <strong>wajah dan aktivitas penugasan terlihat jelas</strong> dengan seragam / ID card panitia.</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-amber-600 font-bold">•</span>
                                    <span>Tanggal, jam, menit, dan detik akan <strong>tercatat permanen</strong> sebagai bukti kehadiran resmi.</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Banner Peringatan Browser WhatsApp / In-App Browser -->
                    <div id="inapp-browser-banner" class="hidden mb-4 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 border border-emerald-500/30 text-emerald-950 rounded-2xl p-3.5 flex items-start gap-3 text-xs shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 mt-0.5 text-sm shadow-sm">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-emerald-950">Membuka Halaman di Dalam WhatsApp?</p>
                            <p class="text-emerald-800 mt-0.5 leading-relaxed">
                                Browser bawaan WhatsApp sering membatasi akses streaming kamera. Agar kamera aktif lancar, klik <strong>titik 3 (⋮)</strong> di pojok kanan atas, lalu pilih <strong>"Buka di Browser"</strong> atau <strong>"Buka di Chrome"</strong>. Anda juga bisa langsung gunakan tombol <strong>Kamera Bawaan HP</strong> di bawah.
                            </p>
                        </div>
                    </div>

                    <form id="attendance-form" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="photo_base64" id="photo_base64">
                        <input type="file" name="photo" id="photo_file" class="hidden" accept="image/*">
                        <!-- Native Camera Fallback Input -->
                        <input type="file" id="native-camera-input" accept="image/*" capture="user" class="hidden" onchange="handleNativeCameraCapture(event)">

                        <!-- LIVE CAMERA VIEWFINDER & PREVIEW BOX -->
                        <div class="relative w-full max-w-sm mx-auto rounded-3xl overflow-hidden shadow-2xl bg-slate-900 border-4 border-white/80 aspect-[3/4] mb-5 flex items-center justify-center">
                            
                            <!-- Shutter Flash Effect -->
                            <div id="shutter-flash" class="absolute inset-0 bg-white pointer-events-none opacity-0 z-30"></div>

                            <!-- Live Video Stream -->
                            <video id="camera-stream" autoplay playsinline webkit-playsinline muted class="w-full h-full object-cover"></video>

                            <!-- Captured Photo Preview (Unobscured) -->
                            <img id="captured-preview" class="hidden w-full h-full object-cover z-10" alt="Hasil Foto">

                            <!-- Top Overlay: Live Status & Digital Clock (Active only during live stream) -->
                            <div id="camera-top-overlay" class="absolute top-3 inset-x-3 flex items-center justify-between z-20 pointer-events-none">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-600/90 text-white text-[11px] font-bold backdrop-blur-md shadow-md animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span> LIVE
                                </span>
                                
                                <!-- Digital Clock with Hours, Minutes, Seconds -->
                                <div class="px-3 py-1 rounded-xl bg-black/60 backdrop-blur-md text-white font-mono text-xs font-bold shadow-md tracking-wider border border-white/10 flex items-center gap-1.5">
                                    <i class="fas fa-clock text-amber-400 text-[10px]"></i>
                                    <span id="live-clock-text">--:--:-- WIB</span>
                                </div>
                            </div>

                            <!-- Top Overlay after Capture: Success Tag -->
                            <div id="camera-captured-tag" class="hidden absolute top-3 inset-x-3 flex items-center justify-between z-20 pointer-events-none">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-600/95 text-white text-xs font-bold backdrop-blur-md shadow-lg">
                                    <i class="fas fa-check-circle"></i> FOTO TERSIMPAN
                                </span>
                                <span class="px-2.5 py-1 rounded-xl bg-black/60 backdrop-blur-md text-white font-mono text-[11px] font-bold">
                                    Siap Dikirim
                                </span>
                            </div>

                            <!-- Bottom Overlay: Live Preview Guidance (Active only during live stream) -->
                            <div id="camera-bottom-overlay" class="absolute bottom-3 inset-x-3 p-3 rounded-2xl bg-black/60 backdrop-blur-md text-white border border-white/10 z-20 pointer-events-none text-left">
                                <div class="text-xs font-bold truncate leading-tight">{{ $event->title }}</div>
                                <div class="text-[11px] text-gray-300 flex items-center justify-between mt-1">
                                    <span class="truncate"><i class="fas fa-user-check text-emerald-400 mr-1"></i>{{ session('customer_username', 'Volunteer') }}</span>
                                    <span id="live-date-text" class="text-amber-300 font-semibold flex-shrink-0 ml-2">-- --- ----</span>
                                </div>
                            </div>

                            <!-- Camera Loading / Permission Block -->
                            <div id="camera-loading" class="absolute inset-0 bg-slate-900 text-white flex flex-col items-center justify-center p-6 text-center z-10">
                                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-3 animate-spin">
                                    <i class="fas fa-circle-notch text-xl text-red-500"></i>
                                </div>
                                <p class="text-sm font-bold">Menghubungkan Kamera...</p>
                                <p class="text-xs text-gray-400 mt-1 max-w-xs">Pastikan Anda telah mengizinkan akses kamera jika muncul permintaan izin.</p>
                                <div class="flex items-center gap-2 mt-4">
                                    <button type="button" onclick="startCamera()" class="px-3.5 py-2 bg-white/20 hover:bg-white/30 rounded-xl text-xs font-bold text-white transition">
                                        Izinkan / Coba Lagi
                                    </button>
                                    <button type="button" onclick="triggerNativeCamera()" class="px-3.5 py-2 bg-red-600 hover:bg-red-700 rounded-xl text-xs font-bold text-white transition shadow-sm">
                                        <i class="fas fa-camera mr-1"></i> Buka Kamera HP
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden Canvas for Watermarking -->
                            <canvas id="camera-canvas" class="hidden"></canvas>
                        </div>

                        <!-- CAMERA CONTROLS -->
                        <div class="space-y-2.5 sm:space-y-3">
                            <!-- State 1: Shutter Button (Before Capture) -->
                            <div id="control-before-capture" class="flex flex-col items-center gap-2">
                                <div class="flex items-center justify-center gap-2.5 sm:gap-3 w-full">
                                    <!-- Switch Camera (Mobile) -->
                                    <button type="button" onclick="toggleCameraFacing()" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition shadow-sm" title="Putar Kamera">
                                        <i class="fas fa-sync-alt text-sm sm:text-base"></i>
                                    </button>

                                    <!-- Big Capture Shutter Button -->
                                    <button type="button" id="btn-snap" onclick="takeSnapshot()" class="flex-1 py-2.5 sm:py-3.5 px-4 sm:px-6 rounded-xl sm:rounded-2xl bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white font-bold text-xs sm:text-sm shadow-md sm:shadow-lg shadow-red-500/25 hover:shadow-red-500/40 transition-all flex items-center justify-center gap-2">
                                        <i class="fas fa-camera text-sm sm:text-base"></i>
                                        <span>Ambil Foto {{ $typeLabel }}</span>
                                    </button>
                                </div>

                                <!-- Native Camera Fallback Quick Link -->
                                <button type="button" onclick="triggerNativeCamera()" class="text-[11px] sm:text-xs font-medium text-gray-500 hover:text-red-600 transition flex items-center gap-1.5 pt-1">
                                    <i class="fas fa-mobile-alt text-gray-400"></i>
                                    <span>Kamera web tidak nyala? <strong>Klik untuk gunakan Kamera Bawaan HP</strong></span>
                                </button>
                            </div>

                            <!-- State 2: After Capture (Preview & Submit) -->
                            <div id="control-after-capture" class="hidden space-y-2">
                                <div class="flex gap-2 sm:gap-2.5">
                                    <button type="button" onclick="retakeSnapshot()" class="w-1/3 py-2.5 sm:py-3.5 px-3 sm:px-4 rounded-xl sm:rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-[11px] sm:text-xs transition flex items-center justify-center gap-1.5">
                                        <i class="fas fa-redo-alt"></i>
                                        <span>Foto Ulang</span>
                                    </button>

                                    <button type="submit" id="btn-submit-attendance" class="flex-1 py-2.5 sm:py-3.5 px-3 sm:px-4 rounded-xl sm:rounded-2xl {{ $isCheckIn ? 'bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 shadow-red-500/25' : 'bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 shadow-blue-500/25' }} text-white font-bold text-xs sm:text-sm shadow-md sm:shadow-lg transition-all flex items-center justify-center gap-2">
                                        <i class="fas fa-paper-plane text-[10px] sm:text-xs"></i>
                                        <span id="btn-submit-label">Kirim Bukti {{ $typeLabel }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                @else
                    <!-- Selesai (Check-in & Check-out Complete) -->
                    <div class="text-center py-6">
                        <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-emerald-100 shadow-inner">
                            <i class="fas fa-check-circle text-3xl text-emerald-500"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">Absensi Lengkap!</h3>
                        <p class="text-gray-500 max-w-md mx-auto text-sm mb-6">
                            Anda telah menyelesaikan proses Check-in dan Check-out untuk event <strong>{{ $event->title }}</strong>. Terima kasih atas dedikasi dan kontribusi Anda!
                        </p>

                        <!-- Preview Bukti Foto Kehadiran -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg mx-auto mb-6 text-left">
                            <div class="bg-gray-50 rounded-2xl p-3 border border-gray-200">
                                <p class="text-xs font-bold text-gray-700 mb-2 flex items-center gap-1.5">
                                    <i class="fas fa-camera text-emerald-500"></i> Bukti Foto Check-in
                                </p>
                                <img src="{{ route('customer.attendance.photo', ['event' => $event->id, 'type' => 'check-in']) }}" alt="Foto Check-in" class="w-full h-40 object-cover rounded-xl shadow-sm bg-gray-100">
                                <p class="text-[11px] text-gray-500 mt-2 text-center">{{ $attendance->check_in_at?->format('d M Y, H:i:s') }} WIB</p>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-3 border border-gray-200">
                                <p class="text-xs font-bold text-gray-700 mb-2 flex items-center gap-1.5">
                                    <i class="fas fa-camera text-blue-500"></i> Bukti Foto Check-out
                                </p>
                                <img src="{{ route('customer.attendance.photo', ['event' => $event->id, 'type' => 'check-out']) }}" alt="Foto Check-out" class="w-full h-40 object-cover rounded-xl shadow-sm bg-gray-100">
                                <p class="text-[11px] text-gray-500 mt-2 text-center">{{ $attendance->check_out_at?->format('d M Y, H:i:s') }} WIB</p>
                            </div>
                        </div>

                        <!-- Reset & Ulangi Button -->
                        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-center gap-3">
                            <a href="{{ route('customer.attendance.index') }}" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-bold transition">
                                <i class="fas fa-arrow-left mr-1.5"></i> Kembali ke Daftar Event
                            </a>
                            <form action="{{ route('customer.attendance.reset', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset dan mengulang absensi event ini dari awal?')">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 bg-gray-100 hover:bg-red-50 text-gray-700 hover:text-red-600 rounded-xl text-xs font-bold transition border border-gray-200">
                                    <i class="fas fa-redo-alt mr-1.5"></i> Reset & Foto Ulang dari Awal
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

@if(!$isComplete)
@push('scripts')
<script>
    let currentStream = null;
    let currentFacingMode = 'user'; // 'user' (front) or 'environment' (back)
    let isPhotoTaken = false;
    let recordedTimestamp = '';

    const videoEl = document.getElementById('camera-stream');
    const previewEl = document.getElementById('captured-preview');
    const canvasEl = document.getElementById('camera-canvas');
    const loadingEl = document.getElementById('camera-loading');
    const clockTextEl = document.getElementById('live-clock-text');
    const dateTextEl = document.getElementById('live-date-text');
    const flashEl = document.getElementById('shutter-flash');

    const topOverlay = document.getElementById('camera-top-overlay');
    const bottomOverlay = document.getElementById('camera-bottom-overlay');
    const capturedTag = document.getElementById('camera-captured-tag');

    const controlBefore = document.getElementById('control-before-capture');
    const controlAfter = document.getElementById('control-after-capture');
    const submitLabel = document.getElementById('btn-submit-label');
    const base64Input = document.getElementById('photo_base64');
    const fileInput = document.getElementById('photo_file');
    const nativeInput = document.getElementById('native-camera-input');

    // Detect In-App Browsers (WhatsApp, Instagram, Facebook, Line)
    (function detectInAppBrowser() {
        const ua = navigator.userAgent || navigator.vendor || window.opera || '';
        const isWhatsApp = /WhatsApp/i.test(ua);
        const isInApp = /FBAN|FBAV|Instagram|Line/i.test(ua);
        if (isWhatsApp || isInApp) {
            const banner = document.getElementById('inapp-browser-banner');
            if (banner) banner.classList.remove('hidden');
        }
    })();

    // Trigger Native Camera Input
    function triggerNativeCamera() {
        if (nativeInput) {
            nativeInput.click();
        }
    }

    // Helper: Draw Rounded Rectangle on Canvas
    function drawRoundedRect(ctx, x, y, width, height, radius) {
        ctx.beginPath();
        if (ctx.roundRect) {
            ctx.roundRect(x, y, width, height, radius);
        } else {
            ctx.moveTo(x + radius, y);
            ctx.lineTo(x + width - radius, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
            ctx.lineTo(x + width, y + height - radius);
            ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
            ctx.lineTo(x + radius, y + height);
            ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
            ctx.lineTo(x, y + radius);
            ctx.quadraticCurveTo(x, y, x + radius, y);
        }
        ctx.closePath();
    }

    // Live Digital Clock (Jam, Menit, Detik)
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const timeString = `${hours}:${minutes}:${seconds} WIB`;
        if (clockTextEl) clockTextEl.textContent = timeString;

        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const dateString = `${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
        if (dateTextEl) dateTextEl.textContent = dateString;

        return { timeString, dateString };
    }

    setInterval(updateClock, 1000);
    updateClock();

    // Display Friendly Camera Error with Native Camera Alternative
    function showCameraError(title, message, errCode) {
        if (!loadingEl) return;
        loadingEl.classList.remove('hidden');
        const debugLine = errCode ? `<p class="text-[10px] text-gray-500 mt-2 font-mono">Error: ${errCode}</p>` : '';
        loadingEl.innerHTML = `
            <div class="p-4 sm:p-5 text-center flex flex-col items-center justify-center max-w-sm">
                <div class="w-12 h-12 rounded-2xl bg-red-500/20 text-red-500 flex items-center justify-center mb-3 text-2xl shadow-inner">
                    <i class="fas fa-video-slash"></i>
                </div>
                <h4 class="text-sm font-bold text-white mb-1 leading-snug">${title}</h4>
                <p class="text-xs text-gray-300 mb-3 leading-relaxed">${message}</p>
                ${debugLine}
                <div class="flex flex-col w-full gap-2 mt-3">
                    <button type="button" onclick="requestCameraWithGesture()" class="w-full py-2.5 px-4 bg-white/15 hover:bg-white/25 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                        <i class="fas fa-video text-sm"></i>
                        <span>Izinkan & Nyalakan Kamera</span>
                    </button>
                    <button type="button" onclick="triggerNativeCamera()" class="w-full py-2.5 px-4 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center justify-center gap-2">
                        <i class="fas fa-camera text-sm"></i>
                        <span>Gunakan Kamera Bawaan HP</span>
                    </button>
                </div>
            </div>
        `;
    }

    // Request camera with explicit user gesture (fixes permission prompt suppression)
    async function requestCameraWithGesture() {
        if (loadingEl) {
            loadingEl.classList.remove('hidden');
            loadingEl.innerHTML = `
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-3 animate-spin">
                    <i class="fas fa-circle-notch text-xl text-red-500"></i>
                </div>
                <p class="text-sm font-bold">Meminta Izin Kamera...</p>
                <p class="text-xs text-gray-400 mt-1 max-w-xs">Jika muncul pop-up, klik "Izinkan".</p>
            `;
        }
        await startCamera();
    }

    // Handle Camera Failure Details
    function handleCameraFailure(err) {
        console.error('Camera stream initialization failed:', err);
        const ua = navigator.userAgent || '';
        const isWhatsApp = /WhatsApp/i.test(ua);
        const isHttp = location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1';
        const errCode = err ? (err.name || 'UnknownError') : 'NoError';

        let title = 'Akses Kamera Ditolak / Tidak Ditemukan';
        let message = 'Tap tombol "Izinkan & Nyalakan Kamera" di bawah. Jika pop-up muncul, pilih Izinkan.';

        if (isHttp) {
            title = 'Koneksi Belum Menggunakan HTTPS';
            message = 'Browser HP mewajibkan koneksi aman (HTTPS) untuk kamera web. Silakan gunakan tombol Kamera Bawaan HP di bawah ini!';
        } else if (isWhatsApp) {
            title = 'Browser WhatsApp Membatasi Kamera';
            message = 'Browser bawaan WhatsApp memblokir streaming kamera langsung. Silakan buka di Chrome.';
        } else if (err && (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError')) {
            title = 'Izin Kamera Ditolak';
            message = 'Klik "Izinkan & Nyalakan Kamera" lalu tap "Izinkan" saat pop-up muncul. Jika tidak muncul, buka Settings browser → Kamera → Izinkan.';
        } else if (err && (err.name === 'NotReadableError' || err.name === 'TrackStartError')) {
            title = 'Kamera Sedang Digunakan';
            message = 'Kamera sedang dipakai oleh aplikasi lain. Tutup semua aplikasi lain lalu tap "Izinkan & Nyalakan Kamera".';
        } else if (err && err.name === 'NotFoundError') {
            title = 'Kamera Tidak Ditemukan';
            message = 'Tidak ada kamera yang terdeteksi di perangkat ini. Gunakan Kamera Bawaan HP.';
        } else if (err && err.name === 'OverconstrainedError') {
            title = 'Sensor Kamera Tidak Sesuai';
            message = 'Resolusi kamera tidak didukung sensor HP. Silakan gunakan tombol Kamera Bawaan HP di bawah.';
        }

        showCameraError(title, message, errCode);
    }

    // Start Live Camera Stream with Progressive Fallbacks
    async function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            const isHttp = location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1';
            showCameraError(
                isHttp ? 'Akses Kamera Memerlukan HTTPS' : 'Kamera Web Tidak Didukung',
                isHttp 
                    ? 'Browser HP mewajibkan koneksi aman (HTTPS). Karena halaman ini diakses via HTTP biasa, browser memblokir kamera. Silakan gunakan tombol Kamera Bawaan HP.'
                    : 'Browser ini tidak mendukung streaming kamera. Silakan gunakan Chrome/Safari atau klik tombol Kamera Bawaan HP di bawah.'
            );
            return;
        }

        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
            currentStream = null;
        }

        if (loadingEl) {
            loadingEl.classList.remove('hidden');
            loadingEl.innerHTML = `
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-3 animate-spin">
                    <i class="fas fa-circle-notch text-xl text-red-500"></i>
                </div>
                <p class="text-sm font-bold">Menghubungkan Kamera...</p>
                <p class="text-xs text-gray-400 mt-1 max-w-xs">Pastikan Anda telah mengizinkan akses kamera jika muncul permintaan izin.</p>
            `;
        }

        // Progressive constraint fallbacks for broad mobile compatibility
        const constraintAttempts = [
            // Attempt 1: Ideal facingMode and ideal 1280x720
            {
                video: {
                    facingMode: { ideal: currentFacingMode },
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            },
            // Attempt 2: FacingMode only (unconstrained dimensions)
            {
                video: {
                    facingMode: { ideal: currentFacingMode }
                },
                audio: false
            },
            // Attempt 3: Any video track
            {
                video: true,
                audio: false
            }
        ];

        let stream = null;
        let lastError = null;

        for (const constraints of constraintAttempts) {
            try {
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                if (stream) break;
            } catch (err) {
                lastError = err;
                console.warn('Constraint attempt failed, trying fallback...', constraints, err);
            }
        }

        if (!stream) {
            handleCameraFailure(lastError);
            return;
        }

        currentStream = stream;
        videoEl.srcObject = stream;

        videoEl.onloadedmetadata = () => {
            const playPromise = videoEl.play();
            if (playPromise !== undefined) {
                playPromise.then(() => {
                    if (loadingEl) loadingEl.classList.add('hidden');
                }).catch(playErr => {
                    console.warn('Video play caught:', playErr);
                    if (loadingEl) loadingEl.classList.add('hidden');
                });
            } else {
                if (loadingEl) loadingEl.classList.add('hidden');
            }
        };
    }

    // Toggle Front / Back Camera
    function toggleCameraFacing() {
        currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
        startCamera();
    }

    // Handle Native Camera Fallback File Selection
    function handleNativeCameraCapture(event) {
        const file = event.target.files && event.target.files[0];
        if (!file) return;

        if (loadingEl) {
            loadingEl.classList.remove('hidden');
            loadingEl.innerHTML = `
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mb-3 animate-spin">
                    <i class="fas fa-circle-notch text-xl text-red-500"></i>
                </div>
                <p class="text-sm font-bold">Memproses Foto & Watermark...</p>
                <p class="text-xs text-gray-400 mt-1">Membubuhkan tanggal dan jam resmi kehadiran.</p>
            `;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                // Scale down large camera photos proportionally to keep payload lean
                let w = img.naturalWidth || 1280;
                let h = img.naturalHeight || 720;
                const maxDim = 1280;
                if (w > maxDim || h > maxDim) {
                    if (w > h) {
                        h = Math.round((h * maxDim) / w);
                        w = maxDim;
                    } else {
                        w = Math.round((w * maxDim) / h);
                        h = maxDim;
                    }
                }

                renderWatermarkedPhoto(img, w, h);
                if (loadingEl) loadingEl.classList.add('hidden');
            };
            img.onerror = function() {
                alert('Gagal memproses file foto. Silakan coba lagi.');
                if (loadingEl) loadingEl.classList.add('hidden');
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Take Snapshot from Live Video
    function takeSnapshot() {
        if (!videoEl || !videoEl.videoWidth) {
            alert('Kamera belum siap. Harap tunggu sebentar atau gunakan tombol Kamera Bawaan HP.');
            return;
        }

        // Shutter flash effect
        if (flashEl) {
            flashEl.classList.add('flash-active');
            setTimeout(() => flashEl.classList.remove('flash-active'), 300);
        }

        const width = videoEl.videoWidth || 1280;
        const height = videoEl.videoHeight || 720;
        renderWatermarkedPhoto(videoEl, width, height);
    }

    // Burn Official Attendance Watermark Card and Setup Submission
    function renderWatermarkedPhoto(sourceElement, width, height) {
        canvasEl.width = width;
        canvasEl.height = height;
        const ctx = canvasEl.getContext('2d');

        // 1. Draw raw photo frame
        ctx.drawImage(sourceElement, 0, 0, width, height);

        // 2. Format Date and Time
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeFormatted = `${hours}:${minutes}:${seconds}`;

        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dayName = days[now.getDay()];
        const dateFormatted = `${dayName}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;

        const volunteerName = "{{ session('customer_username', 'Volunteer') }}";
        const eventTitle = "{{ $event->title }}";
        const statusType = "{{ $typeLabel }}".toUpperCase();

        // 3. Floating Frosted Glass Card Geometry (Responsive to aspect ratio)
        const paddingX = Math.round(width * 0.035);
        const paddingY = Math.round(height * 0.035);
        const cardWidth = width - (paddingX * 2);
        const cardHeight = Math.max(140, Math.min(Math.round(height * 0.22), 200));
        const cardX = paddingX;
        const cardY = height - cardHeight - paddingY;
        const radius = Math.round(cardHeight * 0.12);

        // 4. Draw Floating Frosted Glass Card
        ctx.save();
        ctx.shadowColor = 'rgba(0, 0, 0, 0.6)';
        ctx.shadowBlur = 18;
        ctx.shadowOffsetY = 6;

        drawRoundedRect(ctx, cardX, cardY, cardWidth, cardHeight, radius);
        ctx.fillStyle = 'rgba(11, 18, 33, 0.88)'; // Deep navy glass
        ctx.fill();

        // Subtle Glass Border
        ctx.shadowColor = 'transparent';
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.18)';
        ctx.lineWidth = 1.5;
        ctx.stroke();
        ctx.restore();

        // 5. Card Inner Contents
        const innerPadding = Math.round(cardHeight * 0.12);
        const contentX = cardX + innerPadding;
        const contentRightX = cardX + cardWidth - innerPadding;

        // Top Row: Verified Pill Tag (Left) & Date (Right)
        const topRowY = cardY + Math.round(cardHeight * 0.24);

        // Verified Pill Tag
        const isCheckInAction = statusType.includes('CHECK-IN');
        const pillText = `● ${statusType} TERVERIFIKASI`;
        ctx.font = `bold ${Math.max(10, Math.round(cardHeight * 0.085))}px system-ui, -apple-system, sans-serif`;
        const pillTextWidth = ctx.measureText(pillText).width;
        const pillBoxWidth = pillTextWidth + 16;
        const pillBoxHeight = Math.round(cardHeight * 0.14);

        drawRoundedRect(ctx, contentX, topRowY - pillBoxHeight + 3, pillBoxWidth, pillBoxHeight, 6);
        ctx.fillStyle = isCheckInAction ? 'rgba(220, 38, 38, 0.25)' : 'rgba(37, 99, 235, 0.25)';
        ctx.fill();
        ctx.fillStyle = isCheckInAction ? '#f87171' : '#60a5fa';
        ctx.fillText(pillText, contentX + 8, topRowY - 2);

        // Date on Right
        ctx.font = `500 ${Math.max(11, Math.round(cardHeight * 0.088))}px system-ui, -apple-system, sans-serif`;
        ctx.fillStyle = '#94a3b8';
        const dateWidth = ctx.measureText(dateFormatted).width;
        ctx.fillText(dateFormatted, contentRightX - dateWidth, topRowY - 2);

        // Middle Row: Big Crisp Time Display (e.g. 15:16:05 WIB)
        const timeRowY = cardY + Math.round(cardHeight * 0.58);
        const timeFontSize = Math.max(26, Math.round(cardHeight * 0.28));
        ctx.font = `800 ${timeFontSize}px system-ui, -apple-system, sans-serif`;
        ctx.fillStyle = '#ffffff';
        ctx.fillText(timeFormatted, contentX, timeRowY);

        const timeWidth = ctx.measureText(timeFormatted).width;
        ctx.font = `600 ${Math.round(timeFontSize * 0.48)}px system-ui, -apple-system, sans-serif`;
        ctx.fillStyle = '#94a3b8';
        ctx.fillText('WIB', contentX + timeWidth + 6, timeRowY - 2);

        // Right side of Time Row: Official Brand Seal
        ctx.font = `600 ${Math.max(11, Math.round(cardHeight * 0.085))}px system-ui, -apple-system, sans-serif`;
        ctx.fillStyle = '#64748b';
        const sealText = 'ARISE ATTENDANCE VERIFIED';
        const sealWidth = ctx.measureText(sealText).width;
        ctx.fillText(sealText, contentRightX - sealWidth, timeRowY);

        // Divider Hairline
        const dividerY = cardY + Math.round(cardHeight * 0.69);
        ctx.beginPath();
        ctx.moveTo(contentX, dividerY);
        ctx.lineTo(contentRightX, dividerY);
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.1)';
        ctx.lineWidth = 1;
        ctx.stroke();

        // Bottom Row: Volunteer Name (Left) & Event Location (Right)
        const bottomRowY = cardY + Math.round(cardHeight * 0.88);
        ctx.font = `700 ${Math.max(12, Math.round(cardHeight * 0.1))}px system-ui, -apple-system, sans-serif`;
        ctx.fillStyle = '#f8fafc';
        ctx.fillText(volunteerName, contentX, bottomRowY);

        ctx.font = `500 ${Math.max(11, Math.round(cardHeight * 0.088))}px system-ui, -apple-system, sans-serif`;
        ctx.fillStyle = '#cbd5e1';
        const eventText = `${eventTitle} • Lokasi Resmi`;
        const eventWidth = ctx.measureText(eventText).width;
        if (contentRightX - eventWidth > contentX + ctx.measureText(volunteerName).width + 16) {
            ctx.fillText(eventText, contentRightX - eventWidth, bottomRowY);
        }

        // Convert to Data URL & Assign to hidden input
        const dataUrl = canvasEl.toDataURL('image/jpeg', 0.94);
        if (base64Input) base64Input.value = dataUrl;

        // Also create a File and attach to input[type=file] via DataTransfer
        canvasEl.toBlob((blob) => {
            if (blob && fileInput) {
                const file = new File([blob], `attendance_${Date.now()}.jpg`, { type: 'image/jpeg' });
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
            }
        }, 'image/jpeg', 0.94);

        // Show captured image preview and hide live video
        previewEl.src = dataUrl;
        previewEl.classList.remove('hidden');
        videoEl.classList.add('hidden');

        // Hide live HTML overlays
        if (topOverlay) topOverlay.classList.add('hidden');
        if (bottomOverlay) bottomOverlay.classList.add('hidden');
        if (capturedTag) capturedTag.classList.remove('hidden');

        // Stop camera tracks to freeze / save battery
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
            currentStream = null;
        }

        // Switch button controls
        if (controlBefore) controlBefore.classList.add('hidden');
        if (controlAfter) controlAfter.classList.remove('hidden');
        if (submitLabel) submitLabel.textContent = `Kirim Bukti (${timeFormatted} WIB)`;

        isPhotoTaken = true;
    }

    // Retake Snapshot
    function retakeSnapshot() {
        isPhotoTaken = false;
        previewEl.classList.add('hidden');
        videoEl.classList.remove('hidden');
        if (base64Input) base64Input.value = '';
        if (fileInput) fileInput.value = '';
        if (nativeInput) nativeInput.value = '';

        // Restore live overlays
        if (topOverlay) topOverlay.classList.remove('hidden');
        if (bottomOverlay) bottomOverlay.classList.remove('hidden');
        if (capturedTag) capturedTag.classList.add('hidden');

        if (controlAfter) controlAfter.classList.add('hidden');
        if (controlBefore) controlBefore.classList.remove('hidden');

        startCamera();
    }

    // Detect mobile device
    const isMobileDevice = /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent);

    // Show startup UI based on device type
    document.addEventListener('DOMContentLoaded', () => {
        if (!videoEl) return;

        if (loadingEl) {
            loadingEl.classList.remove('hidden');

            if (isMobileDevice) {
                // Mobile: Show native camera as PRIMARY (no browser permission needed)
                loadingEl.innerHTML = `
                    <div class="flex flex-col items-center justify-center gap-3 p-4 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-red-500/20 text-red-400 flex items-center justify-center text-3xl">
                            <i class="fas fa-camera"></i>
                        </div>
                        <p class="text-sm font-bold text-white">Ambil Foto Absensi</p>
                        <p class="text-xs text-gray-400 max-w-xs leading-relaxed">Kamera HP akan terbuka langsung. Ambil foto selfie yang jelas di lokasi event.</p>
                        <button type="button" onclick="triggerNativeCamera()" class="mt-1 w-full py-3 px-4 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-red-500/25 transition flex items-center justify-center gap-2">
                            <i class="fas fa-camera"></i>
                            <span>Buka Kamera HP</span>
                        </button>
                        <button type="button" onclick="requestCameraWithGesture()" class="text-xs text-gray-500 hover:text-gray-300 transition flex items-center gap-1.5">
                            <i class="fas fa-video text-[10px]"></i>
                            <span>Gunakan kamera live di browser</span>
                        </button>
                    </div>
                `;
            } else {
                // Desktop: Try live camera stream (getUserMedia)
                loadingEl.innerHTML = `
                    <div class="flex flex-col items-center justify-center gap-3 p-4 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 text-white flex items-center justify-center text-2xl">
                            <i class="fas fa-video"></i>
                        </div>
                        <p class="text-sm font-bold text-white">Nyalakan Kamera</p>
                        <p class="text-xs text-gray-400 max-w-xs leading-relaxed">Klik di bawah lalu izinkan akses kamera jika muncul permintaan.</p>
                        <button type="button" onclick="requestCameraWithGesture()" class="mt-1 w-full py-3 px-4 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-red-500/25 transition flex items-center justify-center gap-2">
                            <i class="fas fa-video"></i>
                            <span>Nyalakan Kamera Web</span>
                        </button>
                        <button type="button" onclick="triggerNativeCamera()" class="text-xs text-gray-500 hover:text-gray-300 transition">
                            Atau upload foto dari file
                        </button>
                    </div>
                `;
            }
        }
    });

    // Cleanup camera when leaving page
    window.addEventListener('beforeunload', () => {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
    });
</script>
@endpush
@endif
@endsection
