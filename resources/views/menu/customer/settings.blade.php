@extends('layouts.public')
@section('title', 'Settings - NOCIS')
@section('content')
<div class="relative bg-white pt-24 pb-12 overflow-hidden min-h-screen">
    <div class="absolute top-0 left-0 right-0 h-[500px] bg-gradient-to-b from-red-50 via-white to-white z-0"></div>
    <div class="absolute top-[-100px] right-[-100px] w-[500px] h-[500px] bg-red-100/50 rounded-full blur-[100px] pointer-events-none mix-blend-multiply opacity-70"></div>
    <div class="absolute top-[-100px] left-[-100px] w-[400px] h-[400px] bg-blue-50/50 rounded-full blur-[100px] pointer-events-none mix-blend-multiply opacity-70"></div>
    <div class="container mx-auto px-4 relative z-10 max-w-7xl">
        <div class="mb-8 text-center md:text-left">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight mb-2">Account Settings</h1>
            <p class="text-gray-500">Manage your profile information and preferences.</p>
        </div>
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 font-medium flex items-center gap-2">
            <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        <div class="bg-white rounded-2xl shadow-xl shadow-red-500/5 border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 p-2">
                <nav class="flex flex-wrap gap-2" aria-label="Tabs">
                    <button onclick="showSettingsTab('basic')" class="tab-button flex-1 md:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 active-tab" data-tab="basic"><i class="fas fa-id-card mr-2"></i>Basic Info</button>
                    <button onclick="showSettingsTab('personal')" class="tab-button flex-1 md:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200" data-tab="personal"><i class="fas fa-user-shield mr-2"></i>Personal Data</button>
                    <button onclick="showSettingsTab('preferences')" class="tab-button flex-1 md:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200" data-tab="preferences"><i class="fas fa-sliders-h mr-2"></i>Preferences</button>
                </nav>
            </div>
            <div class="p-6 md:p-8">

                {{-- BASIC INFO TAB --}}
                <div id="basic-tab" class="tab-content transition-opacity duration-300">
                    <form action="{{ route('customer.settings.photo') }}" method="POST" enctype="multipart/form-data" class="mb-10 border-b border-gray-100 pb-10">
                        @csrf
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-full p-1 bg-white border-4 border-gray-100 shadow-lg overflow-hidden relative">
                                    @if($user->profile && $user->profile->profile_photo)
                                        @php
                                            $pp = ltrim((string)$user->profile->profile_photo, '/');
                                            if(str_starts_with($pp,'storage/')) $pp=substr($pp,8);
                                            if(!str_contains($pp,'/')) $pp='profile_photos/'.$pp;
                                            $ppUrl=asset('storage/'.$pp).'?v='.(optional($user->profile->updated_at)->timestamp??time());
                                        @endphp
                                        <img src="{{ $ppUrl }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold text-4xl">{{ strtoupper(substr($user->name??$user->username??'U',0,2)) }}</div>
                                    @endif
                                </div>
                                <label for="profile_photo" class="absolute bottom-1 right-1 w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 shadow-lg cursor-pointer transition-transform hover:scale-110 border-2 border-white"><i class="fas fa-camera text-xs"></i></label>
                                <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png" class="hidden" onchange="openCropperModal(this)">
                            </div>
                            <div class="text-center md:text-left flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Profile Photo</h3>
                                <p class="text-sm text-gray-500 mb-4">Upload a professional photo (JPG/PNG, max 2MB).</p>
                            </div>
                        </div>
                    </form>
                    <form action="{{ route('customer.settings.update') }}" method="POST">
                        @csrf
                        <div>
                            <div class="flex items-center gap-2 mb-3"><i class="fas fa-align-left text-red-500"></i><label for="summary" class="text-sm font-bold text-gray-900 uppercase tracking-wide">Professional Summary</label></div>
                            <textarea id="summary" name="summary" rows="5" placeholder="Write a brief professional summary about yourself..." class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium text-gray-800 placeholder-gray-400">{{ old('summary', optional($user->profile)->summary ?? '') }}</textarea>
                            @error('summary')<p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">Save Changes</button>
                        </div>
                    </form>
                </div>

                {{-- PERSONAL DATA TAB --}}
                <div id="personal-tab" class="tab-content hidden transition-opacity duration-300">
                    <form action="{{ route('customer.settings.update') }}" method="POST" id="personalDataForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                            {{-- Username --}}
                            <div class="md:col-span-2">
                                <label for="username" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Username</label>
                                <div class="relative"><div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-user text-gray-400"></i></div>
                                <input type="text" id="username" name="username" value="{{ old('username', $user->username??'') }}" required class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium"></div>
                                @error('username')<p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            {{-- Name --}}
                            <div class="md:col-span-2">
                                <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Full Name</label>
                                <div class="relative"><div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-user text-gray-400"></i></div>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name??'') }}" required class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium"></div>
                                @error('name')<p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            {{-- Email --}}
                            <div class="md:col-span-2">
                                <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                                <div class="relative"><div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-envelope text-gray-400"></i></div>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email??'') }}" required class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium"></div>
                                <p class="text-[10px] text-gray-400 mt-1.5 ml-1">Changes may require verification via email.</p>
                                @error('email')<p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            {{-- Professional Headline --}}
                            <div class="md:col-span-2">
                                <label for="professional_headline" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Professional Headline</label>
                                <div class="relative"><div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-briefcase text-gray-400"></i></div>
                                <input type="text" id="professional_headline" name="professional_headline" value="{{ old('professional_headline', optional($user->profile)->professional_headline??'') }}" placeholder="e.g. Event Specialist" class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium"></div>
                                <p class="text-[10px] text-gray-400 mt-1.5 ml-1">Appears below your name on your profile.</p>
                            </div>
                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Phone Number</label>
                                <div class="relative"><div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none border-r border-gray-200 pr-3 bg-gray-100 rounded-l-xl"><span class="text-gray-500 text-sm font-bold">🇮🇩 +62</span></div>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', optional($user->profile)->phone??'') }}" placeholder="81234567890" class="w-full pl-24 pr-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium"></div>
                                @error('phone')<p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                            {{-- Date of Birth --}}
                            <div>
                                <label for="date_of_birth" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Date of Birth</label>
                                <div class="relative"><div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-calendar-alt text-gray-400"></i></div>
                                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', optional($user->profile)->date_of_birth ? optional($user->profile)->date_of_birth->format('Y-m-d') : '') }}" class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium text-gray-800"></div>
                                @error('date_of_birth')<p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- NATIONALITY SECTION --}}
                        <div class="mt-8 pt-6 border-t border-gray-100">
                            <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2"><i class="fas fa-globe-asia text-red-500"></i> Kewarganegaraan &amp; Alamat</h3>

                            {{-- Nationality Radio --}}
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Kewarganegaraan</label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="nationality-radio-label flex items-center gap-3 px-5 py-3 rounded-xl border-2 cursor-pointer transition-all shadow-sm hover:border-gray-300 {{ old('nationality_type', optional($user->profile)->nationality_type) === 'wni' ? 'border-red-500 bg-red-50 ring-2 ring-red-500/20' : 'border-gray-200 bg-white' }}" id="lbl-wni">
                                        <input type="radio" name="nationality_type" value="wni" class="accent-red-600 w-4 h-4" onchange="switchNationality('wni')" {{ old('nationality_type', optional($user->profile)->nationality_type) === 'wni' ? 'checked' : '' }}>
                                        <div><div class="font-bold text-gray-900 text-sm">Indonesian Citizen (WNI)</div></div>
                                    </label>
                                    <label class="nationality-radio-label flex items-center gap-3 px-5 py-3 rounded-xl border-2 cursor-pointer transition-all shadow-sm hover:border-gray-300 {{ old('nationality_type', optional($user->profile)->nationality_type) === 'wna' ? 'border-red-500 bg-red-50 ring-2 ring-red-500/20' : 'border-gray-200 bg-white' }}" id="lbl-wna">
                                        <input type="radio" name="nationality_type" value="wna" class="accent-red-600 w-4 h-4" onchange="switchNationality('wna')" {{ old('nationality_type', optional($user->profile)->nationality_type) === 'wna' ? 'checked' : '' }}>
                                        <div><div class="font-bold text-gray-900 text-sm">Foreign Citizen (WNA)</div></div>
                                    </label>
                                </div>
                            </div>

                            {{-- Placeholder when no nationality selected --}}
                            <div id="nationality-placeholder" class="{{ old('nationality_type', optional($user->profile)->nationality_type) ? 'hidden' : '' }} p-6 rounded-xl border-2 border-dashed border-gray-200 text-center text-gray-400 text-sm">
                                <i class="fas fa-hand-pointer text-2xl mb-2 block"></i>Pilih kewarganegaraan terlebih dahulu untuk melihat form alamat.
                            </div>

                            {{-- WNI Address Fields --}}
                            <div id="wni-fields" class="{{ old('nationality_type', optional($user->profile)->nationality_type) === 'wni' ? '' : 'hidden' }} space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Provinsi <span class="text-red-500">*</span></label>
                                        <select id="sel-province" name="province" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                            <option value="">-- Pilih Provinsi --</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Kota / Kabupaten <span class="text-red-500">*</span></label>
                                        <select id="sel-city" name="city_regency" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium" disabled>
                                            <option value="">-- Pilih Kota/Kabupaten --</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Kecamatan</label>
                                        <select id="sel-district" name="district" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium" disabled>
                                            <option value="">-- Pilih Kecamatan --</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Kelurahan / Desa</label>
                                        <select id="sel-village" name="village" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium" disabled>
                                            <option value="">-- Pilih Kelurahan --</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Kode Pos</label>
                                        <input type="text" name="postal_code" id="wni-postal" value="{{ old('postal_code', optional($user->profile)->postal_code??'') }}" placeholder="e.g. 12345" maxlength="10" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">RT</label>
                                            <input type="text" name="rt" value="{{ old('rt', optional($user->profile)->rt??'') }}" placeholder="e.g. 001" maxlength="5" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">RW</label>
                                            <input type="text" name="rw" value="{{ old('rw', optional($user->profile)->rw??'') }}" placeholder="e.g. 005" maxlength="5" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Alamat Lengkap / Detail</label>
                                    <textarea name="address" rows="3" placeholder="Nama jalan, nomor rumah, kompleks, dll." class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">{{ old('address', optional($user->profile)->address??'') }}</textarea>
                                </div>
                            </div>

                            {{-- WNA Address Fields --}}
                            <div id="wna-fields" class="{{ old('nationality_type', optional($user->profile)->nationality_type) === 'wna' ? '' : 'hidden' }} space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Country / Negara <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select name="country" id="wna-country" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                                <option value="">e.g. Malaysia, Singapore, Japan</option>
                                                @php
                                                    $countries = [
                                                        'Afghanistan','Albania','Algeria','Australia','Austria','Belgium','Brazil','Cambodia',
                                                        'Canada','China','Denmark','Egypt','Finland','France','Germany','Greece','India',
                                                        'Indonesia','Iran','Iraq','Ireland','Italy','Japan','Jordan','Kenya','Korea, South',
                                                        'Kuwait','Malaysia','Mexico','Morocco','Netherlands','New Zealand','Nigeria','Norway',
                                                        'Pakistan','Philippines','Poland','Portugal','Qatar','Romania','Russia','Saudi Arabia',
                                                        'Singapore','South Africa','Spain','Sweden','Switzerland','Thailand','Turkey','Ukraine',
                                                        'United Arab Emirates','United Kingdom','United States','Vietnam','Yemen'
                                                    ];
                                                @endphp
                                                @foreach($countries as $c)
                                                    <option value="{{ $c }}" {{ old('country', optional($user->profile)->country) == $c ? 'selected' : '' }}>{{ $c }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">State / Province / Region</label>
                                        <input type="text" name="state_region" value="{{ old('state_region', optional($user->profile)->state_region??'') }}" placeholder="e.g. Selangor, New South Wales" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">City</label>
                                        <input type="text" name="wna_city" id="wna-city" value="{{ old('wna_city', optional($user->profile)->city_regency??'') }}" placeholder="e.g. Kuala Lumpur" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Postal Code</label>
                                        <input type="text" name="wna_postal_code" id="wna-postal" value="{{ old('wna_postal_code', optional($user->profile)->postal_code??'') }}" placeholder="e.g. 50450" maxlength="20" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Full Address / Detail</label>
                                    <textarea name="wna_address" rows="3" placeholder="Street, building number, unit, etc." class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">{{ old('wna_address', optional($user->profile)->address??'') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-4 border-t border-gray-100 pt-6">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">Save Changes</button>
                        </div>
                    </form>
                </div>
{{-- END PERSONAL TAB --}}

                {{-- PREFERENCES TAB --}}
                <div id="preferences-tab" class="tab-content hidden transition-opacity duration-300">
                    {{-- EDUCATION HISTORY --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-graduation-cap text-red-500"></i> Riwayat Pendidikan
                            </h3>
                            <button type="button" onclick="openEduModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl shadow shadow-red-500/30 transition-all hover:-translate-y-0.5">
                                <i class="fas fa-plus"></i> Tambah Riwayat Pendidikan
                            </button>
                        </div>
                        <div id="edu-list" class="space-y-3">
                            <div id="edu-empty" class="p-6 rounded-xl border-2 border-dashed border-gray-200 text-center text-gray-400 text-sm">
                                <i class="fas fa-graduation-cap text-3xl mb-2 block opacity-30"></i>
                                Belum ada riwayat pendidikan. Klik "Tambah Riwayat Pendidikan" untuk menambahkan.
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100 my-8">

                    {{-- SKILLS & LANGUAGES --}}
                    <form action="{{ route('customer.settings.update') }}" method="POST">
                        @csrf
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-tools text-red-500"></i> Skills &amp; Languages
                            </h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Professional Skills</label>
                                    <input type="hidden" id="skills_input" name="skills" value="{{ old('skills', optional($user->profile)->skills ?? '') }}">
                                    <div id="skills_tags" class="flex flex-wrap gap-2 mb-3"></div>
                                    <div class="relative">
                                        <select id="skills_select" onchange="addTag('skills', this.value); this.value='';" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium appearance-none">
                                            <option value="">+ Add Skill</option>
                                            @foreach(['Public Speaking', 'Time Management', 'Leadership', 'Teamwork', 'Problem Solving', 'Data Analysis', 'Event Management', 'First Aid', 'Photography', 'Social Media'] as $skill)
                                                <option value="{{ $skill }}">{{ $skill }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1.5 ml-1">Select multiple skills to add to your profile.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Languages Spoken</label>
                                    <input type="hidden" id="languages_input" name="languages" value="{{ old('languages', optional($user->profile)->languages ?? '') }}">
                                    <div id="languages_tags" class="flex flex-wrap gap-2 mb-3"></div>
                                    <div class="relative">
                                        <select id="languages_select" onchange="addTag('languages', this.value); this.value='';" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium appearance-none">
                                            <option value="">+ Add Language</option>
                                            @foreach(['Indonesian', 'English', 'Mandarin', 'Japanese', 'Korean', 'Arabic', 'French', 'German', 'Spanish'] as $lang)
                                                <option value="{{ $lang }}">{{ $lang }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500"><i class="fas fa-chevron-down text-xs"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">Save Preferences</button>
                        </div>
                    </form>
                </div>
                {{-- END PREFERENCES TAB --}}

            </div>
        </div>
    </div>
</div>
<!-- END PAGE LAYOUT -->

{{-- EDUCATION MODAL --}}
<div id="eduModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden scale-95 transition-transform duration-300">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900" id="eduModalTitle">Tambah Riwayat Pendidikan</h3>
            <button type="button" onclick="closeEduModal()" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form id="eduForm" onsubmit="submitEduForm(event)" class="p-6">
            <input type="hidden" id="edu_id" name="id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Tingkat Pendidikan <span class="text-red-500">*</span></label>
                    <select id="edu_level" name="education_level" required class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                        <option value="">Pilih Tingkat</option>
                        @foreach($educationLevels as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nama Institusi <span class="text-red-500">*</span></label>
                    <input type="text" id="edu_institution" name="institution_name" required placeholder="Nama Sekolah/Universitas" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Jurusan / Program Studi</label>
                    <input type="text" id="edu_major" name="field_of_study" placeholder="Kosongkan jika tidak ada" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Tahun Lulus</label>
                    <input type="number" id="edu_year" name="graduation_year" min="1950" max="{{ date('Y')+10 }}" placeholder="Contoh: 2020" class="w-full px-4 py-3 bg-white border border-gray-200 shadow-sm hover:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all font-medium">
                </div>
                <div class="flex items-center mt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="edu_still_studying" name="is_still_studying" value="1" onchange="toggleGradYear(this.checked)" class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                        <span class="text-sm font-medium text-gray-700">Masih menempuh pendidikan</span>
                    </label>
                </div>
                <div class="md:col-span-2 mt-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Bukti Pendidikan (Ijazah/Sertifikat)</label>
                    <input type="file" id="edu_proof" name="proof_document" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-all">
                    <p class="text-[10px] text-gray-400 mt-1">Format: PDF, JPG, PNG (Maks. 5MB)</p>
                    <div id="edu_proof_current" class="mt-2 text-sm text-gray-600 hidden">
                        Bukti saat ini: <a href="#" target="_blank" id="edu_proof_link" class="text-red-600 hover:underline">Lihat Dokumen</a>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeEduModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition-all">Batal</button>
                <button type="submit" id="btnEduSubmit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- CROPPER MODAL --}}
<div id="cropperModal" class="fixed inset-0 z-[60] hidden bg-gray-900/90 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900"><i class="fas fa-crop-alt text-red-500 mr-2"></i>Crop Profile Photo</h3>
            <button onclick="closeCropperModal()" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-6 bg-gray-900">
            <div class="w-full max-h-[60vh] overflow-hidden flex justify-center">
                <img id="cropperImage" src="" alt="To Crop" class="max-w-full max-h-[60vh]">
            </div>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
            <button onclick="closeCropperModal()" class="px-5 py-2 rounded-xl text-gray-600 font-bold hover:bg-gray-200 transition-all">Cancel</button>
            <button onclick="saveCroppedImage(this)" class="px-5 py-2 bg-red-600 text-white rounded-xl font-bold shadow-lg shadow-red-500/30 hover:bg-red-700 transition-all">Apply & Save</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/css/tom-select.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/js/tom-select.complete.min.js"></script>

<style>
/* Modern SaaS styling for TomSelect */
.ts-control {
    border-radius: 0.75rem !important;
    border-color: #e5e7eb !important;
    padding: 0.75rem 1rem !important;
    background-color: #ffffff !important;
    font-weight: 500 !important;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    color: #1f2937 !important;
}
.ts-control:hover:not(.disabled) {
    border-color: #d1d5db !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
}
.ts-control.focus {
    border-color: rgba(239, 68, 68, 0.6) !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
    background-color: #ffffff !important;
}
.ts-control.disabled, .ts-control.disabled * {
    background-color: #f9fafb !important;
    color: #9ca3af !important;
    cursor: not-allowed !important;
    border-color: #f3f4f6 !important;
    box-shadow: none !important;
}
.ts-dropdown {
    border-radius: 0.75rem !important;
    border: 1px solid #f3f4f6 !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
    overflow: hidden !important;
    margin-top: 0.35rem !important;
    background-color: #ffffff !important;
}
.ts-dropdown .ts-dropdown-content {
    padding: 0.35rem !important;
}
.ts-dropdown .option {
    border-radius: 0.5rem !important;
    padding: 0.6rem 1rem !important;
    transition: all 0.15s ease !important;
    color: #4b5563 !important;
    font-size: 0.9rem !important;
}
.ts-dropdown .option:hover, .ts-dropdown .option.active {
    background-color: #fef2f2 !important; /* red-50 */
    color: #b91c1c !important; /* red-700 */
    font-weight: 600 !important;
}
</style>

<script>
// TABS LOGIC
function showSettingsTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => { el.classList.add('hidden'); el.classList.remove('opacity-100'); });
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('text-red-600', 'bg-red-50', 'active-tab');
        btn.classList.add('text-gray-500', 'hover:bg-gray-50');
    });
    
    const activeBtn = document.querySelector(`.tab-button[data-tab="${tabName}"]`);
    if(activeBtn) {
        activeBtn.classList.remove('text-gray-500', 'hover:bg-gray-50');
        activeBtn.classList.add('text-red-600', 'bg-red-50', 'active-tab');
    }
    
    const content = document.getElementById(`${tabName}-tab`);
    if(content) {
        content.classList.remove('hidden');
        setTimeout(() => content.classList.add('opacity-100'), 50);
    }
    
    localStorage.setItem('activeSettingsTab', tabName);
}

// PROFILE PHOTO CROPPER
let cropper = null;
function openCropperModal(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('cropperImage').src = e.target.result;
            document.getElementById('cropperModal').classList.remove('hidden');
            if (cropper) cropper.destroy();
            cropper = new Cropper(document.getElementById('cropperImage'), {
                aspectRatio: 1, viewMode: 1, dragMode: 'move', autoCropArea: 0.8,
                restore: false, guides: true, center: true, highlight: false,
                cropBoxMovable: true, cropBoxResizable: true, toggleDragModeOnDblclick: false,
            });
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function closeCropperModal() {
    document.getElementById('cropperModal').classList.add('hidden');
    if (cropper) cropper.destroy();
    document.getElementById('profile_photo').value = '';
}
function saveCroppedImage(btn) {
    if (!cropper) return;
    
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    btn.disabled = true;

    cropper.getCroppedCanvas({ width: 500, height: 500 }).toBlob((blob) => {
        const formData = new FormData();
        formData.append('profile_photo', blob, 'profile.jpg');
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("customer.settings.photo") }}', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) window.location.reload();
            else alert(data.message || 'Error saving photo.');
        })
        .catch(err => { console.error(err); alert('Failed to upload photo.'); })
        .finally(() => { btn.innerHTML = originalText; btn.disabled = false; closeCropperModal(); });
    }, 'image/jpeg', 0.9);
}

