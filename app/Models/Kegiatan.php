<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasUuids;

    protected $table = 'kegiatan';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'id_opsi_kegiatan', 'kode_opsi_kegiatan', 'id_user',
        'tahun', 'bulan', 'tanggal_batas_cetak', 'status',
    ];

    protected function casts(): array
    {
        return ['tanggal_batas_cetak' => 'date:Y-m-d'];
    }

    public function opsiKegiatan()
    {
        return $this->belongsTo(OpsiKegiatan::class, 'id_opsi_kegiatan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function blokSensus()
    {
        return $this->belongsToMany(BlokSensus::class, 'kegiatan_blok_sensus', 'id_kegiatan', 'id_blok_sensus');
    }

    public function sls()
    {
        return $this->belongsToMany(Sls::class, 'kegiatan_sls', 'id_kegiatan', 'id_sls');
    }

    public function desa()
    {
        return $this->belongsToMany(Desa::class, 'kegiatan_desa', 'id_kegiatan', 'id_desa');
    }

    public function wilkerstatPeta()
    {
        return $this->hasMany(KegiatanWilkerstatPeta::class, 'id_kegiatan');
    }
}
