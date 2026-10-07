<?php

namespace App\Exports;

use App\Models\KelasModel;
use App\Models\DetailKelasModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

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
            $detail = DetailKelasModel::where('id_kelas', $this->id_kelas)->get();

            if ($detail->isEmpty()) {
                return collect([[
                    'kelas'  => $kelas,
                    'detail' => null,
                ]]);
            }

            return $detail->map(function ($item) use ($kelas) {
                return [
                    'kelas'  => $kelas,
                    'detail' => $item,
                ];
            });
        }

        $semuaKelas = KelasModel::with('detailKelas')->get();
        $dataExport = collect();

        foreach ($semuaKelas as $kelas) {
            if ($kelas->detailKelas->isEmpty()) {
                $dataExport->push([
                    'kelas'  => $kelas,
                    'detail' => null,
                ]);
            } else {
                foreach ($kelas->detailKelas as $detail) {
                    $dataExport->push([
                        'kelas'  => $kelas,
                        'detail' => $detail,
                    ]);
                }
            }
        }

        return $dataExport;
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