// SKILLS AND LANGUAGES TAGS
function initTags(type) {
    const input = document.getElementById(`${type}_input`);
    const container = document.getElementById(`${type}_tags`);
    if(!input || !container) return;
    
    const renderTags = () => {
        container.innerHTML = '';
        const tags = input.value.split(',').map(t => t.trim()).filter(t => t);
        tags.forEach(tag => {
            const el = document.createElement('span');
            el.className = 'inline-flex items-center px-3 py-1 bg-red-50 text-red-700 text-sm font-medium rounded-full border border-red-200';
            el.innerHTML = `${tag} <button type="button" onclick="removeTag('${type}', '${tag}')" class="ml-2 text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>`;
            container.appendChild(el);
        });
    };
    renderTags();
    window[`render${type}Tags`] = renderTags;
}
function addTag(type, value) {
    if(!value) return;
    const input = document.getElementById(`${type}_input`);
    let tags = input.value.split(',').map(t => t.trim()).filter(t => t);
    if(!tags.includes(value)) {
        tags.push(value);
        input.value = tags.join(', ');
        window[`render${type}Tags`]();
    }
}
function removeTag(type, value) {
    const input = document.getElementById(`${type}_input`);
    let tags = input.value.split(',').map(t => t.trim()).filter(t => t);
    tags = tags.filter(t => t !== value);
    input.value = tags.join(', ');
    window[`render${type}Tags`]();
}

