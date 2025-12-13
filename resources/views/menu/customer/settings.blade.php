@extends('layouts.public')

@section('title', 'Settings - NOCIS')

@section('content')
<!-- Modern Settings Page -->
<div class="relative bg-white pt-24 pb-12 overflow-hidden min-h-screen">
    
    <!-- Aurora Background Effect -->
    <div class="absolute top-0 left-0 right-0 h-[500px] bg-gradient-to-b from-red-50 via-white to-white z-0"></div>
    <div class="absolute top-[-100px] right-[-100px] w-[500px] h-[500px] bg-red-100/50 rounded-full blur-[100px] pointer-events-none mix-blend-multiply opacity-70"></div>
    <div class="absolute top-[-100px] left-[-100px] w-[400px] h-[400px] bg-blue-50/50 rounded-full blur-[100px] pointer-events-none mix-blend-multiply opacity-70"></div>

    <div class="container mx-auto px-4 relative z-10 max-w-7xl">
        <div class="mb-8 text-center md:text-left">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight mb-2">Account Settings</h1>
            <p class="text-gray-500">Manage your profile information and preferences.</p>
        </div>
        
        <!-- Settings Container -->
        <div class="bg-white rounded-2xl shadow-xl shadow-red-500/5 border border-gray-100 overflow-hidden">
            
            <!-- Modern Pill Tabs -->
            <div class="border-b border-gray-100 p-2">
                <nav class="flex flex-wrap gap-2" aria-label="Tabs">
                    <button onclick="showSettingsTab('basic')" class="tab-button flex-1 md:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 active-tab" data-tab="basic">
                        <i class="fas fa-id-card mr-2"></i>Basic Info
                    </button>
                    <button onclick="showSettingsTab('personal')" class="tab-button flex-1 md:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200" data-tab="personal">
                        <i class="fas fa-user-shield mr-2"></i>Personal Data
                    </button>
                    <button onclick="showSettingsTab('preferences')" class="tab-button flex-1 md:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200" data-tab="preferences">
                        <i class="fas fa-sliders-h mr-2"></i>Preferences
                    </button>
                </nav>
            </div>

            <!-- Tab Content Area -->
            <div class="p-6 md:p-8">
                
                <!-- Basic Information Tab -->
                <div id="basic-tab" class="tab-content transition-opacity duration-300">
                    
                    <!-- Photo Upload Section -->
                    <form action="{{ route('customer.settings.photo') }}" method="POST" enctype="multipart/form-data" class="mb-10 border-b border-gray-100 pb-10">
                        @csrf
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-full p-1 bg-white border-4 border-gray-100 shadow-lg overflow-hidden relative">
                                    @if($user->profile && $user->profile->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold text-4xl">
                                            {{ strtoupper(substr($user->name ?? $user->username ?? 'U', 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                <label for="profile_photo" class="absolute bottom-1 right-1 w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 shadow-lg cursor-pointer transition-transform hover:scale-110 border-2 border-white">
                                    <i class="fas fa-camera text-xs"></i>
                                </label>
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png" class="hidden" onchange="openCropperModal(this)">
                            </div>
                            
                            <div class="text-center md:text-left flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Profile Photo</h3>
                                <p class="text-sm text-gray-500 mb-4">Upload a professional photo (JPG/PNG, max 2MB). Click the camera icon to update.</p>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Form -->
                    <form action="{{ route('customer.settings.update') }}" method="POST">
                        @csrf
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-align-left text-red-500"></i>
                                <label for="summary" class="text-sm font-bold text-gray-900 uppercase tracking-wide">
                                    Professional Summary
                                </label>
                            </div>
                            <textarea id="summary"
                                      name="summary"
                                      rows="5"
                                      placeholder="Write a brief professional summary about yourself..."
                                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium text-gray-800 placeholder-gray-400">{{ old('summary', optional($user->profile)->summary ?? '') }}</textarea>
                            @error('summary')
                                <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-2 text-right">Briefly describe your experience and skills</p>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Personal Data Tab -->
                <div id="personal-tab" class="tab-content hidden transition-opacity duration-300">
                    <form action="{{ route('customer.settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                            <!-- Username -->
                            <div class="md:col-span-2">
                                <label for="username" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Username</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text"
                                           id="username"
                                           name="username"
                                           value="{{ old('username', $user->username ?? '') }}"
                                           required
                                           class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1.5 ml-1">Unique identifier for your account.</p>
                                @error('username')
                                    <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Name  -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name ?? '') }}"
                                           required
                                           class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1.5 ml-1">Name for your account.</p>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email ?? '') }}"
                                           required
                                           class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1.5 ml-1">Changes may require verification via email.</p>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Professional Headline -->
                            <div class="md:col-span-2">
                                <label for="professional_headline" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Professional Headline</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-briefcase text-gray-400"></i>
                                    </div>
                                    <input type="text"
                                           id="professional_headline"
                                           name="professional_headline"
                                           value="{{ old('professional_headline', optional($user->profile)->professional_headline ?? '') }}"
                                           placeholder="e.g. Senior Software Engineer, Event Specialist"
                                           class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1.5 ml-1">Appears below your name on your profile.</p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Phone Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none border-r border-gray-200 pr-3 mr-3 bg-gray-100 rounded-l-xl">
                                        <span class="text-gray-500 text-sm font-bold">🇮🇩 +62</span>
                                    </div>
                                    <input type="tel"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', optional($user->profile)->phone ?? '') }}"
                                           placeholder="81234567890"
                                           class="w-full pl-24 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                </div>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- City -->
                            <div>
                                <label for="address" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Location / City</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <input type="text"
                                           id="address"
                                           name="address"
                                           value="{{ old('address', optional($user->profile)->address ?? '') }}"
                                           placeholder="e.g. Jakarta Selatan"
                                           class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                </div>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="md:col-span-2">
                                <label for="date_of_birth" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Date of Birth</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                    <input type="date"
                                           id="date_of_birth"
                                           name="date_of_birth"
                                           value="{{ old('date_of_birth', optional($user->profile)->date_of_birth ? optional($user->profile)->date_of_birth->format('Y-m-d') : '') }}"
                                           class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium text-gray-800">
                                </div>
                                @error('date_of_birth')
                                    <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-4 border-t border-gray-100 pt-6">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Preferences Tab -->
                <div id="preferences-tab" class="tab-content hidden transition-opacity duration-300">
                    <form action="{{ route('customer.settings.update') }}" method="POST">
                        @csrf
                        
                        <!-- Education Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-graduation-cap text-red-500"></i> Education
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="last_education" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Last Education</label>
                                    <select id="last_education" name="last_education" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                        <option value="">Select Level</option>
                                        @foreach(['High School', 'Diploma', 'Bachelor (S1)', 'Master (S2)', 'Doctorate (S3)'] as $edu)
                                            <option value="{{ $edu }}" {{ old('last_education', optional($user->profile)->last_education ?? '') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="graduation_year" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Graduation Year</label>
                                    <input type="number" id="graduation_year" name="graduation_year" 
                                           value="{{ old('graduation_year', optional($user->profile)->graduation_year ?? '') }}"
                                           placeholder="YYYY" min="1900" max="{{ date('Y') + 10 }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                </div>
                                <div>
                                    <label for="university" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">University / School</label>
                                    <input type="text" id="university" name="university" 
                                           value="{{ old('university', optional($user->profile)->university ?? '') }}"
                                           placeholder="e.g. Universitas Indonesia"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                </div>
                                <div>
                                    <label for="field_of_study" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Field of Study</label>
                                    <input type="text" id="field_of_study" name="field_of_study" 
                                           value="{{ old('field_of_study', optional($user->profile)->field_of_study ?? '') }}"
                                           placeholder="e.g. Psychology"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-8">

                        <!-- Skills & Languages -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-tools text-red-500"></i> Skills & Languages
                            </h3>
                            <div class="space-y-6">
                                <!-- Skills -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Professional Skills</label>
                                    
                                    <!-- Hidden Input for Form Submission -->
                                    <input type="hidden" id="skills_input" name="skills" value="{{ old('skills', optional($user->profile)->skills ?? '') }}">
                                    
                                    <!-- Tag Container -->
                                    <div id="skills_tags" class="flex flex-wrap gap-2 mb-3">
                                        <!-- Tags will be injected here via JS -->
                                    </div>

                                    <!-- Dropdown Selector -->
                                    <div class="relative">
                                        <select id="skills_select" onchange="addTag('skills', this.value); this.value='';" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium appearance-none">
                                            <option value="">+ Add Skill</option>
                                            @foreach(['Public Speaking', 'Time Management', 'Leadership', 'Teamwork', 'Problem Solving', 'Data Analysis', 'Event Management', 'First Aid', 'Photography', 'Social Media'] as $skill)
                                                <option value="{{ $skill }}">{{ $skill }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1.5 ml-1">Select multiple skills to add to your profile.</p>
                                </div>

                                <!-- Languages -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Languages Spoken</label>
                                    
                                    <input type="hidden" id="languages_input" name="languages" value="{{ old('languages', optional($user->profile)->languages ?? '') }}">
                                    
                                    <div id="languages_tags" class="flex flex-wrap gap-2 mb-3">
                                        <!-- Tags injected via JS -->
                                    </div>

                                    <div class="relative">
                                        <select id="languages_select" onchange="addTag('languages', this.value); this.value='';" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium appearance-none">
                                            <option value="">+ Add Language</option>
                                            @foreach(['Indonesian', 'English', 'Mandarin', 'Japanese', 'Korean', 'Arabic', 'French', 'German', 'Spanish'] as $lang)
                                                <option value="{{ $lang }}">{{ $lang }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize tags on load
    document.addEventListener('DOMContentLoaded', function() {
        ['skills', 'languages'].forEach(type => {
            const input = document.getElementById(type + '_input');
            if (input && input.value) {
                const values = input.value.split(',').filter(v => v.trim() !== '');
                values.forEach(val => renderTag(type, val.trim()));
            }
        });
    });

    function addTag(type, value) {
        if (!value) return;

        const input = document.getElementById(type + '_input');
        let currentValues = input.value ? input.value.split(',') : [];
        currentValues = currentValues.map(v => v.trim()).filter(v => v !== '');

        // Prevent duplicates
        if (currentValues.includes(value)) return;

        currentValues.push(value);
        input.value = currentValues.join(',');
        
        renderTag(type, value);
    }

    function removeTag(type, value) {
        const input = document.getElementById(type + '_input');
        let currentValues = input.value ? input.value.split(',') : [];
        currentValues = currentValues.map(v => v.trim()).filter(v => v !== value);
        
        input.value = currentValues.join(',');
        
        // Remove element from DOM
        const tag = document.querySelector(`[data-tag-id="${type}-${value.replace(/\s+/g, '-')}"]`);
        if (tag) tag.remove();
    }

    function renderTag(type, value) {
        const container = document.getElementById(type + '_tags');
        const tagId = `${type}-${value.replace(/\s+/g, '-')}`;
        
        // Check if already rendered (just in case)
        if (document.querySelector(`[data-tag-id="${tagId}"]`)) return;

        const tag = document.createElement('div');
        tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100 animate-fade-in-up';
        tag.setAttribute('data-tag-id', tagId);
        tag.innerHTML = `
            ${value}
            <button type="button" onclick="removeTag('${type}', '${value}')" class="ml-2 text-red-400 hover:text-red-700 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(tag);
    }
</script>

<style>
    .active-tab {
        background-color: #ef4444; /* brand red */
        color: white;
    }
    .tab-button:not(.active-tab) {
        background-color: #f3f4f6; /* gray-100 */
        color: #4b5563; /* gray-600 */
    }
    .tab-button:not(.active-tab):hover {
        background-color: #e5e7eb; /* gray-200 */
    }
</style>

<script>
    function showSettingsTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
            content.classList.remove('opacity-100');
            content.classList.add('opacity-0');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active-tab');
        });
        
        // Show selected tab content
        const selectedContent = document.getElementById(tabName + '-tab');
        selectedContent.classList.remove('hidden');
        // Small timeout to allow display:block to apply before opacity transition
        setTimeout(() => {
            selectedContent.classList.remove('opacity-0');
            selectedContent.classList.add('opacity-100');
        }, 10);
        
        
        // Add active class to selected tab button
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active-tab');
    }
    
    // Initialize tab based on URL parameter or default to basic
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'basic';
        if (document.getElementById(tab + '-tab')) {
            showSettingsTab(tab);
        } else {
            showSettingsTab('basic');
        }
    });
</script>
@endsection

<!-- Cropper Modal (Moved to root level for proper Z-Index) -->
<div id="cropperModal" class="relative z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-xl font-bold text-gray-900">Crop Profile Photo</h3>
                        <button type="button" onclick="closeCropperModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <div class="relative w-full h-[300px] bg-gray-100 rounded-xl overflow-hidden mb-6">
                        <img id="cropperImage" src="" alt="To Crop" class="max-w-full">
                    </div>

                    <!-- Zoom Slider -->
                    <div class="mb-4">
                        <label for="zoomRange" class="flex justify-between text-sm font-bold text-gray-700 mb-2">
                            <span>Zoom</span>
                            <span id="zoomValue">100%</span>
                        </label>
                        <input type="range" id="zoomRange" min="0.1" max="3" step="0.1" value="1" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600">
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="closeCropperModal()" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="button" onclick="saveCroppedImage()" class="px-6 py-2.5 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">
                            Save Photo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cropper CSS/JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    let cropper;
    const image = document.getElementById('cropperImage');
    const zoomRange = document.getElementById('zoomRange');
    const zoomValue = document.getElementById('zoomValue');

    function openCropperModal(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                image.src = e.target.result;
                document.getElementById('cropperModal').classList.remove('hidden');
                
                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false,
                    zoomOnWheel: false, // Prevent scroll hijacking
                    ready: function () {
                        // Initialize zoom slider
                        zoomRange.value = 1;
                        zoomValue.innerText = '100%';
                    },
                    zoom: function (e) {
                        // Sync zoom slider
                        const ratio = e.detail.ratio;
                        zoomRange.value = ratio;
                        zoomValue.innerText = Math.round(ratio * 100) + '%';
                    }
                });
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function closeCropperModal() {
        document.getElementById('cropperModal').classList.add('hidden');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        document.getElementById('profile_photo').value = '';
    }

    // Zoom Slider Event
    zoomRange.addEventListener('input', function() {
        if (cropper) {
            cropper.zoomTo(parseFloat(this.value));
            zoomValue.innerText = Math.round(this.value * 100) + '%';
        }
    });

    function saveCroppedImage() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
            fillColor: '#fff'
        });

        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('profile_photo', blob, 'profile_photo.jpg');
            formData.append('_token', '{{ csrf_token() }}');

            // Show loading state on button
            const saveBtn = document.querySelector('button[onclick="saveCroppedImage()"]');
            const originalText = saveBtn.innerText;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            fetch('{{ route("customer.settings.photo") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload(); 
                } else {
                    throw new Error('Upload failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to upload photo. Please try again.');
                saveBtn.innerText = originalText;
                saveBtn.disabled = false;
            });
        }, 'image/jpeg', 0.9);
    }
</script>