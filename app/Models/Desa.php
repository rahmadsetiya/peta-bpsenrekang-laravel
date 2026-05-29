<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasUuids;

    protected $table = 'desa';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'kode_desa', 'nama_desa', 'luas',
        'kode_prov', 'kode_kabupaten', 'kode_kecamatan',
        'nama_provinsi', 'nama_kabupaten', 'nama_kecamatan',
    ];

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_desa', 'id_desa', 'id_kegiatan');
    }
}
