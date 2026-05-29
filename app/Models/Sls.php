<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Sls extends Model
{
    use HasUuids;

    protected $table = 'sls';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'kode_sls', 'nama_sls', 'luas',
        'kode_prov', 'kode_kabupaten', 'kode_kecamatan', 'kode_desa',
        'nama_provinsi', 'nama_kabupaten', 'nama_kecamatan', 'nama_desa',
    ];

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_sls', 'id_sls', 'id_kegiatan');
    }
}
