@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Selamat Datang, {{ auth()->user()->nama }}</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $total_kelas }}</h3>
                        <p>Kelas Diampu</p>
                    </div>
                    <div class="icon"><i class="fas fa-chalkboard"></i></div>
                    <a href="{{ route('dosen.data_kelas_matkul') }}" class="small-box-footer">
                        Lihat Kelas <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $total_mahasiswa }}</h3>
                        <p>Total Mahasiswa</p>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-qrcode mr-1"></i> Presensi Sedang Aktif</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Mata Kuliah / Kelas</th>
                            <th>Pertemuan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftar_presensi as $item)
                            <tr>
                                <td>{{ $item->kelas_matkul?->matkul?->nama_matkul ?? '-' }} ({{ $item->kelas_matkul?->nama_kelas }})</td>
                                <td>Pertemuan ke-{{ $item->pertemuan_ke }}</td>
                                <td class="text-center">
                                    <a href="{{ route('dosen.data_presensi', $item->id_pertemuan) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye mr-1"></i> Kelola Presensi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    Tidak ada sesi presensi yang sedang aktif saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection