<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NOCIS')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    @stack('styles')
</head>
<body class="bg-gray-100">

    <div id="app">
        
        @include('components.sidebar')

        <div class="main-content min-h-screen transition-all duration-300" id="main-content"> 
            
            @include('components.header')

            @include('components.flash')
    @include('components.confirm-modal')

            <main class="page-content p-4 lg:p-6">
                @yield('content') {{-- Ini tempat konten Dashboard, Events, dll. akan dimasukkan --}}
            </main>
        </div>
    </div>

    {{-- JavaScript files are loaded via Vite --}}
    @stack('scripts')
</body>
</html>