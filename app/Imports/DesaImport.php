<?php

namespace App\Imports;

use App\Models\Desa;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DesaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['kode_desa'])) return null;

        return new Desa([
            'id' => Str::uuid(),
            'kode_desa' => $row['kode_desa'],
            'nama_desa' => $row['nama_desa'] ?? null,
            'luas' => $row['luas'] ?? null,
            'kode_prov' => $row['kode_provinsi'] ?? null,
            'nama_provinsi' => $row['nama_provinsi'] ?? null,
            'kode_kabupaten' => $row['kode_kabupaten'] ?? null,
            'nama_kabupaten' => $row['nama_kabupaten'] ?? null,
            'kode_kecamatan' => $row['kode_kecamatan'] ?? null,
            'nama_kecamatan' => $row['nama_kecamatan'] ?? null,
        ]);
    }
}
