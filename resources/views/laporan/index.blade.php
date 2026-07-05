<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Peramalan Penduduk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Pesan jika belum ada prediksi --}}
                @if(!$hasPrediksi)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Perhatian!</strong>
                        <span class="block sm:inline">Belum ada data prediksi. Silakan lakukan peramalan terlebih dahulu di menu Peramalan.</span>
                    </div>
                @endif

                {{-- Tombol Download (hanya untuk admin) --}}
                @auth
                <div class="flex gap-4 mb-6">
                    <a href="{{ route('laporan.pdf') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded inline-flex items-center" target="_blank">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Download PDF
                    </a>
                    <a href="{{ route('laporan.excel') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download Excel
                    </a>
                </div>
                @endauth

                {{-- Ringkasan MAPE --}}
                @if(!is_null($mape1Tahun) || !is_null($mapeRataRata))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @if(!is_null($mape1Tahun))
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-lg"><strong>MAPE (1 Tahun Sebelumnya):</strong> <span class="text-blue-600 font-bold">{{ number_format($mape1Tahun, 2) }}%</span></p>
                    </div>
                    @endif
                    @if(!is_null($mapeRataRata))
                    <div class="p-4 bg-emerald-50 rounded-lg">
                        <p class="text-lg"><strong>MAPE (Rata‑rata Historis):</strong> <span class="text-emerald-600 font-bold">{{ number_format($mapeRataRata, 2) }}%</span></p>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Tabel Perbandingan --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktual</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prediksi 1 Thn</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Error (%) 1 Thn</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prediksi Rata‑rata</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Error (%) Rata‑rata</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($laporan as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $item['periode'] }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ is_null($item['aktual']) ? '-' : number_format($item['aktual']) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ is_null($item['prediksi_1_tahun']) ? '-' : number_format($item['prediksi_1_tahun']) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ is_null($item['error_persen_1_tahun']) ? '-' : $item['error_persen_1_tahun'].'%' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ is_null($item['prediksi_rata_rata']) ? '-' : number_format($item['prediksi_rata_rata']) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ is_null($item['error_persen_rata_rata']) ? '-' : $item['error_persen_rata_rata'].'%' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 text-sm text-gray-600">
                    <p><strong>Catatan:</strong> Tanda "-" menunjukkan data belum diprediksi. Error (%) selalu bernilai positif (absolut).</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>