<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Baris pertama: Ringkasan --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <!-- Jumlah Penduduk -->
                <div class="bg-white rounded-xl shadow-sm border border-green-100 p-5 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-700">Jumlah Penduduk</p>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($totalPenduduk) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Kelahiran -->
                <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-5 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-700">Total Kelahiran</p>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($totalKelahiran) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Kematian -->
                <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-red-700">Total Kematian</p>
                            <p class="text-2xl font-bold text-red-900">{{ number_format($totalKematian) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Migrasi Masuk -->
                <div class="bg-white rounded-xl shadow-sm border border-teal-100 p-5 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-teal-700">Migrasi Masuk</p>
                            <p class="text-2xl font-bold text-teal-900">{{ number_format($totalMigrasiMasuk) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Migrasi Keluar -->
                <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-5 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-orange-700">Migrasi Keluar</p>
                            <p class="text-2xl font-bold text-orange-900">{{ number_format($totalMigrasiKeluar) }}</p>
                        </div>
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Migrasi Bersih -->
                <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-5 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-700">Migrasi Bersih</p>
                            <p class="text-2xl font-bold {{ $migrasiBersih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $migrasiBersih > 0 ? '+' : '' }}{{ number_format($migrasiBersih) }}
                            </p>
                        </div>
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grafik --}}
            @if(count($tahunLabels) > 0)
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
                <h3 class="text-lg font-semibold text-green-900 mb-4">Grafik Pertumbuhan Penduduk (Bulanan)</h3>
                <canvas id="pendudukChart"></canvas>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-8 text-center text-gray-500">
                <p>Belum ada data penduduk. Silakan tambahkan data melalui menu <strong>Data Penduduk</strong>.</p>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if(count($tahunLabels) > 0)
        const ctx = document.getElementById('pendudukChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {{ Js::from($tahunLabels) }},
                datasets: [{
                    label: 'Jumlah Penduduk',
                    data: {{ Js::from($jumlahPendudukValues) }},
                    borderColor: '#2e7d32',
                    backgroundColor: 'rgba(46,125,50,0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Pertumbuhan Penduduk per Periode' }
                },
                scales: {
                    y: { beginAtZero: false }
                }
            }
        });
        @endif
    </script>
    @endpush
</x-app-layout>