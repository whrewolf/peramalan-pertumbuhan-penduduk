<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(!$cukupData)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                        <strong>Perhatian!</strong> Data penduduk minimal 6 bulan. Silakan tambahkan data.
                    </div>
                @else
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tahun Peramalan</label>
                        <div class="flex items-center gap-4 mb-4">
                            <select id="tahun" class="rounded-md border-gray-300 shadow-sm">
                                @foreach($tahunTersedia as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(isset($tahunMinimum))
                            <p class="text-xs text-gray-500 mb-3">* Tahun {{ $tahunMinimum }} tidak tersedia karena merupakan tahun awal data.</p>
                        @endif

                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <button id="btn1Tahun" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                1 Tahun Sebelumnya
                            </button>
                            <button id="btnRataRata" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 transition">
                                Rata-rata Historis
                            </button>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-xs text-gray-600">
                            <p><strong>1 Tahun Sebelumnya:</strong> Variabel bebas diambil dari data aktual 1 tahun sebelum tahun yang dipilih.</p>
                            <p class="mt-1"><strong>Rata-rata Historis:</strong> Variabel bebas diambil dari rata-rata semua tahun sebelum tahun yang dipilih.</p>
                        </div>
                    </div>

                    <div id="peringatanKosong" class="hidden bg-yellow-50 border border-yellow-300 text-yellow-800 px-4 py-3 rounded mb-4">
                        <strong>Perhatian:</strong> Beberapa bulan memiliki data tidak lengkap dan diabaikan dalam perhitungan MAPE.
                    </div>

                    <div id="hasil" class="hidden">
                        <div id="judulMetode" class="text-lg font-semibold text-green-800 mb-4"></div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Koefisien Regresi</h3>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-sm">
                                <div class="bg-gray-100 p-2 rounded">b0: <span id="b0"></span></div>
                                <div class="bg-gray-100 p-2 rounded">b1 (Kelahiran): <span id="b1"></span></div>
                                <div class="bg-gray-100 p-2 rounded">b2 (Kematian): <span id="b2"></span></div>
                                <div class="bg-gray-100 p-2 rounded">b3 (Mig. Masuk): <span id="b3"></span></div>
                                <div class="bg-gray-100 p-2 rounded">b4 (Mig. Keluar): <span id="b4"></span></div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <span class="font-semibold">MAPE: </span><span id="mape" class="text-blue-600 font-bold"></span>%
                            <span id="mapeCatatan" class="text-xs text-gray-500 ml-2"></span>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold">Grafik</h3>
                            <canvas id="chart"></canvas>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">Tabel</h3>
                            <table class="min-w-full divide-y divide-gray-200" id="tabelHasil"></table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let myChart;

        async function fetchData(url, body) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(body)
            });
            return await res.json();
        }

        function tampilkanHasil(data) {
            document.getElementById('hasil').classList.remove('hidden');
            document.getElementById('judulMetode').textContent =
                data.metode === '1_tahun'
                    ? 'Hasil: 1 Tahun Sebelumnya (' + data.tahun + ')'
                    : 'Hasil: Rata-rata Historis (' + data.tahun + ')';

            document.getElementById('b0').textContent = data.koefisien.b0.toFixed(2);
            document.getElementById('b1').textContent = data.koefisien.b1.toFixed(4);
            document.getElementById('b2').textContent = data.koefisien.b2.toFixed(4);
            document.getElementById('b3').textContent = data.koefisien.b3.toFixed(4);
            document.getElementById('b4').textContent = data.koefisien.b4.toFixed(4);

            if (data.mape !== null) {
                document.getElementById('mape').textContent = data.mape;
                document.getElementById('mapeCatatan').textContent = '';
            } else {
                document.getElementById('mape').textContent = '-';
                document.getElementById('mapeCatatan').textContent = '(tidak dapat dihitung)';
            }

            if (data.bulan_kosong && data.bulan_kosong.length > 0) {
                document.getElementById('peringatanKosong').classList.remove('hidden');
            } else {
                document.getElementById('peringatanKosong').classList.add('hidden');
            }

            const ctx = document.getElementById('chart').getContext('2d');
            if (myChart) myChart.destroy();
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        { label: 'Data Aktual', data: data.aktual, borderColor: 'rgb(46,125,50)', spanGaps: false },
                        { label: 'Hasil Prediksi', data: data.prediksi, borderColor: 'rgb(234,88,12)', borderDash: [5,5], spanGaps: false }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { title: { display: true, text: 'Tahun ' + data.tahun } },
                    scales: { y: { beginAtZero: false, title: { display: true, text: 'Jumlah Penduduk' } } }
                }
            });

                        // Tabel
            let html = `<thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktual</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prediksi</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Error (%)</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
            </tr></thead><tbody>`;

            data.labels.forEach((label, i) => {
                const aktual = data.aktual[i];
                const pred = data.prediksi[i];
                const predBulat = pred !== null ? Math.round(pred) : null;
                const errorPersen = (aktual && pred && aktual !== 0) ? (Math.abs(aktual - pred) / aktual * 100).toFixed(2) : null;
                let kategori = '-';
                if (data.mape !== null) {
                    if (data.mape < 10) kategori = 'Sangat Baik';
                    else if (data.mape < 20) kategori = 'Baik';
                    else if (data.mape < 50) kategori = 'Layak';
                    else kategori = 'Tidak Akurat';
                }
                const empty = (aktual === null || pred === null);
                const keterangan = empty ? 'Data tidak lengkap' : kategori;
                let warna = 'text-gray-500';
                if (kategori === 'Sangat Baik' || kategori === 'Baik') warna = 'text-green-600';
                else if (kategori === 'Layak') warna = 'text-yellow-600';
                else if (kategori === 'Tidak Akurat') warna = 'text-red-600';

                html += `<tr class="${empty ? 'bg-yellow-50' : ''} border-b border-gray-200">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${label}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${aktual !== null ? aktual.toLocaleString() : '-'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${predBulat !== null ? predBulat.toLocaleString() : '-'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${errorPersen !== null ? errorPersen + '%' : '-'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs font-medium ${warna}">${keterangan}</td>
                </tr>`;
            });
            html += '</tbody>';
            document.getElementById('tabelHasil').innerHTML = html;
        }

        async function proses(metode) {
            const tahun = document.getElementById('tahun').value;
            if (!tahun) return alert('Pilih tahun dahulu.');
            const data = await fetchData('{{ route("peramalan.prediksi") }}', { tahun: tahun, metode: metode });
            if (data && data.success) tampilkanHasil(data);
            else alert(data?.message || 'Error');
        }

        document.getElementById('btn1Tahun').addEventListener('click', () => proses('1_tahun'));
        document.getElementById('btnRataRata').addEventListener('click', () => proses('rata_rata'));
    </script>
</x-app-layout>