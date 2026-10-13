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
                    <h3 class="card-title"><strong>Data Jurusan</strong></h3>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 8px;">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>

                        <a href="{{ route('admin.data_jurusan.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>

                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-import">
                            <i class="fas fa-file-excel"></i> Import Excel
                        </button>

                        <form action="{{ route('admin.data_jurusan.reset') }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin mereset data jurusan?')">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sync-alt"></i> Reset Data
                            </button>
                        </form>
                        <a href="{{ route('admin.data_jurusan.pdf') }}" target="_blank" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabel-jurusan" style="width: 100%">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th>Kode Jurusan</th>
                                    <th>Nama Jurusan</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
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
                <h4 class="modal-title">Tambah Data Jurusan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.data_jurusan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-left">
                    <div class="form-group">
                        <label for="kode_jurusan">Kode Jurusan</label>
                        <input type="text" class="form-control" id="kode_jurusan" name="kode_jurusan" value="{{ old('kode_jurusan') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_jurusan">Nama Jurusan</label>
                        <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" value="{{ old('nama_jurusan') }}" required>
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
                <h4 class="modal-title">Import Data Jurusan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.data_jurusan.import') }}" method="POST" enctype="multipart/form-data">
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

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Data Jurusan</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-left">
                    <div class="form-group">
                        <label for="edit_kode_jurusan">Kode Jurusan</label>
                        <input type="text" id="edit_kode_jurusan" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_jurusan">Nama Jurusan</label>
                        <input type="text" class="form-control" name="nama_jurusan" id="edit_nama_jurusan" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="form-hapus" method="POST" style="display: none">
    @csrf
    @method('DELETE')
</form>

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

@push('scripts')
<script>
    $(document).ready(function () {
        var table = $('#tabel-jurusan').dataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: "{{ route('admin.data_jurusan') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center align-middle' },
                { data: 'kode_jurusan', name: 'kode_jurusan', className: 'text-center align-middle' },
                { data: 'nama_jurusan', name: 'nama_jurusan', className: 'text-center align-middle' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center align-middle'}
            ]
        });

        $('#tabel-jurusan').on('click', '.btn-edit', function() {
            var kode_jurusan = $(this).data('kode_jurusan');
            var nama_jurusan = $(this).data('nama_jurusan');

            $('#edit_kode_jurusan').val(kode_jurusan);
            $('#edit_nama_jurusan').val(nama_jurusan);

            $('#form-edit').attr('action', '/admin/data_jurusan/' + kode_jurusan);
            $('#modal-edit').modal('show');
        });

        $('#tabel-jurusan').on('click', '.btn-hapus', function() {
            var kode_jurusan = $(this).data('kode_jurusan');

            if(confirm('Apakah kamu yakin ingin menghapus data jurusan?')) {
                $('#form-hapus').attr('action', '/admin/data_jurusan/' + kode_jurusan).submit();
            }
        });
    });
</script>
@endpush
