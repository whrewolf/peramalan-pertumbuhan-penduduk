<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Analisis Data Kependudukan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ================== PREPROCESSING ================== --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Preprocessing Data
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    {{-- Missing Value --}}
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                            <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span> Missing Value
                        </h4>
                        @if(count($missingValues) > 0)
                            <p class="text-sm text-gray-600 mb-2">Ditemukan <span class="font-bold text-yellow-600">{{ count($missingValues) }}</span> data dengan nilai kosong:</p>
                            <ul class="list-disc list-inside text-sm text-gray-500 max-h-32 overflow-y-auto">
                                @foreach($missingValues as $mv)
                                    <li>{{ $mv->periode ?? '-' }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-green-600">✅ Tidak ada missing value</p>
                        @endif
                    </div>

                    {{-- Interpolasi --}}
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                            <span class="w-3 h-3 bg-blue-400 rounded-full mr-2"></span> Interpolasi
                        </h4>
                        @if(count($missingValues) > 0)
                            <p class="text-sm text-gray-600">
                                Sistem otomatis mengisi <span class="font-bold">{{ count($missingValues) }}</span> data kosong menggunakan <em>interpolasi linear</em> (rata‑rata data sebelum & sesudah).
                            </p>
                            <p class="text-xs text-gray-400 mt-1">Data yang telah diinterpolasi digunakan untuk grafik di bawah.</p>
                        @else
                            <p class="text-sm text-gray-600">Tidak diperlukan interpolasi.</p>
                        @endif
                    </div>

                    {{-- Validasi --}}
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                            <span class="w-3 h-3 bg-red-400 rounded-full mr-2"></span> Validasi
                        </h4>
                        @if(count($validasiErrors) > 0)
                            <p class="text-sm text-red-600 mb-2">Ditemukan <span class="font-bold">{{ count($validasiErrors) }}</span> anomali data:</p>
                            <ul class="list-disc list-inside text-sm text-red-500 max-h-32 overflow-y-auto">
                                @foreach($validasiErrors as $err)
                                    <li>{{ $err['periode'] }} : {{ $err['errors'] }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-green-600">✅ Semua data valid</p>
                        @endif
                    </div>
                </div>

                {{-- TOMBOL SIMPAN & VALIDASI DATA --}}
                <div class="border-t border-gray-200 pt-4 mb-4">
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('analisis.simpan') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                            Simpan & Validasi Data
                        </button>
                    </form>
                    <p class="text-xs text-gray-500 mt-1">
                        Ini akan menyimpan hasil interpolasi sebagai data tervalidasi untuk Peramalan (tanpa mengubah data asli).
                    </p>
                    @if($dataTervalidasi > 0)
                        <p class="text-xs text-green-600 mt-1">✅ Saat ini sudah ada <strong>{{ $dataTervalidasi }}</strong> data tervalidasi.</p>
                    @else
                        <p class="text-xs text-yellow-600 mt-1">⚠️ Belum ada data tervalidasi. Silakan klik tombol di atas.</p>
                    @endif
                </div>
            </div>

            {{-- ================== GRAFIK ANALISIS ================== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h4 class="font-semibold mb-2">Kelahiran per Periode</h4>
                    <canvas id="chartKelahiran"></canvas>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h4 class="font-semibold mb-2">Kematian per Periode</h4>
                    <canvas id="chartKematian"></canvas>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h4 class="font-semibold mb-2">Migrasi Masuk per Periode</h4>
                    <canvas id="chartMigrasiMasuk"></canvas>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h4 class="font-semibold mb-2">Migrasi Keluar per Periode</h4>
                    <canvas id="chartMigrasiKeluar"></canvas>
                </div>
            </div>

            {{-- Grafik Gabungan --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h4 class="font-semibold mb-2">Tren Semua Variabel dan Jumlah Penduduk</h4>
                <canvas id="chartGabungan"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = {{ Js::from($labels) }};
        const kelahiran = {{ Js::from($kelahiran) }};
        const kematian = {{ Js::from($kematian) }};
        const migrasiMasuk = {{ Js::from($migrasiMasuk) }};
        const migrasiKeluar = {{ Js::from($migrasiKeluar) }};
        const penduduk = {{ Js::from($penduduk) }};

        const colorKelahiran = 'rgb(59, 130, 246)';
        const colorKematian = 'rgb(234, 88, 12)';
        const colorMigrasiMasuk = 'rgb(34, 197, 94)';
        const colorMigrasiKeluar = 'rgb(168, 85, 247)';
        const colorPenduduk = 'rgb(0, 0, 0)';

        const lineOptions = (title) => ({
            responsive: true,
            plugins: { title: { display: true, text: title } }
        });

        new Chart(document.getElementById('chartKelahiran'), {
            type: 'line',
            data: { labels, datasets: [{ label: 'Kelahiran', data: kelahiran, borderColor: colorKelahiran }] },
            options: lineOptions('Kelahiran per Periode')
        });

        new Chart(document.getElementById('chartKematian'), {
            type: 'line',
            data: { labels, datasets: [{ label: 'Kematian', data: kematian, borderColor: colorKematian }] },
            options: lineOptions('Kematian per Periode')
        });

        new Chart(document.getElementById('chartMigrasiMasuk'), {
            type: 'line',
            data: { labels, datasets: [{ label: 'Migrasi Masuk', data: migrasiMasuk, borderColor: colorMigrasiMasuk }] },
            options: lineOptions('Migrasi Masuk per Periode')
        });

        new Chart(document.getElementById('chartMigrasiKeluar'), {
            type: 'line',
            data: { labels, datasets: [{ label: 'Migrasi Keluar', data: migrasiKeluar, borderColor: colorMigrasiKeluar }] },
            options: lineOptions('Migrasi Keluar per Periode')
        });

        new Chart(document.getElementById('chartGabungan'), {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label: 'Penduduk', data: penduduk, borderColor: colorPenduduk, borderWidth: 2, yAxisID: 'y' },
                    { label: 'Kelahiran', data: kelahiran, borderColor: colorKelahiran, yAxisID: 'y1' },
                    { label: 'Kematian', data: kematian, borderColor: colorKematian, yAxisID: 'y1' },
                    { label: 'Migrasi Masuk', data: migrasiMasuk, borderColor: colorMigrasiMasuk, yAxisID: 'y1' },
                    { label: 'Migrasi Keluar', data: migrasiKeluar, borderColor: colorMigrasiKeluar, yAxisID: 'y1' }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index' },
                plugins: { title: { display: true, text: 'Tren Semua Variabel' } },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Jumlah Penduduk' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        title: { display: true, text: 'Jumlah (Kelahiran, Kematian, Migrasi)' }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>