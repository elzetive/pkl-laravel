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

class DosenController extends Controller
{
    public function index()
    {
        $dosen = DosenModel::orderBy('nik', 'desc')
            ->orderBy('nama', 'asc')
            ->get();

        return view('admin.data_dosen.index', compact('dosen'));
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

        $updateDosen = [
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

        $updateDosen['img'] = $nama_file;
    }

    $dosen->update($updateDosen);

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
