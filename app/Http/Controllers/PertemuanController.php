<?php

namespace App\Http\Controllers;

use App\Models\DetailKelasModel;
use App\Models\KelasModel;
use App\Models\PertemuanModel;
use App\Models\PresensiModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PertemuanController extends Controller
{
    public function index(Request $request, $id_kelas)
    {
        $user = Auth::user();
        $kelas = KelasModel::with(['dosen', 'matkul'])->findOrFail($id_kelas);

        if ($request->ajax()) {
            $data = PertemuanModel::where('id_kelas', $id_kelas)
                ->orderBy('pertemuan_ke', 'asc');

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('pertemuan_ke', function ($baris) {
                        return 'Pertemuan ' . $baris->pertemuan_ke;
                    })
                    ->addColumn('judul_pertemuan', function ($baris) {
                        return $baris->judul_pertemuan;
                    })
                    ->addColumn('tanggal', function ($baris) {
                        return $baris->tanggal->translatedFormat('l, d F Y');
                    })
                    ->addColumn('aksi', function ($baris) use ($user, $kelas) {
                        $peran = $user->peran === 'D' ? 'dosen.' : 'admin.';
                        $presensi = route($peran . 'data_presensi', $baris->id_pertemuan);
                        $destroy = route($peran . 'data_pertemuan.destroy', [$kelas->id_kelas, $baris->id_pertemuan]);

                        $btn = '<div class="btn-group" role="group">';
                        $btn .= '<a href="' .$presensi. '" class="btn btn-info btn-sm mr-2">
                            <i class="fas fa-qrcode"></i> Presensi
                        </a>';

                        $btn .= '<form action="' .$destroy. '" method="POST" class="d-inline" onsubmit="return confirm(\'Apakah anda yakin ingin menghapus data pertemuan ini?\')">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '<button type="submit" class="btn btn-sm btn-danger" title="Hapus Pertemuan">
                            <i class="fas fa-trash"></i> Hapus
                            </button>';
                        $btn .= '</form>';
                        $btn .= '</div>';

                        return $btn;
                    })
                    ->rawColumns(['aksi'])
                    ->make(true);
        }

        $pertemuan = PertemuanModel::where('id_kelas', $id_kelas)
            ->orderBy('pertemuan_ke', 'asc')
            ->get();

        $daftar_pertemuan = $pertemuan->pluck('pertemuan_ke')->toArray();
        $pertemuan_lanjut = 1;
        while (in_array($pertemuan_lanjut, $daftar_pertemuan)) {
            $pertemuan_lanjut++;
        }

        $tampil_pertemuan = $user->peran === 'D' ? 'dosen.data_pertemuan.index' : 'admin.data_pertemuan.index';

        return view($tampil_pertemuan, compact('kelas', 'pertemuan', 'pertemuan_lanjut'));
    }

    public function store(Request $request, $id_kelas)
    {
        $request->validate([
            'tanggal'         => 'required|date',
            'judul_pertemuan' => 'required|string|max:255',
            'pertemuan_ke'    => 'required|integer|min:1',
        ]);

        $pertemuan = PertemuanModel::create([
            'id_kelas'         => $id_kelas,
            'tanggal'          => $request->tanggal,
            'judul_pertemuan'  => $request->judul_pertemuan,
            'pertemuan_ke'     => $request->pertemuan_ke,
            'status_pertemuan' => 0,
        ]);

        $tampil_presensi = Auth::user()->peran === 'D' ? 'dosen.data_presensi' : 'admin.data_presensi';

        return redirect()->route($tampil_presensi, $pertemuan->id_pertemuan)
            ->with('success', 'Pertemuan berhasil ditambahkan!');
    }

    public function update_bobot(Request $request, $id_kelas)
    {
        $request->validate([
            'bobot_kelas' => 'required|integer|min:0|max:100',
        ]);

        $kelas = KelasModel::findOrFail($id_kelas);
        $kelas->update([
            'bobot_kelas' => $request->bobot_kelas,
        ]);

        return redirect()->back()->with('success', 'Bobot kehadiran berhasil diperbarui!');
    }

    public function destroy($id_kelas, $id_pertemuan)
    {
        $pertemuan = PertemuanModel::where('id_kelas', $id_kelas)
            ->where('id_pertemuan', $id_pertemuan)
            ->firstOrFail();

        $pertemuan->delete();

        return redirect()->back()->with('success', 'Data pertemuan berhasil dihapus.');
    }

    public function pdf_pertemuan($id_kelas)
    {
        $kelas = KelasModel::with(['matkul', 'jurusan', 'dosen', 'akademik'])->findOrFail($id_kelas);

        $pertemuan = PertemuanModel::where('id_kelas', $id_kelas)
                ->orderBy('pertemuan_ke', 'asc')
                ->get();

        $daftar_mahasiswa = DetailKelasModel::with('mahasiswa')
            ->where('id_kelas', $id_kelas)
            ->orderBy('nim', 'asc')
            ->get();

        $peran = Auth::user()->peran === 'D' ? 'dosen.data_pertemuan.pdf' : 'admin.data_pertemuan.pdf';

        $pdf  = Pdf::loadView($peran, compact('kelas', 'pertemuan', 'daftar_mahasiswa'))->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Presensi_Lengkap_' . str_replace(' ', '-', $kelas->nama_kelas) . '.pdf');
    }

    public function pdf_presensi($id_kelas)
    {
        $kelas = KelasModel::with(['matkul', 'jurusan', 'dosen', 'akademik'])->findOrFail($id_kelas);

        $daftar_mahasiswa = DetailKelasModel::with('mahasiswa')
            ->where('id_kelas', $id_kelas)
            ->orderBy('nim', 'asc')
            ->get();

        $data_pertemuan = PertemuanModel::where('id_kelas', $id_kelas)
            ->orderBy('pertemuan_ke', 'asc')
            ->get();

        $daftar_pertemuan = $data_pertemuan->pluck('id_pertemuan');

        $rekap_presensi = $daftar_mahasiswa->map(function ($detail) use ($daftar_pertemuan) {
            $nim = $detail->nim;

            $jumlah_hadir = PresensiModel::whereIn('id_pertemuan', $daftar_pertemuan)
                ->where('nim', $nim)
                ->whereIn('status_kehadiran', ['H', 'Hadir'])
                ->count();

            return [
                'nim' => $nim,
                'nama' => $detail->mahasiswa->nama_mahasiswa ?? $detail->mahasiswa->nama ?? '-',
                'jumlah_hadir' => $jumlah_hadir,
            ];
        });

        $peran = Auth::user()->peran === 'D' ? 'dosen.data_presensi.pdf' : 'admin.data_presensi.pdf';

        $pdf = Pdf::loadView($peran, compact('kelas', 'data_pertemuan', 'rekap_presensi'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Presensi_Lengkap_' . str_replace(' ', '_', $kelas->nama_kelas) . '.pdf');
    }
}
