<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme') === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Dashboard') - {{ config('app.name', 'MacaBae') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Icon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/brand/logo.png') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F3F7FB] dark:bg-slate-950 antialiased h-screen overflow-hidden text-[#2F3951] dark:text-slate-100 transition-colors duration-200">
    <div class="flex h-full w-full">
        <div class="w-72 h-full flex-shrink-0 hidden md:block">
            @if(Auth::user()->role == 'pustakawan')
                @include('layouts.partials.sidebar-librarian')
            @elseif(Auth::user()->role == 'admin')
                @include('layouts.partials.sidebar-admin')
            @else
                @include('layouts.partials.sidebar-member')
            @endif
        </div>

        <div class="flex-1 flex flex-col min-w-0 h-full">
            @include('layouts.partials.header')

            <main class="rounded flex-1 overflow-y-auto p-6 lg:p-10 custom-scrollbar">
                <div class="rounded-sm border border-gray-100 dark:border-slate-800 min-h-full p-8 shadow-sm bg-[#FEFEFE] dark:bg-slate-900 transition-colors duration-200">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/app-toast.js') }}"></script>
    <script src="{{ asset('assets/js/helpers.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                fireToast('success', "{!! session('success') !!}");
            @endif

            @if(session('error'))
                fireToast('error', "{!! session('error') !!}");
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>