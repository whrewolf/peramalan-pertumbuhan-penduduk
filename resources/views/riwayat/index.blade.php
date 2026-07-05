<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Prediksi</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($periodeUnik->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    Belum ada riwayat prediksi. Silakan lakukan prediksi terlebih dahulu di menu Peramalan.
                </div>
            @else
                <!-- Grafik 1 Tahun -->
                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-2">Prediksi 1 Tahun Sebelumnya</h3>
                    <canvas id="chart1Tahun"></canvas>
                </div>

                <!-- Grafik Rata-rata -->
                <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-2">Prediksi Rata‑rata Historis</h3>
                    <canvas id="chartRataRata"></canvas>
                </div>

                <!-- Tabel Riwayat -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Tabel Riwayat Prediksi</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prediksi 1 Thn</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">MAPE 1 Thn</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prediksi Rata‑rata</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">MAPE Rata‑rata</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($periodeUnik as $periode)
                                    @php
                                        $r1 = $riwayat1Tahun->firstWhere('periode', $periode);
                                        $r2 = $riwayatRataRata->firstWhere('periode', $periode);
                                        $tanggal = $r1?->created_at ?? $r2?->created_at;
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $periode }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $r1 ? number_format($r1->prediksi_jumlah) : '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $r1 && $r1->mape ? number_format($r1->mape, 2).'%' : '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $r2 ? number_format($r2->prediksi_jumlah) : '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $r2 && $r2->mape ? number_format($r2->mape, 2).'%' : '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $tanggal ? $tanggal->format('d M Y H:i') : '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <form action="{{ route('riwayat.destroyByPeriode', $periode) }}" method="POST" onsubmit="return confirm('Hapus semua riwayat untuk periode {{ $periode }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Hapus Semua -->
                    <div class="mt-4 flex justify-end">
                        <form action="{{ route('riwayat.destroyAll') }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus SEMUA riwayat prediksi?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Hapus Semua Riwayat
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik 1 Tahun
        @if($riwayat1Tahun->isNotEmpty())
        new Chart(document.getElementById('chart1Tahun'), {
            type: 'line',
            data: {
                labels: {{ Js::from($labels1) }},
                datasets: [{
                    label: 'Prediksi 1 Thn',
                    data: {{ Js::from($prediksi1) }},
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: { title: { display: false } },
                scales: { y: { title: { display: true, text: 'Jumlah Penduduk' } } }
            }
        });
        @endif

        // Grafik Rata-rata
        @if($riwayatRataRata->isNotEmpty())
        new Chart(document.getElementById('chartRataRata'), {
            type: 'line',
            data: {
                labels: {{ Js::from($labelsRata) }},
                datasets: [{
                    label: 'Prediksi Rata‑rata',
                    data: {{ Js::from($prediksiRata) }},
                    borderColor: 'rgb(34, 197, 94)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: { title: { display: false } },
                scales: { y: { title: { display: true, text: 'Jumlah Penduduk' } } }
            }
        });
        @endif
    </script>
    @endpush
</x-app-layout>