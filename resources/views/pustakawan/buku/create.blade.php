<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Koleksi Buku MacaBae') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('pustakawan.buku.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">ISBN</label>
                                <input type="text" name="isbn" class="border-gray-300 rounded-md shadow-sm w-full" required>
                            </div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Judul Buku</label>
                                <input type="text" name="judul" class="border-gray-300 rounded-md shadow-sm w-full" required>
                            </div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Kategori</label>
                                <select name="kategori_id" class="border-gray-300 rounded-md shadow-sm w-full" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Pengarang</label>
                                <input type="text" name="pengarang" class="border-gray-300 rounded-md shadow-sm w-full" required>
                            </div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Stok</label>
                                <input type="number" name="stok" class="border-gray-300 rounded-md shadow-sm w-full" min="0" required>
                            </div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Lokasi Rak</label>
                                <input type="text" name="lokasi_rak" placeholder="Contoh: A1-02" class="border-gray-300 rounded-md shadow-sm w-full" required>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="penerbit" value="-">
                    <input type="hidden" name="tahun_terbit" value="2026">

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('pustakawan.buku.index') }}" class="text-sm text-gray-600 underline mr-4">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Simpan ke Perpustakaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>