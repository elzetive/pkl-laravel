@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><strong>Data Pengguna</strong></h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center flex-wrap mb-3" style="gap: 8px;">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                    <a href="{{ route('admin.data_pengguna.export') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Ekspor Excel
                    </a>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-import">
                        <i class="fas fa-file-excel"></i> Import Excel
                    </button>
                    <form action="{{ route('admin.data_pengguna.reset') }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin mereset data pengguna?')">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sync-alt"></i> Reset Data
                        </button>
                    </form>
                    <a href="{{ route('admin.data_pengguna.pdf') }}" target="_blank" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </a>
                </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengguna as $data )
                        <tr>
                            <td align="center">{{ $loop->iteration }}</td>
                            <td align="center">{{ $data->username }}</td>
                            <td align="center">{{ $data->nama }}</td>
                            <td align="center">{{ $data->peran }}</td>
                            <td align="center">
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit-{{ $data->id }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('admin.data_pengguna.destroy', $data->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin Hapus Data Pengguna Ini?')">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                                </form>

                                <div class="modal fade" id="modal-edit-{{ $data->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit Data Pengguna</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.data_pengguna.update', $data->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body" align="left">
                                                    <div class="form-group">
                                                        <label for="username">Username</label>
                                                        <input type="text" class="form-control" name="username" value="{{ $data->username }}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nama">Nama</label>
                                                        <input type="text" class="form-control" name="nama" value="{{ $data->nama }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Peran">Peran</label>
                                                        <select name="peran" class="form-control" required>
                                                            <option value="" disabled>-- Pilih Peran --</option>
                                                            <option value="A" {{ $data->peran == 'A' ? 'selected' : '' }}>Admin</option>
                                                            <option value="D" {{ $data->peran == 'D' ? 'selected' : '' }}>Dosen</option>
                                                            <option value="M" {{ $data->peran == 'M' ? 'selected' : '' }}>Mahasiswa</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-end">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" align="center">Data Tidak Ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Data Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.data_pengguna.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="peran">Peran</label>
                        <select name="peran" id="peran" class="form-control" required>
                            <option value="" disabled>-- Pilih Peran --</option>
                            <option value="A" {{ old('peran') == 'A' ? 'selected' : '' }}>Admin</option>
                            <option value="D" {{ old('peran') == 'D' ? 'selected' : '' }}>Dosen</option>
                            <option value="M" {{ old('peran') == 'M' ? 'selected' : '' }}>Mahasiswa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Import Data Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.data_pengguna.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file_excel">Pilih File Excel</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control-file" accept=".xls,.xlsx" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Upload File</button>
                </div>
        </form>
        </div>
    </div>
</div>


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

@endsection
