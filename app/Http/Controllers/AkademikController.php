<?php

namespace App\Http\Controllers;

use App\Models\AkademikModel;
use Illuminate\Http\Request;
use App\Exports\AkademikExport;
use App\Imports\AkademikImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class AkademikController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AkademikModel::orderBy('tahun', 'desc')
                    ->orderBy('semester', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('semester', function ($baris) {
                    if ($baris->semester === 'gl'){
                        return 'Ganjil';
                    } else {
                        return 'Genap';
                    }

                })
                ->editColumn('isactive', function ($baris) {
                    if ($baris->isactive == 1) {
                        return '<span>Aktif</span>';
                    }
                    return '<span>Tidak Aktif</span>';
                })
                ->addColumn('aksi', function ($baris) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning btn-edit mr-2"
                        data-kode_akademik = "' . $baris->kode_akademik . '"
                        data-semester = "' . $baris->semester .'"
                        data-tahun = "' . $baris->tahun .'"
                        data-isactive = "' . $baris->isactive .'">
                        <i class="fas fa-edit"></i> Edit
                        </button> ';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-hapus"
                                data-kode_akademik = "' . $baris->kode_akademik .'">
                                <i class="fas fa-trash"></i> Hapus
                            </button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['isactive', 'aksi'])
                ->make(true);
        }
        return view('admin.data_akademik.index');
    }

    public function store(Request $request)
    {
        $cek_kode = AkademikModel::where('kode_akademik', $request->kode_akademik)->exists();
        if ($cek_kode) {
            return redirect()->back()->withInput()->with('error', 'Kode Akademik sudah digunakan!');
        }

        $validated = $request->validate([
            'kode_akademik' => 'required|string|unique:tbl_akademik,kode_akademik',
            'semester'      => 'required|string',
            'tahun'         => 'required|string|max:4',
            'isactive'      => 'required|in:0,1',
        ]);

        AkademikModel::create([
            'kode_akademik' => $validated['kode_akademik'],
            'semester'      => $validated['semester'],
            'tahun'         => $validated['tahun'],
            'isactive'      => $validated['isactive'],
        ]);

        return redirect()->route('admin.data_akademik')->with('success', 'Data Akademik berhasil disimpan!');
    }

    public function reset()
    {
        AkademikModel::truncate();
        return redirect()->route('admin.data_akademik')->with('success', 'Data Akademik berhasil direset!');
    }

    public function update(Request $request, $kode_akademik)
    {
        $validated = $request->validate([
            'semester' => 'required|string',
            'tahun'    => 'required|string|max:4',
            'isactive' => 'required|in:0,1',
        ]);

        $akademik = AkademikModel::where('kode_akademik', $kode_akademik)->firstOrFail();

        $akademik->update([
            'semester' => $validated['semester'],
            'tahun'    => $validated['tahun'],
            'isactive' => $validated['isactive'],
        ]);

        return redirect()->route('admin.data_akademik')->with('success', 'Data Akademik berhasil diperbarui!');
    }

    public function destroy($kode_akademik)
    {
        $akademik = AkademikModel::where('kode_akademik', $kode_akademik)->firstOrFail();
        $akademik->delete();

        return redirect()->route('admin.data_akademik')->with('success', 'Data Akademik berhasil dihapus!');
    }

    public function export()
    {
        $nama_file = "Data-Akademik-" . date('Y-m-d') . ".xlsx";

        return Excel::download(new AkademikExport, $nama_file);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xls,xlsx|max:2048',
        ], [
            'file_excel.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_excel.mimes' => 'Format file harus .xls atau .xlsx',
        ]);

        Excel::import(new AkademikImport, $request->file('file_excel'));

        return redirect()->back()->with('success', 'Data Akademik berhasil diimpor!');
    }

    public function pdf()
    {
        $data_akademik = AkademikModel::orderBy('tahun', 'desc')
            ->orderBy('semester', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.data_akademik.pdf', compact('data_akademik'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Data_Akademik.pdf');
    }
}
