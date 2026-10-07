<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasModel;
use App\Models\PertemuanModel;

class PertemuanController extends Controller
{
    public function index($id_kelas)
    {
        $kelas = KelasModel::with(['dosen', 'matkul'])->findOrFail($id_kelas);

        $pertemuan = PertemuanModel::where('id_kelas', $id_kelas)
            ->orderBy('pertemuan_ke', 'asc')
            ->get();

        $daftar_pertemuan = $pertemuan->pluck('pertemuan_ke')->toArray();
        $pertemuan_lanjut = 1;
        while (in_array($pertemuan_lanjut, $daftar_pertemuan)) {
            $pertemuan_lanjut++;
        }

        return view('admin.data_pertemuan.index', compact('kelas', 'pertemuan', 'pertemuan_lanjut'));
    }

    public function store(Request $request, $id_kelas)
    {
        $request->validate([
            'tanggal'       => 'required|date',
            'judul_pertemuan'   => 'required|string|max:255',
            'pertemuan_ke'  => 'required|integer|min:1',
        ]);

       $pertemuan = PertemuanModel::create([
            'id_kelas'  =>$id_kelas,
            'tanggal'   => $request->tanggal,
            'judul_pertemuan'   => $request->judul_pertemuan,
            'pertemuan_ke'  => $request->pertemuan_ke,
            'status_pertemuan'  => 0,
        ]);

        return redirect()->route('admin.data_presensi', $pertemuan->id_pertemuan)
                ->with('success', 'Pertemuan berhasil ditambahkan!');
    }

    public function destroy($id_kelas, $id_pertemuan)
    {
        $pertemuan = PertemuanModel::where('id_kelas', $id_kelas)
                    ->where('id_pertemuan', $id_pertemuan)
                    ->firstOrFail();

        $pertemuan->delete();

        return redirect()->back()->with('success', 'Data pertemuan berhasil dihapus.');
    }
}
