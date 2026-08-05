@extends('layouts.public')

@section('title', 'My Certificates - NOCIS')

@section('content')
<!-- Modern Web3 Customer Certificates -->
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <!-- Main Content Wrapper with Top Padding -->
    <div class="relative z-10 pt-32 pb-16">
        <div class="container mx-auto px-4 lg:px-6 max-w-7xl">
            
            <!-- Page Header -->
            <div class="mb-10">
                <h1 class="text-4xl font-bold text-gray-900 mb-2 tracking-tight">My Certificates</h1>
                <p class="text-gray-600 text-lg">View and manage your event participation certificates.</p>
            </div>

            <!-- Navigation Tabs -->
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
                <a href="{{ route('customer.certificates') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-white text-red-600 shadow-md transition-all">
                    <i class="fas fa-certificate mr-2"></i> My Certificates
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Arise Event Certificates -->
                <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/60 shadow-xl overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100/50 flex justify-between items-center bg-white/40">
                        <h3 class="text-xl font-bold text-gray-900">Arise Event Certificates</h3>
                        <div class="bg-white/50 px-3 py-1 rounded-lg border border-gray-100 text-sm font-medium text-gray-600">
                            Total: <span class="font-bold text-gray-900">{{ collect($ariseCertificates)->count() }}</span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        @if(collect($ariseCertificates)->isEmpty())
                            <div class="text-center py-10">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                    <i class="fas fa-medal text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">No Certificates Found</h3>
                                <p class="text-gray-500 text-sm">You haven't received any event certificates yet.</p>
                            </div>
                        @else
                            @foreach($ariseCertificates as $cert)
                                @php $payload = $cert->payload ?? []; @endphp
                                <div class="group bg-white border border-gray-200 hover:border-gray-300 rounded-2xl p-5 transition-all shadow-sm hover:shadow">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                                        <div class="flex gap-4 items-center">
                                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0">
                                                <i class="fas fa-medal text-xl"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="text-lg font-bold text-gray-900 leading-none mb-2">{{ $payload['volunteer_name'] ?? 'Volunteer' }}</h4>
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mb-1">
                                                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-100 uppercase tracking-wide">Valid & Issued</span>
                                                    <span class="text-[11px] font-medium text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md">{{ $payload['role_label'] ?? 'Worker' }}</span>
                                                </div>
                                                <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-1.5">
                                                    <i class="fas fa-calendar-day text-gray-400"></i>
                                                    <span class="font-medium">{{ $payload['event_title'] ?? 'Arise Event' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center md:justify-end">
                                            @if($cert->qr_token)
                                                <a href="{{ url('/sertifikat/verify/' . $cert->qr_token) }}" 
                                                    class="w-full md:w-auto px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold text-xs rounded-xl hover:bg-gray-50 hover:text-blue-600 transition-all flex items-center justify-center gap-2">
                                                    <i class="fas fa-external-link-alt"></i> View
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Externally Uploaded Certificates -->
                <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/60 shadow-xl overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100/50 flex justify-between items-center bg-white/40">
                        <h3 class="text-xl font-bold text-gray-900">External Certificates</h3>
                        <div class="bg-white/50 px-3 py-1 rounded-lg border border-gray-100 text-sm font-medium text-gray-600">
                            Total: <span class="font-bold text-gray-900">{{ collect($externalCertificates)->count() }}</span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        @if(collect($externalCertificates)->isEmpty())
                            <div class="text-center py-10">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                    <i class="fas fa-upload text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">No External Certificates</h3>
                                <p class="text-gray-500 text-sm">You haven't uploaded any certificates.</p>
                            </div>
                        @else
                            @foreach($externalCertificates as $extCert)
                                <div class="group bg-white border border-gray-200 hover:border-gray-300 rounded-2xl p-5 transition-all shadow-sm hover:shadow">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                                        <div class="flex gap-4 items-center">
                                            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 flex-shrink-0">
                                                <i class="fas fa-file-pdf text-xl"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="text-lg font-bold text-gray-900 leading-none mb-2">{{ $extCert->title }}</h4>
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mb-1">
                                                    <span class="text-[11px] font-bold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-md border border-indigo-100 uppercase tracking-wide">{{ strtoupper(str_replace('_',' ', $extCert->stage)) }}</span>
                                                </div>
                                                <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-1.5">
                                                    <i class="fas fa-calendar-day text-gray-400"></i>
                                                    <span class="font-medium">{{ \Carbon\Carbon::parse($extCert->event_date)->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center md:justify-end">
                                            <a href="{{ asset('storage/'.$extCert->file_path) }}" target="_blank"
                                                class="w-full md:w-auto px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold text-xs rounded-xl hover:bg-gray-50 hover:text-indigo-600 transition-all flex items-center justify-center gap-2">
                                                <i class="fas fa-external-link-alt"></i> View File
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