// NATIONALITY & CASCADING REGIONS
function switchNationality(type) {
    const placeholder = document.getElementById('nationality-placeholder');
    const wniFields = document.getElementById('wni-fields');
    const wnaFields = document.getElementById('wna-fields');
    const lblWni = document.getElementById('lbl-wni');
    const lblWna = document.getElementById('lbl-wna');
    
    placeholder.classList.add('hidden');
    
    const activeClasses = ['border-red-500', 'bg-red-50', 'ring-2', 'ring-red-500/20'];
    const inactiveClasses = ['border-gray-200', 'bg-white'];

    if(type === 'wni') {
        wniFields.classList.remove('hidden');
        wnaFields.classList.add('hidden');
        document.getElementById('wna-country').removeAttribute('required');
        
        lblWni.classList.remove(...inactiveClasses);
        lblWni.classList.add(...activeClasses);
        lblWna.classList.remove(...activeClasses);
        lblWna.classList.add(...inactiveClasses);
    } else {
        wnaFields.classList.remove('hidden');
        wniFields.classList.add('hidden');
        document.getElementById('wna-country').setAttribute('required', 'required');
        
        lblWna.classList.remove(...inactiveClasses);
        lblWna.classList.add(...activeClasses);
        lblWni.classList.remove(...activeClasses);
        lblWni.classList.add(...inactiveClasses);
    }
}

