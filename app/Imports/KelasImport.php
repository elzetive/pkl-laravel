<?php

namespace App\Imports;

use App\Models\KelasModel;
use App\Models\DetailKelasModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelasImport implements ToCollection, WithHeadingRow
{
    protected $target_id_kelas;

    public function __construct($target_id_kelas = null)
    {
        $this->target_id_kelas = $target_id_kelas;
    }

    public function collection(Collection $baris): void
    {
        foreach ($baris as $data) {
            $kode_akademik  = $data['kode_akademik'] ?? null;
            $id_kelas_excel = $data['id_kelas'] ?? null;
            $kode_matkul    = $data['kode_matkul'] ?? null;
            $kode_jurusan   = $data['kode_jurusan'] ?? null;
            $nik_dosen      = $data['nik'] ?? null;
            $nama_kelas     = $data['nama_kelas'] ?? null;
            $nim            = $data['nim'] ?? null;

            $target_id_kelas = $this->target_id_kelas ?? $id_kelas_excel;

            if (!empty($this->target_id_kelas)) {
                $target_id_kelas = $this->target_id_kelas;
            }
            elseif (!empty($kode_akademik) && !empty($kode_matkul) && !empty($nama_kelas)) {
                $kelas = KelasModel::firstOrCreate(
                    [
                        'kode_akademik' => $kode_akademik,
                        'kode_matkul'   => $kode_matkul,
                        'nama_kelas'    => $nama_kelas,
                    ],
                    [
                        'kode_jurusan'  => $kode_jurusan,
                        'nik'           => $nik_dosen,
                    ]
                );

                $target_id_kelas = $kelas->id_kelas;
            }

            if (!empty($target_id_kelas) && !empty($nim)) {
                DetailKelasModel::firstOrCreate([
                    'id_kelas' => $target_id_kelas,
                    'nim'      => $nim,
                ]);
            }
        }
    }
}
