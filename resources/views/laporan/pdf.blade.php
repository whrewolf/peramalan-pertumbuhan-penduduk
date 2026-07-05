<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peramalan Penduduk</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
            color: #1e3a8a;
        }
        .subtitle {
            text-align: center;
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 20px;
        }
        .mape-box {
            border: 1px solid #d1d5db;
            padding: 8px;
            margin-bottom: 10px;
            background: #f9fafb;
            display: inline-block;
            width: 45%;
            vertical-align: top;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }
        th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            padding: 6px 4px;
            border: 1px solid #d1d5db;
            text-align: center;
        }
        td {
            padding: 5px 4px;
            border: 1px solid #d1d5db;
            text-align: right;
        }
        td.text-left {
            text-align: left;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .note {
            font-size: 9px;
            color: #6b7280;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>LAPORAN PERAMALAN PENDUDUK</h1>
    <div class="subtitle">Tanggal Cetak: {{ now()->format('d F Y') }}</div>

    @if(!is_null($mape1Tahun) || !is_null($mapeRataRata))
    <div>
        @if(!is_null($mape1Tahun))
        <div class="mape-box">
            <strong>MAPE (1 Tahun Sebelumnya):</strong><br>
            <span style="font-size: 18px; color: #2563eb;">{{ number_format($mape1Tahun, 2) }}%</span>
        </div>
        @endif
        @if(!is_null($mapeRataRata))
        <div class="mape-box">
            <strong>MAPE (Rata‑rata Historis):</strong><br>
            <span style="font-size: 18px; color: #059669;">{{ number_format($mapeRataRata, 2) }}%</span>
        </div>
        @endif
    </div>
    @endif

    <h2 style="font-size: 14px; margin-top: 20px;">Tabel Perbandingan Aktual dan Prediksi</h2>
    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th>Aktual</th>
                <th>Prediksi 1 Thn</th>
                <th>Error (%) 1 Thn</th>
                <th>Prediksi Rata‑rata</th>
                <th>Error (%) Rata‑rata</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $item)
            <tr>
                <td class="text-left">{{ $item['periode'] }}</td>
                <td>{{ is_null($item['aktual']) ? '-' : number_format($item['aktual'], 0, ',', '.') }}</td>
                <td>{{ is_null($item['prediksi_1_tahun']) ? '-' : number_format($item['prediksi_1_tahun'], 0, ',', '.') }}</td>
                <td>{{ is_null($item['error_persen_1_tahun']) ? '-' : $item['error_persen_1_tahun'].'%' }}</td>
                <td>{{ is_null($item['prediksi_rata_rata']) ? '-' : number_format($item['prediksi_rata_rata'], 0, ',', '.') }}</td>
                <td>{{ is_null($item['error_persen_rata_rata']) ? '-' : $item['error_persen_rata_rata'].'%' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="note">
        <p>* Error (%) selalu bernilai positif (absolut). Tanda "-" menunjukkan data belum diprediksi.</p>
        <p>* Model regresi: Y = b0 + b1*Kelahiran + b2*Kematian + b3*Migrasi Masuk + b4*Migrasi Keluar</p>
    </div>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name ?? 'Sistem' }} | Halaman 1 dari 1
    </div>
</body>
</html>