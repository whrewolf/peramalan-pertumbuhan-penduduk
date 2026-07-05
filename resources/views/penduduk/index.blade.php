<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Penduduk</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between mb-4">
                    <a href="{{ route('penduduk.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Tambah Data
                    </a>
                    <div class="flex gap-2">
                        <form action="{{ route('penduduk.import') }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                            @csrf
                            <input type="file" name="file" required class="border p-2 rounded">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Import Excel
                            </button>
                        </form>
                        <a href="{{ route('penduduk.export') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Export Excel
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelahiran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kematian</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Migrasi Masuk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Migrasi Keluar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penduduk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($penduduk as $p)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $p->periode }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($p->kelahiran) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($p->kematian) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($p->migrasi_masuk) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($p->migrasi_keluar) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($p->jumlah_penduduk) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('penduduk.edit', $p) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('penduduk.destroy', $p) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    
                    {{ $penduduk->links() }}
                    <div class="mt-4 flex justify-end">
    <form action="{{ route('penduduk.destroyAll') }}" method="POST" 
          onsubmit="return confirm('Yakin ingin menghapus SEMUA data penduduk? Tindakan ini tidak dapat dibatalkan.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Hapus Semua Data
        </button>
    </form>
</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>