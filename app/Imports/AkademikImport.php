<?php
namespace App\Imports;

use App\Models\AkademikModel;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AkademikImport implements ToModel, WithHeadingRow
{
    public function model(array $baris): ?Model
    {
        $kodeAkademik = $baris['kode_akademik'] ?? $baris['kode akademik'] ?? null;
        $tahun        = $baris['tahun'] ?? $baris['tahun_akademik'] ?? $baris['tahun akademik'] ?? null;
        $semester     = $baris['semester'] ?? null;

        if (!$kodeAkademik) {
            return null;
        }

        return AkademikModel::updateOrCreate(
            ['kode_akademik' => $kodeAkademik],
            [
                'tahun'    => $tahun,
                'semester' => $semester,
            ]
        );
    }
}
