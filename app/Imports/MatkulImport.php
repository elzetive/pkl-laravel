<?php

namespace App\Imports;

use App\Models\MatkulModel;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MatkulImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $baris): Model|null
    {
        $kode_matkul = $baris['kode_matkul'] ?? null;
        $nama_matkul = $baris['nama_matkul'] ?? null;

        if (empty($kode_matkul) || empty($nama_matkul)) {
            return null;
        }

        $jumlah_sks = $baris['jumlah_sks'] ?? 0;
        $jml_cpmk   = $baris['jml_cpmk'] ?? 0;

        return new MatkulModel([
            'kode_matkul' => trim((string)$kode_matkul),
            'nama_matkul' => trim($nama_matkul),
            'jumlah_sks'   => (int)$jumlah_sks,
            'jml_cpmk'  => (int)$jml_cpmk,
        ]);
    }
}

