@extends('layouts.app')

@section('title', $user->name . ' - Volunteer Profile - NOCIS')
@section('page-title')
    Volunteer Profile <span class="bg-emerald-500 text-white text-sm px-2 py-1 rounded-full ml-2">{{ $user->name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <a href="{{ route('super-admin.volunteers.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold"><i class="fas fa-arrow-left mr-2"></i> Back to Volunteers</a>

    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg"><i class="fas fa-check-circle mr-2"></i> {{ session('status') }}</div>
    @endif

    {{-- Profile Header --}}
    @include('super-admin.volunteers.partials.header', ['user' => $user])

    {{-- Tab Navigation --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex border-b border-gray-100 overflow-x-auto" id="vol-tabs">
            <button onclick="switchTab('overview')" data-tab="overview" class="vol-tab active flex items-center px-6 py-4 text-sm font-semibold border-b-2 transition-all whitespace-nowrap"><i class="fas fa-th-large mr-2"></i>Overview</button>
            <button onclick="switchTab('personal')" data-tab="personal" class="vol-tab flex items-center px-6 py-4 text-sm font-semibold border-b-2 transition-all whitespace-nowrap"><i class="fas fa-user mr-2"></i>Personal Info</button>
            <button onclick="switchTab('qualification')" data-tab="qualification" class="vol-tab flex items-center px-6 py-4 text-sm font-semibold border-b-2 transition-all whitespace-nowrap"><i class="fas fa-graduation-cap mr-2"></i>Qualification</button>
            <button onclick="switchTab('applications')" data-tab="applications" class="vol-tab flex items-center px-6 py-4 text-sm font-semibold border-b-2 transition-all whitespace-nowrap"><i class="fas fa-file-alt mr-2"></i>Applications <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-xs">{{ $user->applications->count() }}</span></button>
        </div>

        {{-- Tab Contents --}}
        <div class="p-6">
            <div id="tab-overview">@include('super-admin.volunteers.partials.tab-overview')</div>
            <div id="tab-personal" class="hidden">@include('super-admin.volunteers.partials.tab-personal')</div>
            <div id="tab-qualification" class="hidden">@include('super-admin.volunteers.partials.tab-qualification')</div>
            <div id="tab-applications" class="hidden">@include('super-admin.volunteers.partials.tab-applications')</div>
        </div>
    </div>
</div>

@push('styles')
<style>
.vol-tab{color:#9ca3af;border-color:transparent}.vol-tab:hover{color:#374151;background:#f9fafb}.vol-tab.active{color:#059669;border-color:#059669;background:#ecfdf5}
</style>
@endpush

@push('scripts')
<script>
function switchTab(name){
    document.querySelectorAll('.vol-tab').forEach(t=>{t.classList.remove('active')});
    document.querySelectorAll('[id^="tab-"]').forEach(c=>{c.classList.add('hidden')});
    document.querySelector(`[data-tab="${name}"]`).classList.add('active');
    document.getElementById('tab-'+name).classList.remove('hidden');
}
</script>
@endpush
@endsection
