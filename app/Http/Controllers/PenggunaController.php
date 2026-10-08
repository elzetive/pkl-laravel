<?php

namespace App\Http\Controllers;

use App\Imports\PenggunaImport;
use App\Models\PenggunaModel;
use App\Exports\PenggunaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PenggunaController extends Controller
{
    public function index ()
    {
        $pengguna = PenggunaModel::orderBy('id', 'desc')
        ->get();

        return view('admin.data_pengguna.index', compact('pengguna'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:tbl_pengguna,username',
            'peran' => 'required|string',
            'nama'  =>  'required|string',
            'password_changed_at'    => 'nullable|date',
        ]);

        PenggunaModel::create([
            'username' => $validated['username'],
            'sandi' => Hash::make($validated['username']),
            'peran' => $validated['peran'],
            'pin'   => '123456',
            'nama'  => $validated['nama'],
            'password_changed_at'    => $validated['password_changed_at'] ?? now(),
        ]);

        return redirect()->route('admin.data_pengguna')->with('success', 'Data Pengguna berhasil disimpan');
    }

    public function reset()
    {
        PenggunaModel::truncate();
        return redirect()->route('admin.data_pengguna')->with('success', 'Data Pengguna berhasil direset!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'sandi' => 'nullable|string',
            'peran' => 'required|string',
            'pin'   => 'nullable|numeric',
            'nama'  => 'required|string',
        ]);

        $pengguna = PenggunaModel::where('id', $id)->firstOrFail();

        $update_pengguna =([
            'username' => $validated['username'],
            'peran'     => $validated['peran'],
            'nama'      => $validated['nama'],
        ]);

        if (!empty($validated['sandi'])) {
            $update_pengguna['sandi'] = Hash::make($validated['sandi']);
            $update_pengguna['password_changed_at'] = now();
        }

        if (!empty($validated['pin'])) {
            $update_pengguna['pin'] = $validated['pin'];
        }

        $pengguna->update($update_pengguna);

        return redirect()->route('admin.data_pengguna')->with('success', 'Data Pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pengguna = PenggunaModel::where('id', $id)->firstOrFail();
        $pengguna->delete();

        return redirect()->route('admin.data_pengguna')->with('success', 'Data Pengguna berhasil dihapus!');
    }

    public function export()
    {
        $namaFile = 'Data-Pengguna-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new PenggunaExport, $namaFile);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new PenggunaImport, $request->file('file_excel'));

        return redirect()->route('admin.data_pengguna')->with('success', 'Data Pengguna berhasil diimpor');
    }

    public function pdf()
    {
        $data_pengguna = PenggunaModel::orderBy('id', 'desc')->get();

        $pdf = Pdf::loadView('admin.data_pengguna.pdf', compact('data_pengguna'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Data-Pengguna-' . date('Y-m-d') . '.pdf');

    }
}
