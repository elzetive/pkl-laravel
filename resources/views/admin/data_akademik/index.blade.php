@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><strong>Data Periode Akademik</strong></h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center flex-wrap mb-3" style="gap: 8px;">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>
                        <a href="{{ route('admin.data_akademik.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-import">
                            <i class="fas fa-file-excel"></i> Import Excel
                        </button>
                        <form action="{{ route('admin.data_akademik.reset') }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin mereset data akademik?')">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sync-alt"></i> Reset Data
                            </button>
                        </form>
                        <a href="{{ route('admin.data_akademik.pdf') }}" target="_blank" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr align="center">
                                <th width="5%">No</th>
                                <th>Kode Akademik</th>
                                <th>Semester</th>
                                <th>Tahun</th>
                                <th>Status Aktif</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($akademik as $data)
                                <tr>
                                    <td align="center">{{ $loop->iteration }}</td>
                                    <td align="center">{{ $data->kode_akademik }}</td>
                                    <td align="center">{{ in_array($data->semester, ['gl', 'ganjil']) ? 'Ganjil' : 'Genap' }}</td>
                                    <td align="center">{{ $data->tahun }}</td>
                                    <td align="center">{{ ($data->isactive == '1' || $data->isactive == 'aktif') ? 'Aktif' : 'Tidak Aktif' }}</td>
                                    <td align="center">
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit-{{ $data->kode_akademik }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form action="{{ route('admin.data_akademik.destroy', $data->kode_akademik) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin Hapus Data Periode Ini?')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
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

    <!-- Modal Edit -->
    @foreach ($akademik as $data)
        <div class="modal fade" id="modal-edit-{{ $data->kode_akademik }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Data Akademik</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.data_akademik.update', $data->kode_akademik) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body" align="left">
                            <div class="form-group">
                                <label for="kode_akademik">Kode Akademik</label>
                                <input type="text" class="form-control" value="{{ $data->kode_akademik }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="semester">Semester</label>
                                <select name="semester" class="form-control" required>
                                    <option value="gl" {{ in_array($data->semester, ['gl', 'ganjil']) ? 'selected' : '' }}>Ganjil</option>
                                    <option value="gn" {{ in_array($data->semester, ['gn', 'genap']) ? 'selected' : '' }}>Genap</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tahun">Tahun Akademik</label>
                                <input type="text" class="form-control" name="tahun" value="{{ $data->tahun }}" maxlength="4" required>
                            </div>
                            <div class="form-group">
                                <label for="isactive">Status Periode</label>
                                <select name="isactive" class="form-control" required>
                                    <option value="1" {{ ($data->isactive == '1' || $data->isactive == 'aktif') ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ ($data->isactive == '0' || $data->isactive == 'tidak aktif') ? 'selected' : '' }}>Tidak Aktif</option>
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
    @endforeach

    <!-- Modal Tambah -->
    <div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Periode Akademik</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.data_akademik.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode_akademik">Kode Akademik</label>
                            <input type="text" class="form-control" id="kode_akademik" name="kode_akademik" value="{{ old('kode_akademik') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <select name="semester" id="semester" class="form-control" required>
                                <option value="">-- Pilih Semester --</option>
                                <option value="gl">Ganjil</option>
                                <option value="gn">Genap</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tahun">Tahun Akademik</label>
                            <input type="text" class="form-control" id="tahun" name="tahun" maxlength="4" required>
                        </div>
                        <div class="form-group">
                            <label for="isactive">Status Periode</label>
                            <select name="isactive" id="isactive" class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
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

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Data Akademik</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.data_akademik.import') }}" method="POST" enctype="multipart/form-data">
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
