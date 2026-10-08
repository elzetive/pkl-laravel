@extends('layouts.app')

@section('title', 'Dosen - Data Pertemuan Kelas')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Pertemuan Kelas</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><strong>Data Kelas Mata Kuliah</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td width="35%"><strong>NIK</strong></td>
                                    <td width="5%">:</td>
                                    <td>{{ $kelas->dosen->nik ?? $kelas->nik }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dosen Pengampu</strong></td>
                                    <td>:</td>
                                    <td>{{ $kelas->dosen->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kontrak Kehadiran</strong></td>
                                    <td>:</td>
                                    <td><span>{{ $kelas->bobot_kelas ?? 0 }}%</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td width="35%"><strong>Mata Kuliah</strong></td>
                                    <td width="5%">:</td>
                                    <td>{{ $kelas->matkul->nama_matkul ?? $kelas->kode_matkul }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>:</td>
                                    <td>{{ $kelas->nama_kelas }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Pertemuan -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><strong>Data Pertemuan</strong></h3>
                </div>
                <div class="card-body">
                    <div class="mb-3 text-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                            <i class="fas fa-plus"></i> Tambah Pertemuan
                        </button>
                        @if (auth()->user()->peran === 'D')
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-bobot">
                                <i class="fas fa-percentage"></i> Bobot Kehadiran
                            </button>
                        @endif
                        @php
                            $peran = auth()->user()->peran === 'D' ? 'dosen.' : 'admin.';
                        @endphp

                        <a href="{{ route($peran . 'data_pertemuan.pdf', $kelas->id_kelas) }}" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> Data Pertemuan
                        </a>

                        <a href="{{ route($peran . 'data_presensi.pdf', $kelas->id_kelas) }}" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> Data Presensi
                        </a>
                    </div>

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th width="15%">Pertemuan Ke</th>
                                <th>Judul Pertemuan</th>
                                <th width="20%">Tanggal</th>
                                <th width="25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pertemuan as $item)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>Pertemuan {{ $item->pertemuan_ke }}</td>
                                    <td class="text-left">{{ $item->judul_pertemuan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM YYYY') }}</td>
                                    <td>
                                        @php
                                            $peran = auth()->user()->peran === 'D' ? 'dosen.' : 'admin.';
                                        @endphp

                                        <a href="" class="btn btn-info btn-sm">
                                            <i class="fas fa-qrcode"></i> Presensi
                                        </a>

                                        <form action="{{ route($peran . 'data_pertemuan.destroy', [$item->id_kelas, $item->id_pertemuan]) }}" method="POST" class="d-inline d-delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-delete" title="Hapus Pertemuan" onclick="return confirm('Apakah Anda yakin ingin menghapus data pertemuan ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada pertemuan untuk kelas ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @if (auth()->user()->peran === 'D')
        <div class="modal fade" id="modal-bobot" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Setting Bobot Kehadiran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('dosen.update_bobot', $kelas->id_kelas) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="bobot_kelas">Bobot Kehadiran (%)</label>
                                <input type="number" id="bobot_kelas" name="bobot_kelas" class="form-control" value="{{ $kelas->bobot_kelas ?? 0 }}" min="0" max="100" placeholder="Masukkan bobot (0-100)" required>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan Bobot</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pertemuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @php
                    $storeRoute = auth()->user()->peran === 'D' ? route('dosen.data_pertemuan.store', $kelas->id_kelas) : route('admin.data_pertemuan.store', $kelas->id_kelas);
                @endphp
                <form action="{{ $storeRoute }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">

                        <div class="form-group">
                            <label for="tanggal">Pilih Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="judul_pertemuan">Judul Pertemuan</label>
                            <input type="text" id="judul_pertemuan" name="judul_pertemuan" class="form-control" placeholder="Masukkan Judul Pertemuan" required>
                        </div>
                        <div class="form-group">
                            <label for="pertemuan_ke">Pertemuan Ke-</label>
                            <input type="number" id="pertemuan_ke" name="pertemuan_ke" class="form-control" value="{{ $pertemuan_lanjut }}" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif
@endpush