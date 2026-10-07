<?php

namespace App\Imports;

use App\Models\DosenModel;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $baris): Model|null
    {
        $nik = $baris['nik'] ?? null;
        $nama = $baris['nama'] ?? null;

        if (empty($nik) || empty($nama)) {
            return null;
        }

        return new DosenModel([
            'nik'   => trim((string)$nik),
            'nama'  => trim($nama), 
            'kontak'    =>trim((string)$baris['kontak'] ?? '' ),
            'email' => trim($baris['email'] ?? ''),
            'kelamin'   => trim($baris['kelamin'] ?? ''),
            'img'   => null,
        ]);
    }
}
