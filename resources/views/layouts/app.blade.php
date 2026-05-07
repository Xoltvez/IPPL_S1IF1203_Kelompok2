<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Macabae') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F8FAFC] antialiased h-screen overflow-hidden">
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

            <main class="flex-1 overflow-y-auto p-6 lg:p-10 custom-scrollbar">
                <div class="bg-white rounded-[32px] border border-gray-100 min-h-full p-8 shadow-sm">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