let regionData = { provinces: [], cities: [], districts: [], villages: [] };
const currentProfile = {
    province: "{{ old('province', optional($user->profile)->province) }}",
    city: "{{ old('city_regency', optional($user->profile)->city_regency) }}",
    district: "{{ old('district', optional($user->profile)->district) }}",
    village: "{{ old('village', optional($user->profile)->village) }}"
};

let tsInstances = {};

function populateSelect(id, data, currentVal, defaultLabel) {
    if(tsInstances[id]) {
        tsInstances[id].destroy();
        delete tsInstances[id];
    }
    const select = document.getElementById(id);
    select.innerHTML = `<option value="">-- ${defaultLabel} --</option>`;
    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.nama;
        option.dataset.id = item.id;
        option.textContent = item.nama;
        if(item.nama === currentVal) option.selected = true;
        select.appendChild(option);
    });
    select.disabled = false;
    
    // Initialize TomSelect for searchable dropdown
    tsInstances[id] = new TomSelect(select, {
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: `-- ${defaultLabel} --`,
        onChange: function(value) {
            if(id === 'sel-province') loadCities(value);
            else if(id === 'sel-city') loadDistricts(value);
            else if(id === 'sel-district') loadVillages(value);
        }
    });
    
    return select;
}

function getSelectedOptionId(selectId) {
    const select = document.getElementById(selectId);
    if(select.selectedIndex <= 0) return null;
    return select.options[select.selectedIndex].dataset.id;
}

