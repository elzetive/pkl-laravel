<?php

namespace App\Imports;

use App\Models\MahasiswaModel;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $baris): Model|null
    {
        $nim = $baris['nim'] ?? null;
        $nama = $baris['nama'] ?? null;

        if (empty($nim) || empty($nama)) {
            return null;
        }

        return new MahasiswaModel([
            'nim'   => trim((string)$nim),
            'nama'  => trim($nama),
            'kontak' => trim($baris['kontak'] ?? ''),
            'email' => trim($baris['email'] ?? ''),
            'kelamin'   => trim($baris['kelamin']), 
            'img'   => null,
        ]);
    }
}
