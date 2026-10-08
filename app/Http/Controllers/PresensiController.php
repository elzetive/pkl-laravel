<?php

namespace App\Http\Controllers;

use App\Models\DetailKelasModel;
use App\Models\DosenModel;
use App\Models\JurusanModel;
use App\Models\MatkulModel;
use App\Models\PresensiModel;
use App\Models\KelasModel;
use App\Models\PertemuanModel;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
public function index($id_pertemuan)
{
    $pertemuan = PertemuanModel::findOrFail($id_pertemuan);

    if ((string)$pertemuan->status_pertemuan === '1') {
        $waktu_mulai = $pertemuan->updated_at ?? now();

        if (now()->diffInSeconds($waktu_mulai) >= 300) {
            $pertemuan->status_pertemuan = 0;
            $pertemuan->save();
        }
    }

    $kelas = KelasModel::findOrFail($pertemuan->id_kelas);
    $dosen = DosenModel::where('nik', $kelas->nik)->first();
    $matkul = MatkulModel::where('kode_matkul', $kelas->kode_matkul)->first();
    $jurusan = JurusanModel::where('kode_jurusan', $kelas->kode_jurusan)->first();

    $mahasiswa = DetailKelasModel::where('id_kelas', $kelas->id_kelas)
        ->pluck('nim')
        ->toArray();

    PresensiModel::where('id_pertemuan', $id_pertemuan)
        ->whereNotIn('nim', $mahasiswa)
        ->delete();

    if (!empty($mahasiswa)) {
        $nim_terdaftar = PresensiModel::where('id_pertemuan', $id_pertemuan)
            ->whereIn('nim', $mahasiswa)
            ->pluck('nim')
            ->toArray();

        $data_presensi_baru = [];

        foreach ($mahasiswa as $nim) {
            if (!in_array($nim, $nim_terdaftar)) {
                $data_presensi_baru[] = [
                    'id_pertemuan'     => $id_pertemuan,
                    'nim'              => $nim,
                    'status_kehadiran' => 'A',
                ];
            }
        }

        if (!empty($data_presensi_baru)) {
            PresensiModel::insert($data_presensi_baru);
        }
    }

    $data_presensi = PresensiModel::with('mahasiswa')
        ->where('id_pertemuan', $id_pertemuan)
        ->orderBy('nim', 'asc')
        ->get();

    return view('admin.data_presensi.index', compact(
        'pertemuan', 'kelas', 'dosen', 'matkul', 'jurusan', 'data_presensi'
    ));
}
    public function edit_status($id_pertemuan, $status)
    {
        $pertemuan = PertemuanModel::findOrFail($id_pertemuan);
        $pertemuan->status_pertemuan = $status;

        if ((string)$status === '1') {
            $pertemuan->updated_at = now();
        }

        $pertemuan->save();

        return redirect()->back()->with('success', 'Status presensi berhasil diubah!');
    }

    public function update_kehadiran(Request $request)
    {
        $request->validate([
            'id_presensi'      => 'required',
            'status_kehadiran' => 'required|in:H,I,S,D,A',
        ]);

        PresensiModel::where('id_presensi', $request->id_presensi)
            ->update([
                'status_kehadiran' => $request->status_kehadiran,
            ]);

        return redirect()->back()->with('success', 'Status kehadiran berhasil diperbarui!');
    }

    public function tutup_presensi($id_pertemuan)
    {
        $pertemuan = PertemuanModel::findOrFail($id_pertemuan);
        $pertemuan->status_pertemuan = 0;
        $pertemuan->save();

        return redirect()->back()->with('success', 'Presensi berhasil ditutup.');
    }

    public function mahasiswa_index()
    {
        $nim = auth()->user()->username;

        $data_presensi = PresensiModel::with(['pertemuan.kelas.matkul'])
        ->where('nim', $nim)
        ->get();

        return view('mahasiswa.data_presensi.index', compact('data_presensi'));
    }

    public function scan_qr(Request $request)
    {
        $id_pertemuan = $request->input('id_pertemuan');
        $nim    = auth()->user()->username;

        $pertemuan = PertemuanModel::where('id_pertemuan', $id_pertemuan)->first();

        if (!$pertemuan || (string)$pertemuan->status_pertemuan === '0')
        {
            return redirect()->back()->with('error', 'Presensi telah ditutup, Anda tidak bisa melakukan presensi!');
        }
        $presensi = PresensiModel::where('id_pertemuan', $id_pertemuan)
                        ->where('nim', $nim)
                        ->first();

        if ($presensi && $presensi->status_kehadiran === 'H') {
            return redirect()->back()->with('error', 'Anda sudah melakukan presensi di presensi ini!');
        }

        PresensiModel::updateOrCreate(
            ['id_pertemuan' => $id_pertemuan, 'nim' => $nim],
            ['status_kehadiran' => 'H']
        );

        return redirect()->back()->with('success', 'Anda berhasil melakukan presensi!');
    }

    public function cek_status($id_pertemuan)
    {
        $pertemuan = PertemuanModel::where('id_pertemuan', $id_pertemuan)->first();

        return response()->json([
            'status_pertemuan'  => $pertemuan ? (string)$pertemuan->status_pertemuan : '0'
        ]);
    }

    public function cek_presensi($id_pertemuan)
    {
        $jumlahHadir = PresensiModel::where('id_pertemuan', $id_pertemuan)
            ->where('status_kehadiran', 'H')
            ->count();

        return response()->json([
            'jumlah_hadir' =>(int)$jumlahHadir
        ]);
    }
}
