<?php

namespace App\Exports;

use App\Models\DetailKelasModel;
use App\Models\KelasModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class KelasExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected $id_kelas;

    private $no = 0;

    public function __construct($id_kelas = null)
    {
        $this->id_kelas = $id_kelas;
    }

    public function collection(): Collection
    {
        if ($this->id_kelas) {
            $kelas = KelasModel::find($this->id_kelas);
            $detail_kelas = DetailKelasModel::where('id_kelas', $this->id_kelas)->get();

            if ($detail_kelas->isEmpty()) {
                return collect([[
                    'kelas' => $kelas,
                    'detail' => null,
                ]]);
            }

            return $detail_kelas->map(function ($item) use ($kelas) {
                return [
                    'kelas' => $kelas,
                    'detail' => $item,
                ];
            });
        }

        $semua_kelas = KelasModel::with('detail_kelas')->get();
        $data_ekspor = collect();

        foreach ($semua_kelas as $kelas) {
            if ($kelas->detail_kelas->isEmpty()) {
                $data_ekspor->push([
                    'kelas' => $kelas,
                    'detail' => null,
                ]);
            } else {
                foreach ($kelas->detail_kelas as $detail) {
                    $data_ekspor->push([
                        'kelas' => $kelas,
                        'detail' => $detail,
                    ]);
                }
            }
        }

        return $data_ekspor;
    }

    public function map($baris): array
    {
        $this->no++;
        $kelas = $baris['kelas'];
        $detail = $baris['detail'];

        return [
            $this->no,
            $kelas->kode_akademik ?? '',
            $kelas->id_kelas ?? '',
            $kelas->kode_matkul ?? '',
            $kelas->kode_jurusan ?? '',
            $kelas->nik ?? '',
            $kelas->nama_kelas ?? '',
            $detail->nim ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Akademik',
            'Id Kelas',
            'Kode Matkul',
            'Kode Jurusan',
            'NIK',
            'Nama Kelas',
            'NIM',
        ];
    }

    public function title(): string
    {
        return 'Data Mahasiswa';
    }
}