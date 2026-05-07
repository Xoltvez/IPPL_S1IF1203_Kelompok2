<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacaBae - @yield('page_title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/brand/logo.png') }}">
    <style>
        body { font-family: 'Inter', sans-serif; overflow: hidden; } /* Cegah scroll body utama */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8FAFC] antialiased h-screen overflow-hidden">
    <div class="flex h-full w-full">
        <div class="w-72 h-full flex-shrink-0 hidden md:block">
            @include('layouts.partials.sidebar-member')
        </div>

        <div class="flex-1 flex flex-col min-w-0 h-full">
            @include('layouts.partials.header')

            <main class="flex-1 overflow-y-auto p-6 lg:p-10 custom-scrollbar">
                <div class="bg-white rounded-[32px] border border-gray-100 min-h-full p-8 shadow-sm">
                    @section('content')
                    @show
                </div>
            </main>
        </div>
    </div>
</body>
</html>