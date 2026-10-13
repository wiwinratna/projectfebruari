@extends('layouts.public')

@section('title', 'Card Not Available Yet - ARISE')

@section('content')
<div class="min-h-screen bg-gray-50 relative overflow-hidden">
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-red-100/40 rounded-full filter blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/40 rounded-full filter blur-[100px] animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <div class="relative z-10 pt-24 sm:pt-32 pb-20 sm:pb-16">
        <div class="w-full px-3 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-white/60 shadow-xl p-6 sm:p-8 lg:p-10 text-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-yellow-50 border border-yellow-100 flex items-center justify-center mx-auto mb-4 sm:mb-5">
                    <i class="fas fa-clock text-xl sm:text-2xl text-yellow-600"></i>
                </div>
                <h1 class="text-xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-3">Card not available yet</h1>
                <p class="text-gray-600 text-xs sm:text-lg mb-4 sm:mb-6">
                    Please wait for the admin to issue your card.
                </p>
                <p class="text-xs sm:text-sm text-gray-500 mb-6 sm:mb-8">
                    Application: <span class="font-semibold text-gray-700">{{ $application->opening->title ?? 'N/A' }}</span>
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-2.5 sm:gap-3">
                    <a href="{{ route('customer.applications') }}" class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl bg-red-600 text-white text-xs sm:text-base font-semibold hover:bg-red-700 transition-colors">
                        Back to My Applications
                    </a>
                    <a href="{{ route('customer.dashboard') }}" class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl bg-gray-100 text-gray-700 text-xs sm:text-base font-semibold hover:bg-gray-200 transition-colors">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
