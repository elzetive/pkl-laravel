<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasModel;
use App\Models\AkademikModel;
use App\Models\MatkulModel;
use App\Models\JurusanModel;
use App\Models\DosenModel;
use App\Exports\KelasExport;
use App\Imports\KelasImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class KelasController extends Controller
{
public function index(Request $request)
    {
        $user = auth()->user();
        $akademik = AkademikModel::all();
        $pilih_akademik = $request->input('kode_akademik');

        if (!$pilih_akademik && $akademik->isNotEmpty()) {
            $pilih_akademik = $akademik->first()->kode_akademik;
        }

        if ($request->ajax()) {
            $query = KelasModel::with(['akademik', 'matkul', 'jurusan', 'dosen']);
            if ($pilih_akademik) {
                $query->where('kode_akademik', $pilih_akademik);
            }

            if ($user->peran === 'D') {
                $nik_dosen = $user->nik ?? $user->username;
                $query->where('nik', $nik_dosen);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('akademik', function ($baris) {
                    $is_ganjil = in_array(strtoupper($baris->akademik->semester ?? ''), ['GL', '1', 'GANJIL']);
                    return $baris->akademik ? $baris->akademik->tahun . '-' . ($is_ganjil ? 'Ganjil' : 'Genap') : $baris->kode_akademik;
                })
                ->addColumn('matkul', function ($baris) {
                    return $baris->matkul->nama_matkul ?? $baris->kode_matkul;
                })
                ->addColumn('jurusan', function ($baris) {
                    return $baris->jurusan->nama_jurusan ?? $baris->kode_jurusan;
                })
                ->addColumn('dosen', function ($baris) {
                    return $baris->dosen->nama ?? $baris->nik;
                })
                ->addColumn('aksi', function ($baris) use ($pilih_akademik) {
                    $btn = '<div class="btn-group" role="group">';

                    $btn = '<button type="button" class="btn btn-warning btn-sm mr-1" data-toggle="modal" data-target= "#modal-edit"
                    data-id="' . $baris->id_kelas .'"
                    data-kode_akademik="' . $baris->kode_akademik .'"
                    data-kode_matkul ="'.$baris->kode_matkul.'"
                    data-kode_jurusan = "'.$baris->kode_jurusan.'"
                    data-nik    = "'.$baris->nik.'"
                    data-nama_kelas = "'.$baris->nama_kelas.'">
                    <i class="fas fa-edit"></i> Edit
                    </button>';

                    $btn .= '<form action="' .route('admin.data_kelas_matkul.destroy', $baris->id_kelas) . '" method="POST" class="d-inline mr-1" onsubmit="return confirm(\'Yakin ingin menghapus kelas ini?\')">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="filter_akademik" value="' . $pilih_akademik .'">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                    </form>';

                   $btn .= '<a href="'.route('admin.data_detail_kelas', $baris->id_kelas).'" class="btn btn-primary btn-sm mr-1" <i class="fas fa-list"></i> Detail
                        </a>';
                    $btn .= '<a href="'.route('admin.data_pertemuan', $baris->id_kelas).'" class="btn btn-danger btn-sm">
                        <i class="fas fa-calendar-alt"></i> Pertemuan
                        </a>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        $matkul = MatkulModel::all();
        $jurusan = JurusanModel::all();
        $dosen = DosenModel::all();

        if ($user->peran === 'D') {
            return view('dosen.data_kelas_matkul.index', compact('akademik', 'pilih_akademik', 'matkul', 'jurusan', 'dosen'));
        }

        return view('admin.data_kelas_matkul.index', compact('akademik', 'pilih_akademik', 'matkul', 'jurusan', 'dosen'));
    }
        public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_akademik' => 'required',
            'kode_matkul'   => 'required',
            'kode_jurusan'  => 'required',
            'nik'           => 'required',
            'nama_kelas'    => 'required|string|max:255',
        ]);

        KelasModel::create($validated);

        return redirect()->route('admin.data_kelas_matkul', ['kode_akademik' => $request->kode_akademik])
            ->with('success', 'Data kelas berhasil ditambahkan!');
    }

    public function update(Request $request, $id_kelas)
    {
        $validated = $request->validate([
            'kode_akademik' => 'required',
            'kode_matkul'   => 'required',
            'kode_jurusan'  => 'required',
            'nik'           => 'required',
            'nama_kelas'    => 'required|string|max:255',
        ]);

        $kelas = KelasModel::findOrFail($id_kelas);
        $kelas->update($validated);

        $akademik_terpilih = $request->input('filter_akademik', $request->kode_akademik);

        return redirect()->route('admin.data_kelas_matkul', ['kode_akademik' => $akademik_terpilih])
            ->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy(Request $request, $id_kelas)
    {
        $kelas = KelasModel::findOrFail($id_kelas);
        $akademik_terpilih = $request->input('filter_akademik', $kelas->kode_akademik);

        $kelas->delete();

        return redirect()->route('admin.data_kelas_matkul', ['kode_akademik' => $akademik_terpilih])
            ->with('success', 'Data kelas berhasil dihapus!');
    }

    public function export()
    {
        return Excel::download(new KelasExport(), 'data_kelas_lengkap.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new KelasImport(), $request->file('file_excel'));

        return redirect()->back()->with('success', 'Data kelas berhasil diimpor!');
    }

    public function pdf(Request $request)
    {
        $pilih_akademik = $request->input('kode_akademik');

        if (!$pilih_akademik) {
            $default_akademik = AkademikModel::first();
            $pilih_akademik = $default_akademik ? $default_akademik->kode_akademik : null;
        }

        $query_kelas = KelasModel::with(['akademik', 'matkul', 'jurusan', 'dosen']);

        if ($pilih_akademik) {
            $query_kelas->where('kode_akademik', $pilih_akademik);
        }

        $data_kelas = $query_kelas->orderBy('id_kelas', 'asc')->get();

        $pdf = Pdf::loadView('admin.data_kelas_matkul.pdf', compact('data_kelas'))->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Data_Kelas_Matkul.pdf');
    }

}
