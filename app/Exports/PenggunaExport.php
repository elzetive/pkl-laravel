<?php

namespace App\Exports;

use App\Models\PenggunaModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PenggunaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $no = 1;

    public function collection(): Collection
    {
        return PenggunaModel::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Username',
            'Nama Lengkap',
            'Peran',
        ];
    }

    public function map($row): array
    {
        return [
            $this->no++,
            $row->username,
            $row->nama,
            $row->peran,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $hitung_baris = $sheet->getHighestDataRow();
        $hitung_kolom = $sheet->getHighestColumn();
        $rentang = "A1:{$hitung_kolom}{$hitung_baris}";

        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0'],
                ],
            ],

            $rentang => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'D3D3D3'],
                    ],
                ],
            ],
        ];
    }
}
