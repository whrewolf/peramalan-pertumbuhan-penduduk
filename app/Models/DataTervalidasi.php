<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataTervalidasi extends Model
{
    use HasFactory;

    protected $table = 'data_tervalidasi';

    protected $fillable = [
        'periode',
        'kelahiran',
        'kematian',
        'migrasi_masuk',
        'migrasi_keluar',
        'jumlah_penduduk',
        'is_interpolated',
    ];

    protected $casts = [
        'is_interpolated' => 'boolean',
    ];
}