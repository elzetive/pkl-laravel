<?php

namespace App\Imports;

use App\Models\PenggunaModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenggunaImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $baris): Model|null
    {
        if (empty($baris['username'])) {
            return null;
        }

        return new PenggunaModel([
            'username'            => trim($baris['username']),
            'sandi'               => Hash::make(trim($baris['username'])),
            'nama'                => trim($baris['nama']),
            'peran'               => trim($baris['peran']),
            'pin'                 => '123456',
            'password_changed_at' => now(),
        ]);
    }
}
