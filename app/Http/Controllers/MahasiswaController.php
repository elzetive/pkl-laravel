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
class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = MahasiswaModel::orderBy('nim', 'desc')
            ->orderBy('nama', 'asc')
            ->get();

        return view('admin.data_mahasiswa.index', compact('mahasiswa'));
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
        $updateMahasiswa = [
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

            $updateMahasiswa['img'] = $nama_file;
        }

        $mahasiswa->update($updateMahasiswa);

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
