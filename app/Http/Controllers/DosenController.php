<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use App\Models\PenggunaModel;
use App\Exports\DosenExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DosenImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;
class DosenController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DosenModel::orderBy('nik', 'desc')
            ->orderBy('nama', 'asc');

            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('kelamin', function ($baris) {
                return $baris->kelamin == 'L' ? 'Laki-Laki' : 'Perempuan';
            })
            ->editColumn('img', function ($baris) {
                if ($baris->img && Storage::disk('public')->exists('dosen/' . $baris->img)) {
                    return '<img src="' . asset('storage/dosen/' . $baris->img) . '" width="50" height="50" alt="Foto Dosen" class="img-thumbnail">';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('aksi', function ($baris) {
                $btn = '<div class="btn-group" role="group">';

                $btn .= '<button type="button" class="btn btn-sm btn-warning btn-edit mr-2"
                data-nik ="' . $baris->nik . '"
                data-nama ="' . $baris->nama . '"
                data-kontak = "' . $baris->kontak . '"
                data-email = "' . $baris->email . '"
                data-kelamin = "' . $baris->kelamin . '">
                <i class = "fas fa-edit"></i> Edit
                </button>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger btn-hapus"
                data-nik = "' . $baris->nik .'">
                <i class= "fas fa-trash"></i> Hapus
                </button>';
                $btn .= '</div>';

                return $btn;
            })
            ->rawColumns(['img', 'aksi'])
            ->make(true);
        }
        return view('admin.data_dosen.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|unique:tbl_dosen,nik',
            'nama' => 'required|string',
            'kontak' => 'required|numeric',
            'email' => 'required|email',
            'kelamin'   => 'required|string',
            'img'   => 'nullable|image'
        ], [
            'nik.unique' => 'NIK sudah digunakan!'
        ]);

        $nama_file = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $nama_file = 'foto-dosen-' . round(microtime(true)) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('dosen', $nama_file, 'public');
        }

        DosenModel::create([
            'nik'   => $validated['nik'],
            'nama'  => $validated['nama'],
            'kontak'    => $validated['kontak'],
            'email' => $validated['email'],
            'kelamin'   => $validated['kelamin'],
            'img'   => $nama_file,
        ]);

        PenggunaModel::create([
            'nama'  => $request->nama,
            'username'  => $request->nik,
            'sandi'  => sha1($request->nik),
            'peran' => 'D',
            'pin'   =>  '123456',
        ]);

        return redirect()->route('admin.data_dosen')->with('success', 'Data Dosen berhasil disimpan!');
    }

    public function reset()
    {
        DosenModel::truncate();
        return redirect()->route('admin.data_dosen')->with('success', 'Data Dosen berhasil direset!');
    }

    public function destroy($nik)
    {
        $dosen = DosenModel::where('nik', $nik)->firstOrFail();
        $dosen->delete();
        return redirect()->route('admin.data_dosen')->with('success', 'Data Dosen berhasil dihapus!');
    }

    public function update(Request $request, $nik)
    {
        $validated = $request->validate([
            'nama'  => 'required|string',
            'kontak'    => 'required|string|max:20',
            'email' => 'required|email',
            'kelamin'   => 'required|in:L,P',
            'img'   => 'sometimes|nullable|image',
        ]);

        $dosen = DosenModel::where('nik', $nik)->firstOrFail();

        $update_dosen = [
            'nama' => $validated['nama'],
            'kontak' => $validated['kontak'],
            'email' => $validated['email'],
            'kelamin'   => $validated['kelamin'],
        ];

if ($request->hasFile('img')) {
        $file = $request->file('img');

        if ($dosen->img && Storage::disk('public')->exists('dosen/' . $dosen->img)) {
            Storage::disk('public')->delete('dosen/' . $dosen->img);
        }

        $nama_file = 'foto-dosen-' . round(microtime(true)) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('dosen', $nama_file, 'public');

        $update_dosen['img'] = $nama_file;
    }

    $dosen->update($update_dosen);

    return redirect()->route('admin.data_dosen')->with('success', 'Data Dosen berhasil diperbarui!');
    }

    public function export()
    {
        $namaFile = 'Data-Dosen-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new DosenExport, $namaFile);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel'    => 'required|mimes:xls,xlsx'
        ]);

        Excel::import(new DosenImport, $request->file('file_excel'));

        return redirect()->back()->with('success', 'Data dosen berhasil diimpor!');
    }

    public function pdf()
    {
        $data_dosen = DosenModel::orderBy('nama', 'asc')->get();

        $pdf = Pdf::loadView('admin.data_dosen.pdf', compact('data_dosen'))->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Data_Dosen.pdf');
    }
}
