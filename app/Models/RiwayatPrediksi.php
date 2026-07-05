<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPrediksi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_prediksi';

    protected $fillable = [
        'periode',
        'prediksi_jumlah',
        'mape',
        'koefisien_json',
        'metode',  
    ];

    protected $casts = [
        'koefisien_json' => 'array', // otomatis ubah JSON ke array
    ];
}