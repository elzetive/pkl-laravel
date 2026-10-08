<?php

namespace App\Exports;

use App\Models\AkademikModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AkademikExport implements FromCollection, WithHeadings,WithMapping, ShouldAutoSize, WithStyles
{
    private $no = 1;
    public function collection(): Collection
    {
        return AkademikModel::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Akademik',
            'Semester',
            'Tahun',
            'Isactive',
        ];
    }

    public function map($baris):array
    {
        return [
            $this->no++,
            $baris->kode_akademik,
            $baris->semester,
            $baris->tahun,
            $baris->isactive,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $hitung_baris = $sheet->getHighestDataRow();

        return [
            'A1:E1' => [
                'borders'   => [
                    'allBorders'    => [
                        'borderStyle'   => Border::BORDER_THICK,
                        'color'         => ['rgb'   => '808080'],
                    ],
                ],
            ],

            'A2:E' . $hitung_baris => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D3D3D3'],
                    ],
                ],
            ],
        ];
    }
}
