<?php

namespace App\Http\Controllers;

use App\Imports\MatkulImport;
use App\Models\MatkulModel;
use Illuminate\Http\Request;
use App\Exports\MatkulExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class MatkulController extends Controller
{
    public function index()
    {
        $matkul = MatkulModel::orderBy('kode_matkul', 'desc')
            ->orderBy('nama_matkul', 'asc')
            ->get();

        return view('admin.data_matkul.index', compact('matkul'));
    }

    public function store(Request $request)
    {
        $cek_kode = MatkulModel::where('kode_matkul', $request->kode_matkul)->exists();
        if ($cek_kode) {
            return redirect()->back()->withInput()->with('error', 'Kode Matkul sudah digunakan!');
        }

        $validated = $request->validate([
            'kode_matkul' => 'required|string|unique:tbl_matkul,kode_matkul',
            'nama_matkul' => 'required|string',
            'jumlah_sks'  => 'required|numeric',
            'jml_cpmk'    => 'required|numeric',
        ]);

        MatkulModel::create([
            'kode_matkul' => $validated['kode_matkul'],
            'nama_matkul' => $validated['nama_matkul'],
            'jumlah_sks'  => $validated['jumlah_sks'],
            'jml_cpmk'    => $validated['jml_cpmk'],
        ]);

        return redirect()->route('admin.data_matkul')->with('success', 'Data Matkul berhasil disimpan!');
    }

    public function reset()
    {
        MatkulModel::truncate();
        return redirect()->route('admin.data_matkul')->with('success', 'Data Matkul berhasil direset!');
    }

    public function update(Request $request, $kode_matkul)
    {
        $validated = $request->validate([
            'nama_matkul' => 'required|string',
            'jumlah_sks'  => 'required|numeric',
            'jml_cpmk'    => 'required|numeric',
        ]);

        $matkul = MatkulModel::where('kode_matkul', $kode_matkul)->firstOrFail();

        $matkul->update([
            'nama_matkul' => $validated['nama_matkul'],
            'jumlah_sks'  => $validated['jumlah_sks'],
            'jml_cpmk'    => $validated['jml_cpmk'],
        ]);

        return redirect()->route('admin.data_matkul')->with('success', 'Data Matkul berhasil diperbarui!');
    }

    public function destroy($kode_matkul)
    {
        $matkul = MatkulModel::where('kode_matkul', $kode_matkul)->firstOrFail();
        $matkul->delete();

        return redirect()->route('admin.data_matkul')->with('success', 'Data Matkul berhasil dihapus!');
    }

    public function export()
    {
        $namaFile = 'Data-Matkul-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new MatkulExport, $namaFile);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel'    => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new MatkulImport, $request->file('file_excel'));

        return redirect()->back()->with('success', 'Data mata kuliah berhasil diimport!');
    }

    public function pdf()
    {
        $data_matkul = MatkulModel::orderBy('kode_matkul', 'asc')->get();

        $pdf = Pdf::loadView('admin.data_matkul.pdf', compact('data_matkul'))->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Data_Mata_Kuliah.pdf');
    }
}
