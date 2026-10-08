@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Dashboard Admin</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $total_dosen }}</h3>
                        <p>Total Dosen</p>
                    </div>
                    <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <a href="{{ route('admin.data_dosen') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $total_mahasiswa }}</h3>
                        <p>Total Mahasiswa</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-graduate"></i></div>
                    <a href="{{ route('admin.data_mahasiswa') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $total_kelas }}</h3>
                        <p>Total Kelas</p>
                    </div>
                    <div class="icon"><i class="fas fa-school"></i></div>
                    <a href="{{ route('admin.data_kelas_matkul') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection