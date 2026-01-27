@extends('layouts.public')

@section('title', 'Profile Saya - NOCIS')

@section('content')
<!-- Modern Profile Page -->
<div class="min-h-screen bg-white">
    
    <!-- 1. Notification Banner (Top) -->
    @php
        $percentage = $user->profile_completion;
    @endphp



    <!-- 2. Profile Header with Aurora Gradient -->
    <div class="relative bg-white pt-24 pb-12 overflow-hidden">
        
        <!-- Aurora Background Effect -->
        <div class="absolute top-0 left-0 right-0 h-[500px] bg-gradient-to-b from-red-50 via-white to-white z-0"></div>
        <div class="absolute top-[-100px] right-[-100px] w-[500px] h-[500px] bg-red-100/50 rounded-full blur-[100px] pointer-events-none mix-blend-multiply opacity-70"></div>
        <div class="absolute top-[-100px] left-[-100px] w-[400px] h-[400px] bg-blue-50/50 rounded-full blur-[100px] pointer-events-none mix-blend-multiply opacity-70"></div>

        <div class="container mx-auto px-4 relative z-10 max-w-7xl">
            
            <!-- Notification Banner (Bar Style) -->
            @if($percentage < 50)
            <div class="w-full bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 animate-fade-in-down">
                <div class="flex items-center gap-3 text-center sm:text-left">
                    <i class="fas fa-info-circle text-xl sm:text-lg opacity-90"></i>
                    <p class="text-sm font-medium leading-tight">
                        Get job offers by completing your profile (skills, portfolio, CV/Resume, and supporting documents).
                    </p>
                </div>
                <a href="{{ route('customer.settings') }}" class="whitespace-nowrap px-4 py-1.5 bg-white/20 hover:bg-white/30 rounded-md text-xs font-bold transition-colors border border-white/20">
                    Complete Now
                </a>
            </div>
            @endif

            <div class="flex flex-col lg:flex-row items-start gap-8 lg:gap-10">
                
                <!-- Avatar Section -->
                <div class="relative group flex-shrink-0 mx-auto lg:mx-0">
                    <div class="w-32 h-32 lg:w-40 lg:h-40 rounded-full p-1 bg-white ring-4 ring-gray-50 shadow-lg relative z-10">
                        <div class="w-full h-full rounded-full overflow-hidden relative group-hover:ring-4 ring-red-100 transition-all">
                            @if($user->profile && $user->profile->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold text-4xl">
                                    {{ strtoupper(substr($user->name ?? $user->username ?? 'U', 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('customer.settings') }}" class="absolute bottom-1 right-1 w-8 h-8 bg-gray-900 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-md z-20" title="Edit Photo">
                            <i class="fas fa-pen text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="flex-1 w-full text-center lg:text-left">
                    
                    <!-- Name & Role -->
                    <div class="mb-6 flex flex-col lg:flex-row justify-between items-center lg:items-start gap-4">
                        <div>
                            <div class="flex items-center justify-center lg:justify-start gap-2 mb-1">
                                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                                    {{ $user->name ?? $user->username }}
                                </h1>
                                <a href="{{ route('customer.settings') }}" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fas fa-pen text-xs"></i></a>
                            </div>
                            <p class="text-gray-500 font-medium text-lg">{{ $user->profile->professional_headline ?? 'Job Seeker / Candidate' }}</p>
                        </div>
                        
                        <!-- Percentage Badge (Minimal) -->
                        <div class="bg-white border border-gray-200 rounded-lg px-4 py-2 flex items-center gap-3 shadow-sm">
                            <div class="relative w-10 h-10 flex items-center justify-center">
                                <svg class="transform -rotate-90 w-10 h-10">
                                    <circle cx="20" cy="20" r="16" stroke="#f3f4f6" stroke-width="3" fill="transparent" />
                                    <circle cx="20" cy="20" r="16" stroke="{{ $percentage == 100 ? '#10B981' : '#EF4444' }}" stroke-width="3" fill="transparent" stroke-dasharray="100" stroke-dashoffset="{{ 100 - $percentage }}" class="transition-all duration-1000" />
                                </svg>
                                <span class="absolute text-[10px] font-bold text-gray-700">{{ number_format($percentage, 0) }}%</span>
                            </div>
                            <div class="text-left leading-tight">
                                <span class="block text-[10px] text-gray-400 font-bold uppercase">Completion Status</span>
                                <span class="block text-xs font-bold text-gray-800">{{ $percentage == 100 ? 'Completed' : 'Incomplete' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid lg:grid-cols-12 gap-8 border-t border-gray-100 pt-6">
                        
                        <!-- Summary (Left 8 cols) -->
                        <div class="lg:col-span-8 space-y-3">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-align-left text-red-500"></i>
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Summary</h3>
                            </div>
                            @if($user->profile && $user->profile->summary)
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ $user->profile->summary }}
                                </p>
                                <a href="{{ route('customer.settings') }}" class="inline-block text-xs font-bold text-red-600 hover:underline mt-1">See more</a>
                            @else
                                <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-6 text-center">
                                    <p class="text-gray-400 italic text-sm mb-3">No profile summary added yet.</p>
                                    <a href="{{ route('customer.settings') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-md text-xs font-bold hover:bg-red-700 transition-colors">
                                        <i class="fas fa-plus"></i> Add Summary
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Personal Info (Right 4 cols) -->
                        <div class="lg:col-span-4 space-y-5 lg:border-l lg:border-gray-100 lg:pl-8">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3 flex items-center gap-2">
                                    <i class="fas fa-user-shield text-red-500"></i> Personal Info
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Email</p>
                                        <p class="text-gray-900 font-medium text-sm break-all">{{ $user->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Location</p>
                                        <p class="text-gray-900 font-medium text-sm">{{ optional($user->profile)->address ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Phone Number</p>
                                        <p class="text-gray-900 font-medium text-sm">{{ optional($user->profile)->phone ? '+62 ' . $user->profile->phone : '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Date of Birth</p>
                                        <p class="text-gray-900 font-medium text-sm">{{ optional($user->profile)->date_of_birth ? \Carbon\Carbon::parse($user->profile->date_of_birth)->translatedFormat('d F Y') : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div> <!-- End of Header Wrapper -->

    <!-- 3. Body Content (Full Width) -->
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- CV/Resume Section -->
        <section class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">My CV/Resume</h2>
                <button onclick="triggerCvUpload()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all" id="topUploadBtn">
                    @if($user->profile && $user->profile->cv_file)
                        <i class="fas fa-sync-alt"></i> Update CV
                    @else
                        <i class="fas fa-upload"></i> Upload
                    @endif
                </button>
            </div>

            @if($user->profile && $user->profile->cv_file)
                <div class="bg-gray-100 rounded-xl overflow-hidden border border-gray-200 h-[600px] relative group">
                    <iframe src="{{ asset('storage/' . $user->profile->cv_file) }}" class="w-full h-full" frameborder="0"></iframe>
                    
                    <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity p-2 bg-black/50 rounded-lg backdrop-blur-sm">
                        <a href="{{ asset('storage/' . $user->profile->cv_file) }}" target="_blank" class="p-2 text-white hover:text-red-400 transition-colors" title="Open in New Tab">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a href="{{ asset('storage/' . $user->profile->cv_file) }}" download class="p-2 text-white hover:text-green-400 transition-colors" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-12 text-center hover:bg-gray-100 transition-colors cursor-pointer group">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-pdf text-3xl text-gray-400 group-hover:text-red-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">No CV uploaded yet</h3>
                    <p class="text-gray-500 mb-6">Upload your CV so recruiters can see your full qualifications.</p>
                    <button onclick="triggerCvUpload()" class="inline-block bg-red-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5" id="mainUploadBtn">
                        Upload CV Now
                    </button>
                </div>
            @endif

        </section>
                    {{-- =========================
            Certificates Section
            ========================= --}}
            <section class="mb-12 border-t border-gray-200 pt-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Certificates</h2>
                </div>

                {{-- Form Upload Multi Sertifikat --}}
                <form id="certificateForm" class="space-y-4">
                    <div id="certificateRows" class="space-y-4"></div>

                    <div class="flex items-center gap-3">
                        <button type="button" onclick="submitCertificates()"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all">
                            Save Certificates
                        </button>

                        <p class="text-xs text-gray-500">
                            Allowed: PDF/JPG/PNG (max 2MB). You can upload more than one certificate.
                        </p>
                    </div>
                </form>

                {{-- List Sertifikat yang sudah diupload --}}
                <div class="mt-8">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3 flex items-center gap-2">
                        <i class="fas fa-certificate text-red-500"></i> Uploaded Certificates
                    </h3>

                    @if($user->certificates && $user->certificates->count())
                    <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl">
                        <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                            <th class="text-left p-3 font-bold">Title</th>
                            <th class="text-left p-3 font-bold">Date</th>
                            <th class="text-left p-3 font-bold">Stage</th>
                            <th class="text-left p-3 font-bold">File</th>
                            <th class="text-right p-3 font-bold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($user->certificates as $cert)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-semibold text-gray-900">
                                {{ $cert->title ?? '-' }}
                                </td>

                                <td class="p-3 text-gray-700">
                                {{ $cert->event_date ? \Carbon\Carbon::parse($cert->event_date)->format('d M Y') : '-' }}
                                </td>

                                <td class="p-3">
                                <span class="inline-flex text-[10px] font-bold px-2 py-1 rounded bg-gray-100 text-gray-700 uppercase">
                                    {{ strtoupper(str_replace('_',' ', $cert->stage)) }}
                                </span>
                                </td>

                                <td class="p-3">
                                <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank"
                                    class="text-red-600 font-bold hover:underline">
                                   @php
                                    $ext = pathinfo($cert->original_name ?? $cert->file_path, PATHINFO_EXTENSION);
                                    $displayName = ($cert->title ? \Illuminate\Support\Str::slug($cert->title, '-') : 'certificate') . ($ext ? '.'.$ext : '');
                                    @endphp

                                    <span class="font-semibold text-gray-900">{{ $displayName }}</span>
                                </a>
                                </td>

                                <td class="p-3">
                                <div class="flex justify-end gap-2">
                                    {{-- Download --}}
                                    <a href="{{ asset('storage/' . $cert->file_path) }}" download
                                    class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center"
                                    title="Download">
                                    <i class="fas fa-download"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <button type="button"
                                            onclick="deleteCertificate({{ $cert->id }})"
                                            class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center"
                                            title="Delete">
                                    <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                    @else
                    <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center">
                        <p class="text-gray-500 font-medium">No certificates uploaded yet.</p>
                    </div>
                    @endif

                </div>
            </section>


        <!-- Hidden File Input -->
        <input type="file" id="cvFileInput" class="hidden" accept=".pdf,.doc,.docx" onchange="handleCvUpload(this)">

        <script>
            function triggerCvUpload() {
                document.getElementById('cvFileInput').click();
            }

            function handleCvUpload(input) {
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    const formData = new FormData();
                    formData.append('cv_file', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    // Show loading state
                    const originalText = document.getElementById('mainUploadBtn') ? document.getElementById('mainUploadBtn').innerHTML : '';
                    if(document.getElementById('mainUploadBtn')) {
                        document.getElementById('mainUploadBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
                        document.getElementById('mainUploadBtn').disabled = true;
                    }
                    if(document.getElementById('topUploadBtn')) {
                        document.getElementById('topUploadBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                        document.getElementById('topUploadBtn').disabled = true;
                    }

                    fetch('{{ route("customer.profile.upload-cv") }}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success toast or alert
                            // Reload to show the new CV
                            window.location.reload();
                        } else {
                            alert('Upload failed: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred during upload.');
                    })
                    .finally(() => {
                        // Reset buttons if needed (though reload happens usually)
                        if(document.getElementById('mainUploadBtn')) {
                            document.getElementById('mainUploadBtn').innerHTML = originalText;
                            document.getElementById('mainUploadBtn').disabled = false;
                        }
                    });
                }
            }
        </script>

        <!-- Social Media Section -->
        <section class="border-t border-gray-200 pt-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Social Accounts</h2>
                <button onclick="openSocialMediaModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $socials = [
                        ['key' => 'linkedin', 'icon' => 'fab fa-linkedin', 'color' => 'text-gray-900', 'bg' => 'bg-gray-100', 'label' => 'LinkedIn'],
                        ['key' => 'instagram', 'icon' => 'fab fa-instagram', 'color' => 'text-gray-900', 'bg' => 'bg-gray-100', 'label' => 'Instagram'],
                        ['key' => 'tiktok', 'icon' => 'fab fa-tiktok', 'color' => 'text-gray-900', 'bg' => 'bg-gray-100', 'label' => 'TikTok'],
                        ['key' => 'twitter', 'icon' => 'fab fa-twitter', 'color' => 'text-gray-900', 'bg' => 'bg-gray-100', 'label' => 'Twitter/X'],
                        ['key' => 'website', 'icon' => 'fas fa-globe', 'color' => 'text-gray-900', 'bg' => 'bg-gray-100', 'label' => 'Website']
                    ];
                    $hasSocial = false;
                @endphp

                @foreach($socials as $social)
                    @if($user->profile && $user->profile->{$social['key']})
                        @php $hasSocial = true; @endphp
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 {{ $social['bg'] }} rounded-lg flex items-center justify-center group-hover:bg-red-50 transition-colors">
                                    <i class="{{ $social['icon'] }} {{ $social['color'] }} text-xl group-hover:text-red-600 transition-colors"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase">{{ $social['label'] }}</p>
                                    <p class="font-bold text-gray-900 truncate max-w-[150px]">{{ $user->profile->{$social['key']} }}</p>
                                </div>
                            </div>
                            <button class="text-gray-300 hover:text-red-600 transition-colors opacity-0 group-hover:opacity-100">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endif
                @endforeach

                @if(!$hasSocial)
                    <div class="col-span-full py-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <p class="text-gray-500 font-medium">No social media accounts added yet.</p>
                    </div>
                @endif
            </div>
        </section>
    </div> <!-- End of Body Content -->

<!-- Social Media Modal -->
<div id="socialMediaModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeSocialMediaModal()"></div>

    <!-- Modal Panel -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg transform rounded-2xl bg-white p-6 text-left shadow-xl transition-all">
            
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold text-gray-900" id="modal-title">Edit Social Links</h3>
                <button type="button" onclick="closeSocialMediaModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="socialMediaForm" onsubmit="event.preventDefault(); submitSocialMedia();" class="space-y-4">
                @php
                    $socialsForm = [
                        ['key' => 'linkedin', 'label' => 'LinkedIn', 'icon' => 'fab fa-linkedin', 'placeholder' => 'https://linkedin.com/in/username'],
                        ['key' => 'instagram', 'label' => 'Instagram', 'icon' => 'fab fa-instagram', 'placeholder' => 'https://instagram.com/username'],
                        ['key' => 'tiktok', 'label' => 'TikTok', 'icon' => 'fab fa-tiktok', 'placeholder' => 'https://tiktok.com/@username'],
                        ['key' => 'twitter', 'label' => 'Twitter/X', 'icon' => 'fab fa-twitter', 'placeholder' => 'https://twitter.com/username'],
                        ['key' => 'website', 'label' => 'Website', 'icon' => 'fas fa-globe', 'placeholder' => 'https://yourwebsite.com']
                    ];
                @endphp

                @foreach($socialsForm as $social)
                <div>
                    <label for="social_{{ $social['key'] }}" class="block text-sm font-bold text-gray-700 mb-1 flex items-center gap-2">
                        <i class="{{ $social['icon'] }} text-gray-400 w-5 text-center"></i> {{ $social['label'] }}
                    </label>
                    <div class="relative">
                        <input type="url" 
                               id="social_{{ $social['key'] }}" 
                               name="{{ $social['key'] }}" 
                               value="{{ $user->profile->{$social['key']} ?? '' }}"
                               class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all text-sm placeholder-gray-400"
                               placeholder="{{ $social['placeholder'] }}">
                    </div>
                </div>
                @endforeach

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeSocialMediaModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition-all text-sm">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5 text-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openSocialMediaModal() {
        document.getElementById('socialMediaModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSocialMediaModal() {
        document.getElementById('socialMediaModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function submitSocialMedia() {
        const form = document.getElementById('socialMediaForm');
        const updateButton = form.querySelector('button[type="submit"]');
        const originalText = updateButton.innerText;
        
        updateButton.disabled = true;
        updateButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

        const formData = new FormData(form);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("customer.profile.update-social") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Determine if we need to reload or just show success
                // Since the page shows the social links, it's safer to reload to see updates
                window.location.reload();
            } else {
                alert('Update failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred during update.');
        })
        .finally(() => {
            updateButton.disabled = false;
            updateButton.innerHTML = originalText;
        });
    }
</script>
<script>
let certIndex = 0;

function addCertificateRow() {
  const container = document.getElementById('certificateRows');

  container.insertAdjacentHTML('beforeend', `
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center border border-gray-200 p-4 rounded-xl bg-white">
      
      <div class="md:col-span-4">
        <label class="block text-xs font-bold text-gray-500 mb-1">Title (Nama Lomba/Sertifikat)</label>
        <input type="text"
          name="certificates[${certIndex}][title]"
          class="w-full border border-gray-300 rounded-lg p-2 text-sm"
          placeholder="Contoh: Lomba UI/UX Nasional"
          required>
      </div>

      <div class="md:col-span-2">
        <label class="block text-xs font-bold text-gray-500 mb-1">Date</label>
        <input type="date"
          name="certificates[${certIndex}][event_date]"
          class="w-full border border-gray-300 rounded-lg p-2 text-sm"
          required>
      </div>

      <div class="md:col-span-2">
        <label class="block text-xs font-bold text-gray-500 mb-1">Stage</label>
        <select name="certificates[${certIndex}][stage]"
          class="w-full border border-gray-300 rounded-lg p-2 text-sm"
          required>
          <option value="">Select stage</option>
          <option value="province">Province</option>
          <option value="national">National</option>
          <option value="asean_sea">ASEAN / South East Asia</option>
          <option value="asia">Asia</option>
          <option value="world">World</option>
        </select>
      </div>

      <div class="md:col-span-3">
        <label class="block text-xs font-bold text-gray-500 mb-1">Certificate File</label>
        <input type="file"
          name="certificates[${certIndex}][file]"
          accept=".pdf,.jpg,.jpeg,.png"
          class="w-full border border-gray-300 rounded-lg p-2 text-sm"
          required>
      </div>

      <div class="md:col-span-1 flex md:justify-end">
      </div>
    </div>
  `);

  certIndex++;
}

document.addEventListener('DOMContentLoaded', () => addCertificateRow());
</script>
<script>
function openCertDetail(id){
  fetch(`{{ url('/dashboard/profile/certificates') }}/${id}`, {
    headers: { 'Accept': 'application/json' }
  })
  .then(r => r.json())
  .then(d => {
    alert(
      `Title: ${d.title}\nDate: ${d.event_date}\nStage: ${String(d.stage).toUpperCase()}\nFile: ${d.file_name}`
    );
  });
}

function deleteCertificate(id){
  if(!confirm('Delete this certificate?')) return;

  fetch(`{{ url('/dashboard/profile/certificates') }}/${id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json'
    }
  })
  .then(r => r.json().catch(()=>({})))
  .then(d => {
    if(d.success) location.reload();
    else alert(d.message || 'Delete failed');
  });
}
</script>
<script>
window.submitCertificates = function () {
  const form = document.getElementById('certificateForm');
  if (!form) return alert('certificateForm not found');

  const fileInputs = form.querySelectorAll('input[type="file"]');
  if (!fileInputs.length) return alert('Add at least one certificate first.');

  const formData = new FormData(form);
  formData.append('_token', '{{ csrf_token() }}');

  // DEBUG (biar keliatan kirim apa)
  console.log('Submitting certificates...');
  for (const [k, v] of formData.entries()) {
    console.log(k, v instanceof File ? v.name : v);
  }

  fetch('{{ route("customer.profile.upload-certificates") }}', {
    method: 'POST',
    body: formData,
    headers: { 'Accept': 'application/json' }
  })
  .then(async (res) => {
    const text = await res.text();
    console.log('STATUS', res.status);
    console.log('RAW', text);

    let data = {};
    try { data = JSON.parse(text); } catch(e) {}

    if (!res.ok) {
      if (data?.errors) {
        const firstKey = Object.keys(data.errors)[0];
        return alert(data.errors[firstKey][0]);
      }
      return alert(data.message || 'Upload failed');
    }

    if (data.success) window.location.reload();
    else alert(data.message || 'Upload failed');
  })
  .catch(err => {
    console.error(err);
    alert('An error occurred during upload.');
  });
}
</script>
<script>
window.submitCertificates = function () {
  const form = document.getElementById('certificateForm');
  if (!form) return alert('certificateForm not found');

  const fileInputs = form.querySelectorAll('input[type="file"]');
  if (!fileInputs.length) return alert('Add at least one certificate first.');

  const formData = new FormData(form);
  formData.append('_token', '{{ csrf_token() }}');

  // DEBUG (biar keliatan kirim apa)
  console.log('Submitting certificates...');
  for (const [k, v] of formData.entries()) {
    console.log(k, v instanceof File ? v.name : v);
  }

  fetch('{{ route("customer.profile.upload-certificates") }}', {
    method: 'POST',
    body: formData,
    headers: { 'Accept': 'application/json' }
  })
  .then(async (res) => {
    const text = await res.text();
    console.log('STATUS', res.status);
    console.log('RAW', text);

    let data = {};
    try { data = JSON.parse(text); } catch(e) {}

    if (!res.ok) {
      if (data?.errors) {
        const firstKey = Object.keys(data.errors)[0];
        return alert(data.errors[firstKey][0]);
      }
      return alert(data.message || 'Upload failed');
    }

    if (data.success) window.location.reload();
    else alert(data.message || 'Upload failed');
  })
  .catch(err => {
    console.error(err);
    alert('An error occurred during upload.');
  });
}
</script>

@endsection