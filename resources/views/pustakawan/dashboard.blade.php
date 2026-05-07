<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama MacaBae') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-blue-500 p-6 rounded-lg shadow text-white">
                    <h4 class="text-sm font-semibold uppercase">Total Judul Buku</h4>
                    <p class="text-3xl font-bold">{{ $totalBuku }}</p>
                </div>
                <div class="bg-green-500 p-6 rounded-lg shadow text-white">
                    <h4 class="text-sm font-semibold uppercase">Total Stok Fisik</h4>
                    <p class="text-3xl font-bold">{{ $totalStok }}</p>
                </div>
                <div class="bg-yellow-500 p-6 rounded-lg shadow text-white">
                    <h4 class="text-sm font-semibold uppercase">Kategori</h4>
                    <p class="text-3xl font-bold">{{ $totalKategori }}</p>
                </div>
                <div class="bg-purple-500 p-6 rounded-lg shadow text-white">
                    <h4 class="text-sm font-semibold uppercase">Anggota Terdaftar</h4>
                    <p class="text-3xl font-bold">{{ $totalMember }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p>Status Anda: <span class="badge bg-gray-200 px-2 py-1 rounded text-xs uppercase">{{ Auth::user()->role }}</span></p>
                
                <div class="mt-6 border-t pt-4">
                    <h4 class="font-semibold">Akses Cepat:</h4>
                    <div class="mt-2 flex gap-2">
                        @if(Auth::user()->role == 'pustakawan')
                            <a href="{{ route('pustakawan.buku.index') }}" class="text-blue-600 hover:underline">→ Kelola Koleksi Buku</a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">→ Pengaturan Profil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>