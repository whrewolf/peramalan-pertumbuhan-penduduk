<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Data Penduduk</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('penduduk.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Periode -->
                        <div>
                            <label for="periode" class="block text-sm font-medium text-gray-700">Periode (YYYY-MM)</label>
                            <input type="month" name="periode" id="periode" value="{{ old('periode') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('periode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kelahiran -->
                        <div>
                            <label for="kelahiran" class="block text-sm font-medium text-gray-700">Kelahiran</label>
                            <input type="number" name="kelahiran" id="kelahiran" value="{{ old('kelahiran') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0">
                            @error('kelahiran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kematian -->
                        <div>
                            <label for="kematian" class="block text-sm font-medium text-gray-700">Kematian</label>
                            <input type="number" name="kematian" id="kematian" value="{{ old('kematian') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0">
                            @error('kematian') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Migrasi Masuk -->
                        <div>
                            <label for="migrasi_masuk" class="block text-sm font-medium text-gray-700">Migrasi Masuk</label>
                            <input type="number" name="migrasi_masuk" id="migrasi_masuk" value="{{ old('migrasi_masuk') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0">
                            @error('migrasi_masuk') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Migrasi Keluar -->
                        <div>
                            <label for="migrasi_keluar" class="block text-sm font-medium text-gray-700">Migrasi Keluar</label>
                            <input type="number" name="migrasi_keluar" id="migrasi_keluar" value="{{ old('migrasi_keluar') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0">
                            @error('migrasi_keluar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jumlah Penduduk (di akhir) -->
                        <div>
                            <label for="jumlah_penduduk" class="block text-sm font-medium text-gray-700">Jumlah Penduduk</label>
                            <input type="number" name="jumlah_penduduk" id="jumlah_penduduk" value="{{ old('jumlah_penduduk') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required min="0">
                            @error('jumlah_penduduk') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Simpan
                        </button>
                        <a href="{{ route('penduduk.index') }}" class="ml-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>