async function loadProvinces() {
    try {
        const res = await fetch('/api/indonesia/provinces');
        regionData.provinces = await res.json();
        populateSelect('sel-province', regionData.provinces, currentProfile.province, 'Pilih Provinsi');
        
        if(currentProfile.province) {
            setTimeout(() => loadCities(currentProfile.province, true), 100);
        } else {
            resetSelects(['sel-city', 'sel-district', 'sel-village']);
        }
    } catch(e) { console.error("Error loading provinces:", e); }
}

async function loadCities(provinceName, forceInit = false) {
    if(!provinceName) return resetSelects(['sel-city', 'sel-district', 'sel-village']);
    resetSelects(['sel-district', 'sel-village']);
    
    let isInit = forceInit || (provinceName === currentProfile.province);
    
    // During initialization, the option might not be fully rendered yet to read dataset.id easily,
    // so we find the province ID from the fetched array.
    const province = regionData.provinces.find(p => p.nama === provinceName);
    if(!province) return;

    try {
        const res = await fetch(`/api/indonesia/cities/${province.id}`);
        regionData.cities = await res.json();
        populateSelect('sel-city', regionData.cities, isInit ? currentProfile.city : '', 'Pilih Kota/Kabupaten');
        
        if(isInit && currentProfile.city) {
            setTimeout(() => loadDistricts(currentProfile.city, true), 100);
        }
    } catch(e) { console.error("Error loading cities", e); }
}

