<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class KegiatanWilkerstatPeta extends Model
{
    use HasUuids;

    protected $table = 'kegiatan_wilkerstat_peta';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'id_kegiatan', 'wilkerstat_type', 'id_wilkerstat',
        'jenis_peta', 'id_parent_peta', 'file_path', 'nama_file',
        'uploaded_at', 'uploader',
    ];

    protected function casts(): array
    {
        return ['uploaded_at' => 'datetime'];
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan');
    }

    public function parent()
    {
        return $this->belongsTo(KegiatanWilkerstatPeta::class, 'id_parent_peta');
    }

    public function insets()
    {
        return $this->hasMany(KegiatanWilkerstatPeta::class, 'id_parent_peta');
    }

    public function getWilkerstatAttribute()
    {
        return match ($this->wilkerstat_type) {
            'blok_sensus' => BlokSensus::find($this->id_wilkerstat),
            'sls' => Sls::find($this->id_wilkerstat),
            'desa' => Desa::find($this->id_wilkerstat),
            default => null,
        };
    }
}
