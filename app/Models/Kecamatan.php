<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';

    protected $fillable = [
        'kode_prov', 'nama_provinsi',
        'kode_kabupaten', 'nama_kabupaten',
        'kode_kecamatan', 'nama_kecamatan',
    ];
}