async function loadDistricts(cityName, forceInit = false) {
    if(!cityName) return resetSelects(['sel-district', 'sel-village']);
    resetSelects(['sel-village']);
    
    let isInit = forceInit || (cityName === currentProfile.city);
    
    const city = regionData.cities.find(c => c.nama === cityName);
    if(!city) return;

    try {
        const res = await fetch(`/api/indonesia/districts/${city.id}`);
        regionData.districts = await res.json();
        populateSelect('sel-district', regionData.districts, isInit ? currentProfile.district : '', 'Pilih Kecamatan');
        
        if(isInit && currentProfile.district) {
            setTimeout(() => loadVillages(currentProfile.district, true), 100);
        }
    } catch(e) { console.error("Error loading districts", e); }
}

async function loadVillages(districtName, forceInit = false) {
    if(!districtName) return resetSelects(['sel-village']);
    
    let isInit = forceInit || (districtName === currentProfile.district);
    
    const district = regionData.districts.find(d => d.nama === districtName);
    if(!district) return;

    try {
        const res = await fetch(`/api/indonesia/villages/${district.id}`);
        regionData.villages = await res.json();
        populateSelect('sel-village', regionData.villages, isInit ? currentProfile.village : '', 'Pilih Kelurahan');
    } catch(e) { console.error("Error loading villages", e); }
}

