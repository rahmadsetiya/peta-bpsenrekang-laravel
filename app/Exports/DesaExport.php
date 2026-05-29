<?php

namespace App\Exports;

use App\Models\Desa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DesaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Desa::orderBy('kode_desa')->get([
            'kode_desa', 'nama_desa', 'luas',
            'kode_prov', 'nama_provinsi',
            'kode_kabupaten', 'nama_kabupaten',
            'kode_kecamatan', 'nama_kecamatan',
        ]);
    }

    public function headings(): array
    {
        return [
            'Kode Desa', 'Nama Desa', 'Luas',
            'Kode Provinsi', 'Nama Provinsi',
            'Kode Kabupaten', 'Nama Kabupaten',
            'Kode Kecamatan', 'Nama Kecamatan',
        ];
    }
}
