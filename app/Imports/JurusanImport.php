<?php

namespace App\Imports;

use App\Models\JurusanModel;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JurusanImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $baris): Model|null
    {
        $kode_jurusan = $baris['kode_jurusan'] ?? null;
        $nama_jurusan = $baris['nama_jurusan'] ?? null;

        if (empty($kode_jurusan) || empty($nama_jurusan)) {
            return null;
        }

        return new JurusanModel([
            'kode_jurusan'  => trim($kode_jurusan),
            'nama_jurusan'  => trim($nama_jurusan),
        ]);
    }
}