function resetSelects(ids) {
    ids.forEach(id => {
        if(tsInstances[id]) {
            tsInstances[id].destroy();
            delete tsInstances[id];
        }
        const el = document.getElementById(id);
        el.innerHTML = `<option value="">-- Pilih --</option>`;
        el.disabled = true;
        
        // Re-initialize as disabled TomSelect
        tsInstances[id] = new TomSelect(el, {
            create: false,
            placeholder: '-- Pilih --'
        });
        tsInstances[id].disable();
    });
}

// EDUCATION CRUD
let educations = [];

async function loadEducations() {
    try {
        const res = await fetch('{{ route("customer.education.index") }}');
        const json = await res.json();
        if(json.success) {
            educations = json.data;
            renderEducations();
        }
    } catch(e) { console.error("Failed loading educations", e); }
}

function renderEducations() {
    const list = document.getElementById('edu-list');
    const empty = document.getElementById('edu-empty');
    
    if(educations.length === 0) {
        list.innerHTML = '';
        list.appendChild(empty);
        empty.classList.remove('hidden');
        return;
    }
    
    list.innerHTML = '';
    educations.forEach(edu => {
        const docHtml = edu.proof_document_url 
            ? `<a href="${edu.proof_document_url}" target="_blank" class="text-xs text-red-600 hover:underline flex items-center gap-1 mt-2"><i class="fas fa-file-pdf"></i> Lihat Dokumen</a>`
            : '';
            
        const gradText = edu.is_still_studying ? 'Masih menempuh pendidikan' : `Lulus: ${edu.graduation_year || '-'}`;
        
        const html = `
        <div class="p-5 border border-gray-100 rounded-xl bg-white shadow-sm hover:shadow-md transition-shadow relative group">
            <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="editEdu(${edu.id})" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100"><i class="fas fa-edit"></i></button>
                <button onclick="deleteEdu(${edu.id})" class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100"><i class="fas fa-trash-alt"></i></button>
            </div>
            <div class="flex gap-4">
                <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0 text-xl"><i class="fas fa-graduation-cap"></i></div>
                <div>
                    <h4 class="font-bold text-gray-900">${edu.institution_name}</h4>
                    <p class="text-sm text-gray-600 font-medium">${edu.education_level_label} ${edu.field_of_study ? ' - ' + edu.field_of_study : ''}</p>
                    <p class="text-xs text-gray-500 mt-1">${gradText}</p>
                    ${docHtml}
                </div>
            </div>
        </div>`;
        list.insertAdjacentHTML('beforeend', html);
    });
}

