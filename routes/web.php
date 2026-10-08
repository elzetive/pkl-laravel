<?php

use App\Http\Controllers\AkademikController;
use App\Http\Controllers\DetailKelasController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MatkulController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PertemuanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $peran = auth()->user()->peran;

        if ($peran === 'A') {
            return view('home_admin.index');
        } elseif ($peran === 'D') {
            return view('home_dosen.index');
        } elseif ($peran === 'M') {
            return view('home_mahasiswa.index');
        }

        return abort(403, 'Akses tidak diizinkan');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['peran:A'])->prefix('admin')->as('admin.')->group(function () {
        Route::get('/data_akademik', [AkademikController::class, 'index'])->name('data_akademik');
        Route::post('/data_akademik', [AkademikController::class, 'store'])->name('data_akademik.store');
        Route::put('/data_akademik/{kode_akademik}', [AkademikController::class, 'update'])->name('data_akademik.update');
        Route::delete('/data_akademik/{kode_akademik}', [AkademikController::class, 'destroy'])->name('data_akademik.destroy');
        Route::post('/data_akademik/reset', [AkademikController::class, 'reset'])->name('data_akademik.reset');
        Route::get('/data_akademik/export', [AkademikController::class, 'export'])->name('data_akademik.export');
        Route::post('/data_akademik/import', [AkademikController::class, 'import'])->name('data_akademik.import');
        Route::get('/data_akademik/pdf', [AkademikController::class, 'pdf'])->name('data_akademik.pdf');

        Route::get('/data_matkul', [MatkulController::class, 'index'])->name('data_matkul');
        Route::post('/data_matkul', [MatkulController::class, 'store'])->name('data_matkul.store');
        Route::put('/data_matkul/{kode_matkul}', [MatkulController::class, 'update'])->name('data_matkul.update');
        Route::delete('/data_matkul/{kode_matkul}', [MatkulController::class, 'destroy'])->name('data_matkul.destroy');
        Route::post('/data_matkul/reset', [MatkulController::class, 'reset'])->name('data_matkul.reset');
        Route::get('/data_matkul/export', [MatkulController::class, 'export'])->name('data_matkul.export');
        Route::post('/data_matkul/import', [MatkulController::class, 'import'])->name('data_matkul.import');
        Route::get('/data_matkul/pdf', [MatkulController::class, 'pdf'])->name('data_matkul.pdf');

        Route::get('/data_pengguna', [PenggunaController::class, 'index'])->name('data_pengguna');
        Route::post('/data_pengguna', [PenggunaController::class, 'store'])->name('data_pengguna.store');
        Route::put('/data_pengguna/{id}', [PenggunaController::class, 'update'])->name('data_pengguna.update');
        Route::delete('/data_pengguna/{id}', [PenggunaController::class, 'destroy'])->name('data_pengguna.destroy');
        Route::post('/data_pengguna/reset', [PenggunaController::class, 'reset'])->name('data_pengguna.reset');
        Route::get('/data_pengguna/export', [PenggunaController::class, 'export'])->name('data_pengguna.export');
        Route::post('/data_pengguna/import', [PenggunaController::class, 'import'])->name('data_pengguna.import');
        Route::get('/data_pengguna/pdf', [PenggunaController::class, 'pdf'])->name('data_pengguna.pdf');

        Route::get('/data_dosen', [DosenController::class, 'index'])->name('data_dosen');
        Route::post('/data_dosen', [DosenController::class, 'store'])->name('data_dosen.store');
        Route::put('/data_dosen/{nik}', [DosenController::class, 'update'])->name('data_dosen.update');
        Route::delete('/data_dosen/{nik}', [DosenController::class, 'destroy'])->name('data_dosen.destroy');
        Route::post('/data_dosen/reset', [DosenController::class, 'reset'])->name('data_dosen.reset');
        Route::get('/data_dosen/export', [DosenController::class, 'export'])->name('data_dosen.export');
        Route::post('/data_dosen/import', [DosenController::class, 'import'])->name('data_dosen.import');
        Route::get('/data_dosen/pdf', [DosenController::class, 'pdf'])->name('data_dosen.pdf');

        Route::get('/data_mahasiswa', [MahasiswaController::class, 'index'])->name('data_mahasiswa');
        Route::post('/data_mahasiswa', [MahasiswaController::class, 'store'])->name('data_mahasiswa.store');
        Route::put('/data_mahasiswa/{nim}', [MahasiswaController::class, 'update'])->name('data_mahasiswa.update');
        Route::delete('/data_mahasiswa/{nim}', [MahasiswaController::class, 'destroy'])->name('data_mahasiswa.destroy');
        Route::post('/data_mahasiswa/reset', [MahasiswaController::class, 'reset'])->name('data_mahasiswa.reset');
        Route::get('/data_mahasiswa/export', [MahasiswaController::class, 'export'])->name('data_mahasiswa.export');
        Route::post('/data_mahasiswa/import', [MahasiswaController::class, 'import'])->name('data_mahasiswa.import');
        Route::get('/data_mahasiswa/pdf', [MahasiswaController::class, 'pdf'])->name('data_mahasiswa.pdf');

        Route::get('/data_jurusan', [JurusanController::class, 'index'])->name('data_jurusan');
        Route::post('/data_jurusan', [JurusanController::class, 'store'])->name('data_jurusan.store');
        Route::put('/data_jurusan/{kode_jurusan}', [JurusanController::class, 'update'])->name('data_jurusan.update');
        Route::delete('/data_jurusan/{kode_jurusan}', [JurusanController::class, 'destroy'])->name('data_jurusan.destroy');
        Route::post('/data_jurusan/reset', [JurusanController::class, 'reset'])->name('data_jurusan.reset');
        Route::get('/data_jurusan/export', [JurusanController::class, 'export'])->name('data_jurusan.export');
        Route::post('/data_jurusan/import', [JurusanController::class, 'import'])->name('data_jurusan.import');
        Route::get('/data_jurusan/pdf', [JurusanController::class, 'pdf'])->name('data_jurusan.pdf');

        Route::get('/data_kelas_matkul', [KelasController::class, 'index'])->name('data_kelas_matkul');
        Route::post('/data_kelas_matkul', [KelasController::class, 'store'])->name('data_kelas_matkul.store');
        Route::put('/data_kelas_matkul/{id_kelas}', [KelasController::class, 'update'])->name('data_kelas_matkul.update');
        Route::delete('/data_kelas_matkul/{id_kelas}', [KelasController::class, 'destroy'])->name('data_kelas_matkul.destroy');
        Route::post('/data_kelas_matkul/reset', [KelasController::class, 'reset'])->name('data_kelas_matkul.reset');
        Route::get('/data_kelas_matkul/export', [KelasController::class, 'export'])->name('data_kelas_matkul.export');
        Route::post('/data_kelas_matkul/import', [KelasController::class, 'import'])->name('data_kelas_matkul.import');
        Route::get('/data_kelas_matkul/pdf', [KelasController::class, 'pdf'])->name('data_kelas_matkul.pdf');

        Route::get('/data_detail_kelas/{id_kelas}', [DetailKelasController::class, 'index'])->name('data_detail_kelas');
        Route::post('/data_detail_kelas/{id_kelas}/tambah', [DetailKelasController::class, 'store'])->name('data_detail_kelas.store');
        Route::delete('/data_detail_kelas/{id_kelas}/hapus/{nim}', [DetailKelasController::class, 'destroy'])->name('data_detail_kelas.destroy');
        Route::post('/data_detail_kelas/{id_kelas}/import', [DetailKelasController::class, 'import'])->name('data_detail_kelas.import');
        Route::get('/data_detail_kelas/{id_kelas}/export', [DetailKelasController::class, 'export'])->name('data_detail_kelas.export');
        Route::get('/data_detail_kelas/{id_kelas}/pdf', [DetailKelasController::class, 'pdf'])->name('data_detail_kelas.pdf');

        Route::get('/data_pertemuan/{id_kelas}', [PertemuanController::class, 'index'])->name('data_pertemuan');
        Route::post('/data_pertemuan/{id_kelas}/tambah', [PertemuanController::class, 'store'])->name('data_pertemuan.store');
        Route::delete('/data_pertemuan/{id_kelas}/hapus/{id_pertemuan}', [PertemuanController::class, 'destroy'])->name('data_pertemuan.destroy');

        Route::get('/data_presensi/{id_pertemuan}', [PresensiController::class, 'index'])->name('data_presensi');
        Route::post('/data_presensi/status/{id_pertemuan}/{status}', [PresensiController::class, 'editStatus'])->name('data_presensi.edit_status');
        Route::post('/data_presensi/update-kehadiran', [PresensiController::class, 'updateKehadiran'])->name('data_presensi.update_kehadiran');
        Route::post('/data_presensi/{id_pertemuan}/tutup', [PresensiController::class, 'tutupPresensi'])->name('data_presensi.tutup');
        Route::get('/data_presensi/cek_presensi/{id_pertemuan}', [PresensiController::class, 'cekPresensi'])->name('data_presensi.cek_presensi');
    });

    Route::middleware(['peran:D'])->prefix('dosen')->as('dosen.')->group(function () {
        Route::get('/data_kelas_matkul', [KelasController::class, 'index'])->name('data_kelas_matkul');

        Route::get('/data_detail_kelas/{id_kelas}', [DetailKelasController::class, 'index'])->name('data_detail_kelas');
        Route::post('/data_detail_kelas/{id_kelas}/tambah', [DetailKelasController::class, 'store'])->name('data_detail_kelas.store');
        Route::delete('/data_detail_kelas/{id_kelas}/hapus/{nim}', [DetailKelasController::class, 'destroy'])->name('data_detail_kelas.destroy');
        Route::post('/data_detail_kelas/{id_kelas}/import', [DetailKelasController::class, 'import'])->name('data_detail_kelas.import');

        Route::get('/data_pertemuan/{id_kelas}', [PertemuanController::class, 'index'])->name('data_pertemuan');
        Route::post('/data_pertemuan/{id_kelas}/tambah', [PertemuanController::class, 'store'])->name('data_pertemuan.store');
        Route::put('/data_pertemuan/{id_kelas}/bobot', [PertemuanController::class, 'update_bobot'])->name('update_bobot');
        Route::delete('/data_pertemuan/{id_kelas}/hapus/{id_pertemuan}', [PertemuanController::class, 'destroy'])->name('data_pertemuan.destroy');
        Route::get('/data_pertemuan/{id_kelas}/pdf', [PertemuanController::class, 'pdf_pertemuan'])->name('data_pertemuan.pdf');

        Route::get('/data_presensi/{id_kelas}/pdf', [PertemuanController::class, 'pdf_presensi'])->name('data_presensi.pdf');
        Route::get('/data_presensi/{id_pertemuan}', [PresensiController::class, 'index'])->name('data_presensi');
        Route::post('/data_presensi/status/{id_pertemuan}/{status}', [PresensiController::class, 'editStatus'])->name('data_presensi.edit_status');
        Route::post('/data_presensi/update-kehadiran', [PresensiController::class, 'updateKehadiran'])->name('data_presensi.update_kehadiran');
        Route::post('/data_presensi/{id_pertemuan}/tutup', [PresensiController::class, 'tutupPresensi'])->name('data_presensi.tutup');
        Route::get('/data_presensi/cek_presensi/{id_pertemuan}', [PresensiController::class, 'cekPresensi'])->name('data_presensi.cek_presensi');
    });

    Route::middleware(['peran:M'])->prefix('mahasiswa')->as('mahasiswa.')->group(function () {
        Route::get('/data_presensi', [PresensiController::class, 'mahasiswaIndex'])->name('data_presensi');
        Route::post('/data_presensi/proses', [PresensiController::class, 'scanQr'])->name('data_presensi.proses');
        Route::post('/data_presensi/status/{id_pertemuan}/{status}', [PresensiController::class, 'editStatus'])->name('data_presensi.edit_status');
        Route::get('/data_presensi/cek-status/{id_pertemuan}', [PresensiController::class, 'cekStatus'])->name('data_presensi.cek_status');
    });

});

require __DIR__.'/auth.php';