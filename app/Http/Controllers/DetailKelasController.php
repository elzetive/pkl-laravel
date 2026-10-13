<?php

namespace App\Http\Controllers;

use App\Exports\KelasExport;
use App\Imports\KelasImport;
use App\Models\DetailKelasModel;
use App\Models\KelasModel;
use App\Models\MahasiswaModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DetailKelasController extends Controller
{
    public function index(Request $request, $id_kelas)
    {
        $user = Auth::user();
        $kelas = KelasModel::with(['akademik', 'matkul', 'jurusan', 'dosen'])->findOrFail($id_kelas);

        if  ($request->ajax()) {
            $data = DetailKelasModel::with('mahasiswa')
                ->where('id_kelas', $id_kelas)
                ->orderBy('nim', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nim', function ($baris) {
                    return $baris->nim;
                })
                ->addColumn('nama', function ($baris) {
                    return $baris->mahasiswa?->nama ?? '-';
                })
                ->addColumn('aksi', function ($baris) use ($kelas, $user) {
                    $peran = $user->peran === 'D' ? 'dosen.' : 'admin.';
                    $actionUrl = route($peran . 'data_detail_kelas.destroy', ['id_kelas' => $kelas->id_kelas, 'nim'=> $baris->nim]);

                    $btn = '<form action="' . $actionUrl.'" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin ingin menghapus data ini?\')">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Hapus
                    </button>';
                    $btn .= '</form>';

                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        $detail_kelas =DetailKelasModel::where('id_kelas', $id_kelas)->get();
        $daftar_mahasiswa = $detail_kelas->pluck('nim')->toArray();
        $data_mahasiswa = MahasiswaModel::whereNotIn('nim', $daftar_mahasiswa)->orderBy('nim', 'asc')->get();
        $peran = $user->peran === 'D' ? 'dosen.data_detail_kelas.index' : 'admin.data_detail_kelas.index';

        return view($peran, compact('kelas', 'detail_kelas', 'data_mahasiswa', 'id_kelas'));
    }

    public function store(Request $request, $id_kelas)
    {
        $request->validate([
            'nim' => 'required|exists:tbl_mahasiswa,nim',
        ]);

        DetailKelasModel::create([
            'id_kelas' => $id_kelas,
            'nim'      => $request->nim,
        ]);

        $peran = Auth::user()->peran === 'D' ? 'dosen.' : 'admin.';

        return redirect()->route($peran . 'data_detail_kelas', $id_kelas)
            ->with('success', 'Data mahasiswa berhasil ditambahkan!');
    }

    public function import(Request $request, $id_kelas)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new KelasImport($id_kelas), $request->file('file_excel'));

        $peran = Auth::user()->peran === 'D' ? 'dosen.' : 'admin.';

        return redirect()->route($peran . 'data_detail_kelas', $id_kelas)
            ->with('success', 'Data mahasiswa berhasil diimpor!');
    }

    public function destroy($id_kelas, $nim)
    {
        DetailKelasModel::where('id_kelas', $id_kelas)
            ->where('nim', $nim)
            ->delete();

        $peran = Auth::user()->peran === 'D' ? 'dosen.' : 'admin.';

        return redirect()->route($peran . 'data_detail_kelas', $id_kelas)
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

        $peran = Auth::user()->peran === 'D' ? 'dosen.data_detail_kelas.pdf' : 'admin.data_detail_kelas.pdf';

        $pdf = Pdf::loadView($peran, compact('kelas'))->setPaper('a4', 'portrait');

        return $pdf->stream('Detail_kelas_' . str_replace(' ', '_', $kelas->nama_kelas) . '.pdf');
    }
}