function openEduModal(id = null) {
    const modal = document.getElementById('eduModal');
    const form = document.getElementById('eduForm');
    
    form.reset();
    document.getElementById('edu_id').value = '';
    document.getElementById('edu_proof_current').classList.add('hidden');
    toggleGradYear(false);
    
    if(id) {
        document.getElementById('eduModalTitle').innerText = 'Edit Riwayat Pendidikan';
        const edu = educations.find(e => e.id === id);
        if(edu) {
            document.getElementById('edu_id').value = edu.id;
            document.getElementById('edu_level').value = edu.education_level;
            document.getElementById('edu_institution').value = edu.institution_name;
            document.getElementById('edu_major').value = edu.field_of_study || '';
            
            if(edu.is_still_studying) {
                document.getElementById('edu_still_studying').checked = true;
                toggleGradYear(true);
            } else {
                document.getElementById('edu_year').value = edu.graduation_year || '';
            }
            
            if(edu.proof_document_url) {
                document.getElementById('edu_proof_current').classList.remove('hidden');
                document.getElementById('edu_proof_link').href = edu.proof_document_url;
            }
        }
    } else {
        document.getElementById('eduModalTitle').innerText = 'Tambah Riwayat Pendidikan';
    }
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.querySelector('.bg-white').classList.remove('scale-95');
    }, 10);
}

function closeEduModal() {
    const modal = document.getElementById('eduModal');
    modal.classList.add('opacity-0');
    modal.querySelector('.bg-white').classList.add('scale-95');
    setTimeout(() => modal.classList.add('hidden'), 300);
}

function toggleGradYear(isStillStudying) {
    const input = document.getElementById('edu_year');
    if(isStillStudying) {
        input.value = '';
        input.disabled = true;
        input.classList.add('bg-gray-100', 'text-gray-400');
    } else {
        input.disabled = false;
        input.classList.remove('bg-gray-100', 'text-gray-400');
    }
}

async function submitEduForm(e) {
    e.preventDefault();
    const form = document.getElementById('eduForm');
    const btn = document.getElementById('btnEduSubmit');
    const id = document.getElementById('edu_id').value;
    
    const formData = new FormData(form);
    
    let url = '{{ route("customer.education.store") }}';
    if(id) {
        url = `/dashboard/education/${id}`;
        // Laravel needs _method for PUT/POST multipart, though we can use POST in route definition
        // We defined Route::post for update to handle files easier
    }
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;
    
    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        });
        
        const json = await res.json();
        if(json.success) {
            closeEduModal();
            loadEducations();
        } else {
            alert(json.message || 'Gagal menyimpan data.');
        }
    } catch(err) {
        console.error(err);
        alert('Terjadi kesalahan jaringan.');
    } finally {
        btn.innerHTML = 'Simpan';
        btn.disabled = false;
    }
}

function editEdu(id) { openEduModal(id); }

async function deleteEdu(id) {
    if(!confirm('Apakah Anda yakin ingin menghapus riwayat pendidikan ini?')) return;
    
    try {
        const res = await fetch(`/dashboard/education/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        const json = await res.json();
        if(json.success) loadEducations();
    } catch(err) { console.error(err); alert('Gagal menghapus data.'); }
}


// INIT ON LOAD
document.addEventListener('DOMContentLoaded', () => {
    const activeTab = localStorage.getItem('activeSettingsTab') || 'basic';
    showSettingsTab(activeTab);
    
    initTags('skills');
    initTags('languages');
    
    loadProvinces(); // init regional selects
    loadEducations(); // init education history
    
    if (document.getElementById('wna-country')) {
        new TomSelect('#wna-country', {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "e.g. Malaysia, Singapore, Japan"
        });
    }
});
</script>
@endsection

