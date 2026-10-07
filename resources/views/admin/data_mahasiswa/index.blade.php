@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><strong>Data Mahasiswa</strong></h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-item-center flex-wrap mb-3" style="gap: 8px;">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>

                        <a href="{{ route('admin.data_mahasiswa.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>

                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-import-mahasiswa">
                            <i class="fas fa-file-excel"></i> Import Excel
                        </button>

                        <form action="{{ route('admin.data_mahasiswa.reset') }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin mereset data mahasiswa?')">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sync-alt"></i> Reset Data
                            </button>
                        </form>
                        <a href="{{ route('admin.data_mahasiswa.pdf') }}" target="_blank" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Kontak</th>
                                    <th>Email</th>
                                    <th>Kelamin</th>
                                    <th>Foto</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mahasiswa as $data)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $data->nim }}</td>
                                        <td>{{ $data->nama }}</td>
                                        <td class="text-center">{{ $data->kontak }}</td>
                                        <td>{{ $data->email }}</td>
                                        <td class="text-center">{{ $data->kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                                        <td class="text-center">
                                            @if ($data->img)
                                                <img src="{{ asset('storage/mahasiswa/' . $data->img) }}" width="50" height="50" alt="Foto Mahasiswa">
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit-{{ $data->nim }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>

                                            <form action="{{ route('admin.data_mahasiswa.destroy', $data->nim) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin Hapus Data Mahasiswa Ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>

                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="modal-edit-{{ $data->nim }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Data Mahasiswa</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('admin.data_mahasiswa.update', $data->nim) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body text-left">
                                                                <div class="form-group">
                                                                    <label for="nim">NIM</label>
                                                                    <input type="text" name="nim" class="form-control" value="{{ $data->nim }}" readonly>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nama">Nama</label>
                                                                    <input type="text" class="form-control" name="nama" value="{{ old('nama', $data->nama) }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="kontak">Kontak</label>
                                                                    <input type="text" class="form-control" name="kontak" value="{{ old('kontak', $data->kontak) }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="email">Email</label>
                                                                    <input type="email" class="form-control" name="email" value="{{ old('email', $data->email) }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="kelamin">Jenis Kelamin</label>
                                                                    <select name="kelamin" class="form-control" required>
                                                                        <option value="" disabled>-- Pilih Jenis Kelamin --</option>
                                                                        <option value="L" {{ old('kelamin', $data->kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                                                        <option value="P" {{ old('kelamin', $data->kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="img">Foto Profil (Opsional)</label>
                                                                    <input type="file" class="form-control-file" name="img" accept="image/*">
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
                                        <td colspan="8" class="text-center">Data Tidak Ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Mahasiswa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.data_mahasiswa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-left">
                        <div class="form-group">
                            <label for="nim">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" value="{{ old('nim') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="kontak">Kontak</label>
                            <input type="text" class="form-control" id="kontak" name="kontak" value="{{ old('kontak') }}" maxlength="15" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="kelamin">Jenis Kelamin</label>
                            <select name="kelamin" class="form-control" required>
                                <option value="" disabled {{ old('kelamin') ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ old('kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="img">Foto Profil</label>
                            <input type="file" class="form-control-file" name="img" accept="image/*">
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

    <div class="modal fade" id="modal-import-mahasiswa" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Data Mahasiswa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.data_mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-left">
                        <div class="form-group">
                            <label for="file_excel">Pilih File Excel</label>
                            <input type="file" name="file_excel" id="file_excel" class="form-control-file" accept=".xls,.xlsx" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Upload</button>
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
