<?php

namespace App\Exports;

use App\Models\Sls;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SlsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sls::orderBy('kode_sls')->get([
            'kode_sls', 'nama_sls', 'luas',
            'kode_prov', 'nama_provinsi',
            'kode_kabupaten', 'nama_kabupaten',
            'kode_kecamatan', 'nama_kecamatan',
            'kode_desa', 'nama_desa',
        ]);
    }

    public function headings(): array
    {
        return [
            'Kode SLS', 'Nama SLS', 'Luas',
            'Kode Provinsi', 'Nama Provinsi',
            'Kode Kabupaten', 'Nama Kabupaten',
            'Kode Kecamatan', 'Nama Kecamatan',
            'Kode Desa', 'Nama Desa',
        ];
    }
}
