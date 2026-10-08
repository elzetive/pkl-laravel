<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\KelasModel;
use App\Models\DetailKelasModel;
use App\Models\PertemuanModel;
use App\Models\PresensiModel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->peran === 'A') {
            $total_dosen = DosenModel::count();
            $total_mahasiswa = MahasiswaModel::count();
            $total_kelas = KelasModel::count();

            return view('home_admin.index', compact('total_dosen', 'total_mahasiswa', 'total_kelas'));
        }

        if ($user->peran === 'D') {
            $nik = $user->username;

            $daftar_kelas = KelasModel::where('nik', $nik)->pluck('id_kelas');
            $total_kelas = $daftar_kelas->count();
            $total_mahasiswa = DetailKelasModel::whereIn('id_kelas', $daftar_kelas)->count();

            $daftar_presensi = PertemuanModel::whereIn('id_kelas', $daftar_kelas)
                ->where('status_pertemuan', '1')
                ->with('kelas_matkul')
                ->get();

            return view('home_dosen.index', compact('total_kelas', 'total_mahasiswa', 'daftar_presensi'));
        }

        if ($user->peran === 'M') {
            $nim = $user->username;

            $total_kelas      = DetailKelasModel::where('nim', $nim)->count();
            $total_hadir      = PresensiModel::where('nim', $nim)->where('status_kehadiran', 'H')->count();
            $total_izin_sakit = PresensiModel::where('nim', $nim)->whereIn('status_kehadiran', ['I', 'S'])->count();

            return view('home_mahasiswa.index', compact('total_kelas', 'total_hadir', 'total_izin_sakit'));
        }
        abort(403, 'Akses tidak diizinkan.');
    }
}