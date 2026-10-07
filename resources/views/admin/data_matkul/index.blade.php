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
                    <h3 class="card-title"><strong>Data Mata Kuliah</strong></h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center flex-wrap mb-3" style="gap: 8px">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>

                        <form action="{{ route('admin.data_matkul.reset') }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin mereset data mata kuliah?')">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sync-alt"></i> Reset Data
                            </button>
                        </form>

                        <a href="{{ route('admin.data_matkul.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>

                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-import">
                            <i class="fas fa-file-excel"></i> Import Excel
                        </button>
                        <a href="{{ route('admin.data_matkul.pdf') }}" target="_blank" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th>Kode Matkul</th>
                                    <th>Nama Matkul</th>
                                    <th>Jumlah SKS</th>
                                    <th>Jumlah CPMK</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($matkul as $data)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $data->kode_matkul }}</td>
                                        <td>{{ $data->nama_matkul }}</td>
                                        <td class="text-center">{{ $data->jumlah_sks }}</td>
                                        <td class="text-center">{{ $data->jml_cpmk }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit-{{ $data->kode_matkul }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>

                                            <form action="{{ route('admin.data_matkul.destroy', $data->kode_matkul) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin Hapus Data Mata Kuliah Ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>

                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="modal-edit-{{ $data->kode_matkul }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Data Matkul</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('admin.data_matkul.update', $data->kode_matkul) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body text-left">
                                                                <div class="form-group">
                                                                    <label for="kode_matkul">Kode Matkul</label>
                                                                    <input type="text" class="form-control" value="{{ $data->kode_matkul }}" disabled>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="nama_matkul">Nama Matkul</label>
                                                                    <input type="text" class="form-control" name="nama_matkul" value="{{ old('nama_matkul', $data->nama_matkul) }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="jumlah_sks">Jumlah SKS</label>
                                                                    <input type="number" class="form-control" name="jumlah_sks" value="{{ old('jumlah_sks', $data->jumlah_sks) }}" min="1" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="jml_cpmk">Jumlah CPMK</label>
                                                                    <input type="number" class="form-control" name="jml_cpmk" value="{{ old('jml_cpmk', $data->jml_cpmk) }}" min="1" required>
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
                                        <td colspan="6" class="text-center">Data Tidak Ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Matkul</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.data_matkul.store') }}" method="POST">
                    @csrf
                    <div class="modal-body text-left">
                        <div class="form-group">
                            <label for="kode_matkul">Kode Matkul</label>
                            <input type="text" class="form-control" id="kode_matkul" name="kode_matkul" value="{{ old('kode_matkul') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_matkul">Nama Matkul</label>
                            <input type="text" class="form-control" id="nama_matkul" name="nama_matkul" value="{{ old('nama_matkul') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_sks">Jumlah SKS</label>
                            <input type="number" class="form-control" id="jumlah_sks" name="jumlah_sks" value="{{ old('jumlah_sks') }}" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="jml_cpmk">Jumlah CPMK</label>
                            <input type="number" class="form-control" id="jml_cpmk" name="jml_cpmk" value="{{ old('jml_cpmk') }}" min="1" required>
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

    <!-- Modal Import Matkul -->
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Data Mata Kuliah</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.data_matkul.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-left">
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
