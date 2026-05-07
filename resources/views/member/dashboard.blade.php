{{-- resources/views/member/dashboard.blade.php --}}
@extends('layouts.member')

@section('page_title', 'Selamat Datang! 👋')

@section('content')
    <div class="space-y-6">
        <div class="bg-[#F3F7FB] border border-[#4D9BE2]/10 p-6 md:p-8 rounded-2xl flex items-center gap-5">
            <div class="p-4 bg-white rounded-xl shadow-sm text-3xl">✨</div>
            <div>
                <h2 class="text-xl font-bold text-[#2F3951]">Halo, {{ Auth::user()->name }}!</h2>
                <p class="text-sm text-gray-600 mt-1">Siap untuk petualangan membaca hari ini? Cek katalog terbaru kami.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border border-gray-100 rounded-2xl p-6 h-48 flex items-center justify-center text-gray-400 bg-gray-50/50">Statistik 1</div>
            <div class="border border-gray-100 rounded-2xl p-6 h-48 flex items-center justify-center text-gray-400 bg-gray-50/50">Statistik 2</div>
            <div class="border border-gray-100 rounded-2xl p-6 h-48 flex items-center justify-center text-gray-400 bg-gray-50/50">Statistik 3</div>
        </div>
    </div>
@endsection