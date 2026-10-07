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
        $waktuMulai = $pertemuan->updated_at ?? now();

        if (now()->diffInSeconds($waktuMulai) >= 300) {
            $pertemuan->status_pertemuan = 0;
            $pertemuan->save();
        }
    }

    $kelas = KelasModel::findOrFail($pertemuan->id_kelas);
    $dosen = DosenModel::where('nik', $kelas->nik)->first();
    $matkul = MatkulModel::where('kode_matkul', $kelas->kode_matkul)->first();
    $jurusan = JurusanModel::where('kode_jurusan', $kelas->kode_jurusan)->first();

    $mahasiswaKelas = DetailKelasModel::where('id_kelas', $kelas->id_kelas)
        ->pluck('nim')
        ->toArray();

    PresensiModel::where('id_pertemuan', $id_pertemuan)
        ->whereNotIn('nim', $mahasiswaKelas)
        ->delete();

    if (!empty($mahasiswaKelas)) {
        $nimTerdaftar = PresensiModel::where('id_pertemuan', $id_pertemuan)
            ->whereIn('nim', $mahasiswaKelas)
            ->pluck('nim')
            ->toArray();

        $dataPresensiBaru = [];

        foreach ($mahasiswaKelas as $nim) {
            if (!in_array($nim, $nimTerdaftar)) {
                $dataPresensiBaru[] = [
                    'id_pertemuan'     => $id_pertemuan,
                    'nim'              => $nim,
                    'status_kehadiran' => 'A',
                ];
            }
        }

        if (!empty($dataPresensiBaru)) {
            PresensiModel::insert($dataPresensiBaru);
        }
    }

    $dataPresensi = PresensiModel::with('mahasiswa')
        ->where('id_pertemuan', $id_pertemuan)
        ->orderBy('nim', 'asc')
        ->get();

    return view('admin.data_presensi.index', compact(
        'pertemuan', 'kelas', 'dosen', 'matkul', 'jurusan', 'dataPresensi'
    ));
}
    public function editStatus($id_pertemuan, $status)
    {
        $pertemuan = PertemuanModel::findOrFail($id_pertemuan);
        $pertemuan->status_pertemuan = $status;

        if ((string)$status === '1') {
            $pertemuan->updated_at = now();
        }

        $pertemuan->save();

        return redirect()->back()->with('success', 'Status presensi berhasil diubah!');
    }

    public function updateKehadiran(Request $request)
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

    public function tutupPresensi($id_pertemuan)
    {
        $pertemuan = PertemuanModel::findOrFail($id_pertemuan);
        $pertemuan->status_pertemuan = 0;
        $pertemuan->save();

        return redirect()->back()->with('success', 'Presensi berhasil ditutup.');
    }

    public function mahasiswaIndex()
    {
        $nim = auth()->user()->username;

        $dataPresensi = PresensiModel::with(['pertemuan.kelas.matkul'])
        ->where('nim', $nim)
        ->get();

        return view('mahasiswa.data_presensi.index', compact('dataPresensi'));
    }

    public function scanQr(Request $request)
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

    public function cekStatus($id_pertemuan)
    {
        $pertemuan = PertemuanModel::where('id_pertemuan', $id_pertemuan)->first();

        return response()->json([
            'status_pertemuan'  => $pertemuan ? (string)$pertemuan->status_pertemuan : '0'
        ]);
    }

    public function cekPresensi($id_pertemuan)
    {
        $jumlahHadir = PresensiModel::where('id_pertemuan', $id_pertemuan)
            ->where('status_kehadiran', 'H')
            ->count();

        return response()->json([
            'jumlah_hadir' =>(int)$jumlahHadir
        ]);
    }
}
