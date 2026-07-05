<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    use HasFactory;

    protected $table = 'penduduk';
    
    protected $fillable = [
        'periode',
        'jumlah_penduduk',
        'kelahiran',
        'kematian',
        'migrasi_masuk',
        'migrasi_keluar'
    ];

    // Casting agar periode dianggap sebagai string (misal "2025-01")
    protected $casts = [
        'periode' => 'string',
    ];
}