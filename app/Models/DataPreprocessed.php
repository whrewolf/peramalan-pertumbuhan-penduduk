<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPreprocessed extends Model
{
    use HasFactory;

    protected $table = 'data_preprocessed';

    protected $fillable = [
        'periode',
        'kelahiran',
        'kematian',
        'migrasi_masuk',
        'migrasi_keluar',
        'jumlah_penduduk',
    ];
}