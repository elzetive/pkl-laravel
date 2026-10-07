<?php

namespace App\Http\Controllers;

use App\Imports\KelasImport;
use App\Exports\KelasExport;
use Illuminate\Http\Request;
use App\Models\KelasModel;
use App\Models\DetailKelasModel;
use App\Models\MahasiswaModel;
use App\Imports\DetailImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class DetailKelasController extends Controller
{
    public function index($id_kelas)
    {
        $kelas = KelasModel::with(['akademik', 'matkul', 'jurusan', 'dosen'])->findOrFail($id_kelas);
        $detail_kelas = DetailKelasModel::with('mahasiswa')->where('id_kelas', $id_kelas)->get();
        $daftar_mahasiswa = $detail_kelas->pluck('nim')->toArray();
        $data_mahasiswa = MahasiswaModel::whereNotIn('nim', $daftar_mahasiswa)->orderBy('nim', 'asc')->get();

        return view('admin.data_detail_kelas.index', compact('kelas', 'detail_kelas', 'data_mahasiswa', 'id_kelas'));
    }

    public function store(Request $request, $id_kelas)
    {
        $request->validate([
            'nim'   => 'required|exists:tbl_mahasiswa,nim',
        ]);

        DetailKelasModel::create([
            'id_kelas'  => $id_kelas,
            'nim'       => $request->nim,
        ]);

        return redirect()->route('admin.data_detail_kelas', $id_kelas)
                         ->with('success', 'Data mahasiswa berhasil ditambahkan!');
    }

    public function import(Request $request, $id_kelas)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new KelasImport($id_kelas), $request->file('file_excel'));

        return redirect()->route('admin.data_detail_kelas', $id_kelas)
                         ->with('success', 'Data mahasiswa berhasil diimpor!');
    }

    public function destroy($id_kelas, $nim)
    {
        DetailKelasModel::where('id_kelas', $id_kelas)
                ->where('nim', $nim)
                ->delete();

        return redirect()->route('admin.data_detail_kelas', $id_kelas)
                         ->with('success', 'Data mahasiswa berhasil dihapus!');
    }

    public function export($id_kelas)
    {
        $kelas = KelasModel::findOrFail($id_kelas);
        $nama_file = 'detail_kelas_' . str_replace(' ', '_', $kelas->nama_kelas) . '.xlsx';

        return Excel::download(new KelasExport($id_kelas), $nama_file);
    }

    public function pdf($id_kelas)
    {
        $kelas = KelasModel::with(['akademik', 'matkul', 'jurusan', 'dosen', 'detail_kelas.mahasiswa'])->findOrFail($id_kelas);

        $pdf = Pdf::loadView('admin.data_detail_kelas.pdf', compact('kelas'))->setPaper('a4', 'portrait');

        return $pdf->stream('Detail_kelas_' . str_replace(' ', '-', $kelas->nama_kelas) . '.pdf');
    }

}
