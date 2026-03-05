@extends('layouts.public')

@section('title', 'Card Not Available Yet - NOCIS')

@section('content')
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <div class="relative z-10 pt-32 pb-16">
        <div class="container mx-auto px-4 lg:px-6 max-w-4xl">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-white/60 shadow-xl p-8 lg:p-10 text-center">
                <div class="w-16 h-16 rounded-2xl bg-yellow-50 border border-yellow-100 flex items-center justify-center mx-auto mb-5">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-3">Card not available yet</h1>
                <p class="text-gray-600 text-lg mb-6">
                    Please wait for the admin to issue your card.
                </p>
                <p class="text-sm text-gray-500 mb-8">
                    Application: <span class="font-semibold text-gray-700">{{ $application->opening->title ?? 'N/A' }}</span>
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-3">
                    <a href="{{ route('customer.applications') }}" class="px-6 py-3 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 transition-colors">
                        Back to My Applications
                    </a>
                    <a href="{{ route('customer.dashboard') }}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition-colors">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
