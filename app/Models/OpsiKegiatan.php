<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OpsiKegiatan extends Model
{
    use HasUuids;

    protected $table = 'opsi_kegiatan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'kode_kegiatan', 'nama_kegiatan'];

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'id_opsi_kegiatan');
    }
}
