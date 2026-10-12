@extends('layouts.public')

@section('title', 'Absensi: ' . $event->title . ' - NOCIS')

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

                    <form id="attendance-form" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="photo_base64" id="photo_base64">
                        <input type="file" name="photo" id="photo_file" class="hidden" accept="image/*">

                        <!-- LIVE CAMERA VIEWFINDER & PREVIEW BOX -->
                        <div class="relative w-full max-w-sm mx-auto rounded-3xl overflow-hidden shadow-2xl bg-slate-900 border-4 border-white/80 aspect-[3/4] mb-5 flex items-center justify-center">
                            
                            <!-- Shutter Flash Effect -->
                            <div id="shutter-flash" class="absolute inset-0 bg-white pointer-events-none opacity-0 z-30"></div>

                            <!-- Live Video Stream -->
                            <video id="camera-stream" autoplay playsinline muted class="w-full h-full object-cover"></video>

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
                                <p class="text-xs text-gray-400 mt-1 max-w-xs">Pastikan Anda telah mengizinkan akses kamera di browser Anda.</p>
                                <button type="button" onclick="startCamera()" class="mt-4 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-xl text-xs font-bold text-white transition">
                                    Izinkan / Coba Lagi
                                </button>
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

    // Helper: Draw Rounded Rectangle
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

    // Start Live Camera Stream
    async function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            if (loadingEl) {
                loadingEl.innerHTML = `
                    <div class="p-4 text-center">
                        <i class="fas fa-exclamation-triangle text-amber-400 text-3xl mb-2"></i>
                        <p class="text-sm font-bold">Kamera Web Tidak Didukung</p>
                        <p class="text-xs text-gray-400 mt-1 mb-4">Gunakan browser modern (Chrome/Edge/Safari).</p>
                    </div>
                `;
            }
            return;
        }

        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }

        try {
            if (loadingEl) loadingEl.classList.remove('hidden');

            const constraints = {
                video: {
                    facingMode: currentFacingMode,
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            };

            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            currentStream = stream;
            videoEl.srcObject = stream;

            videoEl.onloadedmetadata = () => {
                videoEl.play();
                if (loadingEl) loadingEl.classList.add('hidden');
            };
        } catch (err) {
            console.error('Camera error:', err);
            if (loadingEl) {
                loadingEl.innerHTML = `
                    <div class="p-4 text-center">
                        <i class="fas fa-video-slash text-red-400 text-3xl mb-2"></i>
                        <p class="text-sm font-bold">Akses Kamera Ditolak / Tidak Ditemukan</p>
                        <p class="text-xs text-gray-400 mt-1 mb-4">Harap izinkan akses kamera pada ikon gembok/kamera di address bar browser Anda.</p>
                        <button type="button" onclick="startCamera()" class="px-4 py-2 bg-red-600 rounded-xl text-xs font-bold text-white shadow">
                            Coba Lagi
                        </button>
                    </div>
                `;
            }
        }
    }

    // Toggle Front / Back Camera
    function toggleCameraFacing() {
        currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
        startCamera();
    }

    // Take Snapshot and Burn Clean, Ultra-Elegant Corporate Glass Badge
    function takeSnapshot() {
        if (!videoEl || !videoEl.videoWidth) {
            alert('Kamera belum siap. Harap tunggu sebentar.');
            return;
        }

        // Shutter flash effect
        if (flashEl) {
            flashEl.classList.add('flash-active');
            setTimeout(() => flashEl.classList.remove('flash-active'), 300);
        }

        const width = videoEl.videoWidth || 1280;
        const height = videoEl.videoHeight || 720;

        canvasEl.width = width;
        canvasEl.height = height;
        const ctx = canvasEl.getContext('2d');

        // 1. Draw raw camera frame
        ctx.drawImage(videoEl, 0, 0, width, height);

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

        // 3. Floating Modern Frosted Glass Card Geometry
        const paddingX = Math.round(width * 0.035);
        const paddingY = Math.round(height * 0.035);
        const cardWidth = width - (paddingX * 2);
        const cardHeight = Math.min(Math.round(height * 0.24), 160);
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

        // 5. Card Inner Contents (Clean Typography, No Emojis)
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
        const sealText = 'NOCIS ATTENDANCE VERIFIED';
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

        // CRITICAL: Hide live HTML overlays so they never overlap the captured photo!
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

        // Restore live overlays
        if (topOverlay) topOverlay.classList.remove('hidden');
        if (bottomOverlay) bottomOverlay.classList.remove('hidden');
        if (capturedTag) capturedTag.classList.add('hidden');

        if (controlAfter) controlAfter.classList.add('hidden');
        if (controlBefore) controlBefore.classList.remove('hidden');

        startCamera();
    }

    // Initialize camera on page load if form exists
    document.addEventListener('DOMContentLoaded', () => {
        if (videoEl) {
            startCamera();
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
