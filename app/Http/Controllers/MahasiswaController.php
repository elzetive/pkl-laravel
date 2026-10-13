<?php

namespace App\Http\Controllers;

use App\Exports\MahasiswaExport;
use App\Imports\MahasiswaImport;
use App\Models\MahasiswaModel;
use App\Models\PenggunaModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MahasiswaModel::orderBy('nim', 'desc')
                ->orderBy('nama', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('kelamin', function ($baris) {
                    return $baris->kelamin == 'L' ? 'Laki-Laki' : 'Perempuan';
                })
                ->editColumn('img', function ($baris) {
                    if ($baris->img && Storage::disk('public')->exists('mahasiswa/' . $baris->img)) {
                        return '<img src="' . asset('storage/mahasiswa/' . $baris->img) . '" width="50" height="50" alt="Foto Mahasiswa" class="img-thumbnail">';
                    }
                    return '<span class="text-muted">-</span>';
                })
                ->addColumn('aksi', function ($baris) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning btn-edit mr-2"
                        data-nim ="' . $baris->nim . '"
                        data-nama = "' . $baris->nama . '"
                        data-kontak = "' .$baris->kontak . '"
                        data-email = "' . $baris->email . '"
                        data-kelamin = "' . $baris ->kelamin . '">
                        <i class="fas fa-edit"></i> Edit
                    </button>';

                    $btn .= '<button type="button" class="btn btn-sm btn-danger btn-hapus"
                        data-nim="' . $baris->nim . '">
                        <i class="fas fa-trash"></i> Hapus
                        </button>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['img', 'aksi'])
                ->make(true);
        }

        return view('admin.data_mahasiswa.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim'       => 'required|string|unique:tbl_mahasiswa,nim',
            'nama'      => 'required|string',
            'kontak'    => 'required|numeric',
            'email'     => 'required|email',
            'kelamin'   => 'required|in:L,P',
            'img'       => 'nullable|image',
        ], [
            'nim.unique'    => 'NIM sudah digunakan!'
        ]);

        $nama_file = null;
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $nama_file = 'foto-mhs-' . round(microtime(true)) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('mahasiswa', $nama_file, 'public');
        }

        MahasiswaModel::create([
            'nim'   => $validated['nim'],
            'nama'  => $validated['nama'],
            'kontak'    => $validated['kontak'],
            'email'     =>$validated['email'],
            'kelamin'   => $validated['kelamin'],
            'img'       => $nama_file,
        ]);

        PenggunaModel::create([
            'nama'  => $request->nama,
            'username'  => $request->nim,
            'sandi'  => sha1($request->nim),
            'peran' => 'M',
            'pin'   =>  '123456',
        ]);


        return redirect()->route('admin.data_mahasiswa')->with('success', 'Data Mahasiswa berhasil disimpan!');
    }

    public function reset()
    {
        MahasiswaModel::truncate();
        return redirect()->route('admin.data_mahasiswa')->with('success', 'Data Mahasiswa berhasil direset!');
    }

    public function destroy($nim)
    {
        $mahasiswa = MahasiswaModel::where('nim', $nim)->firstOrFail();
        $mahasiswa->delete();
        return redirect()->route('admin.data_mahasiswa')->with('success', 'Data Mahasiswa berhasil dihapus!');
    }

    public function update(Request $request, $nim)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'kontak' => 'required|string|max:20',
            'email'     => 'required|email',
            'kelamin'   => 'required|in:L,P',
            'img'       => 'sometimes|nullable|image',
        ]);

        $mahasiswa = MahasiswaModel::where('nim', $nim)->firstOrFail();
        $update_mahasiswa = [
            'nama'  => $validated['nama'],
            'kontak'    => $validated['kontak'],
            'email' => $validated['email'],
            'kelamin'   => $validated['kelamin'],
        ];

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            if ($mahasiswa->img && Storage::disk('public')->exists('mahasiswa/' . $mahasiswa->img)) {
                Storage::disk('public')->delete('mahasiswa/' . $mahasiswa->img);
            }

            $nama_file = 'foto-mahasiswa-' . round(microtime(true)) . '.' . $file->getClientOriginalExtension();

            $update_mahasiswa['img'] = $nama_file;
        }

        $mahasiswa->update($update_mahasiswa);

        return redirect()->route('admin.data_mahasiswa')->with('success', 'Data Mahasiswa berhasil diperbarui!');
    }

    public function export()
    {
        $namaFile = 'Data-Mahasiswa-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new MahasiswaExport(), $namaFile);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel'    => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new MahasiswaImport, $request->file('file_excel'));

        return redirect()->back()->with('success', 'Data mahasiswa berhasil diimport!');
    }

    public function pdf()
    {
        $data_mahasiswa = MahasiswaModel::orderBy('nim', 'asc')->get();

        $pdf    = Pdf::loadView('admin.data_mahasiswa.pdf', compact('data_mahasiswa'))->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Data_Mahasiswa.pdf');
    }

}
