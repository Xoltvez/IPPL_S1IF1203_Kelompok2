<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Buku: ') . $buku->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('pustakawan.buku.update', $buku->id) }}" method="POST">
                    @csrf
                    @method('PUT') 

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">ISBN</label>
                                <input type="text" name="isbn" value="{{ old('isbn', $buku->isbn) }}" class="border-gray-300 rounded-md shadow-sm w-full" required>
                                @error('isbn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Judul Buku</label>
                                <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" class="border-gray-300 rounded-md shadow-sm w-full" required>
                            </div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Kategori</label>
                                <select name="kategori_id" class="border-gray-300 rounded-md shadow-sm w-full" required>
                                    @foreach($kategoris as $k)
                                        <option value="{{ $k->id }}" {{ $buku->kategori_id == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Pengarang</label>
                                <input type="text" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" class="border-gray-300 rounded-md shadow-sm w-full" required>
                            </div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Stok</label>
                                <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}" class="border-gray-300 rounded-md shadow-sm w-full" min="0" required>
                            </div>
                            <div class="mb-4">
                                <label class="block font-medium text-sm text-gray-700">Lokasi Rak</label>
                                <input type="text" name="lokasi_rak" value="{{ old('lokasi_rak', $buku->lokasi_rak) }}" class="border-gray-300 rounded-md shadow-sm w-full" required>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('pustakawan.buku.index') }}" class="text-sm text-gray-600 underline mr-4">Batal</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 shadow-sm transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>