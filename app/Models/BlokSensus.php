<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BlokSensus extends Model
{
    use HasUuids;

    protected $table = 'blok_sensus';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'kode_bs', 'nama_bs', 'luas',
        'kode_prov', 'kode_kabupaten', 'kode_kecamatan', 'kode_desa', 'kode_sls',
        'nama_provinsi', 'nama_kabupaten', 'nama_kecamatan', 'nama_desa', 'nama_sls',
    ];

    public function kegiatan()
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_blok_sensus', 'id_blok_sensus', 'id_kegiatan');
    }

    public function petaUtama()
    {
        return $this->hasMany(KegiatanWilkerstatPeta::class, 'id_wilkerstat')
            ->where('wilkerstat_type', 'blok_sensus')
            ->whereNull('id_parent_peta');
    }
}
