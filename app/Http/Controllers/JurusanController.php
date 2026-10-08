<?php

namespace App\Http\Controllers;

use App\Exports\JurusanExport;
use App\Imports\JurusanImport;
use App\Models\JurusanModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = JurusanModel::orderBy('kode_jurusan', 'desc')
        ->orderBy('nama_jurusan', 'asc')
        ->get();

        return view('admin.data_jurusan.index', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_jurusan'  => 'required|string|unique:tbl_jurusan,kode_jurusan',
            'nama_jurusan'      => 'required|string',
        ], [
            'kode_jurusan.unique'   => 'Kode Jurusan sudah digunakan!'
        ]);

        JurusanModel::create([
            'kode_jurusan'  => $validated['kode_jurusan'],
            'nama_jurusan'  => $validated['nama_jurusan'],
        ]);

        return redirect()->route('admin.data_jurusan')->with('success', 'Data Jurusan berhasil disimpan!');
    }

    public function reset()
    {
        JurusanModel::truncate();
        return redirect()->route('admin.data_jurusan')->with('success', 'Data Jurusan berhasil direset!');
    }

    public function destroy($kode_jurusan)
    {
        $jurusan = JurusanModel::where('kode_jurusan', $kode_jurusan)->firstOrFail();
        $jurusan->delete();

        return redirect()->route('admin.data_jurusan')->with('success', 'Data Jurusan berhasil dihapus!');
    }

    public function update(Request $request, $kode_jurusan)
    {
        $validated = $request->validate([
            'kode_jurusan'  => 'required|string',
            'nama_jurusan'  => 'required|string',
        ]);

        $jurusan = JurusanModel::where('kode_jurusan', $kode_jurusan)->firstOrFail();
        $update_jurusan = [
            'kode_jurusan'  => $validated['kode_jurusan'],
            'nama_jurusan'  => $validated['nama_jurusan'],
        ];

        $jurusan->update($update_jurusan);
        return redirect()->route('admin.data_jurusan')->with('success', 'Data Jurusan berhasil diperbarui!');
    }

    public function export()
    {
        $namaFile = 'Data-Jurusan-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new JurusanExport(), $namaFile);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel'    => 'required|mimes:xls,xlsx'
        ]);

        Excel::import(new JurusanImport, $request->file('file_excel'));

        return redirect()->back()->with('success', 'Data jurusan berhasil diimpor!');
    }

    public function pdf()
    {
        $data_jurusan = JurusanModel::orderBy('kode_jurusan', 'asc')->get();
        $pdf = Pdf::loadView('admin.data_jurusan.pdf', compact('data_jurusan'))->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Data_Jurusan.pdf');
    }

}
