@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Halo, {{ auth()->user()->nama }}</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $total_kelas }}</h3>
                        <p>Total Kelas</p>
                    </div>
                    <div class="icon"><i class="fas fa-book"></i></div>
                </div>
            </div>

            <div class="col-lg-4 col-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $total_hadir }}</h3>
                        <p>Hadir</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <div class="col-lg-4 col-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $total_izin_sakit }}</h3>
                        <p>Izin / Sakit</p>
                    </div>
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection