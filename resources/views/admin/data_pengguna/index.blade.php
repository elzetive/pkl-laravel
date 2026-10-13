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
                <table class="table table-bordered table-striped" id="tabel-pengguna" style="width: 100%">
                    <thead>
                        <tr align="center">
                            <th width="5%">No</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Peran</th>
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

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Data Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_username">Username</label>
                        <input type="text" class="form-control" name="username" id="edit_username" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama">Nama</label>
                        <input type="text" class="form-control" name="nama" id="edit_nama" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_sandi">Password Baru</label>
                        <input type="password" class="form-control" name="sandi" id="edit_sandi">
                    </div>
                    <div class="form-group">
                        <label for="edit_peran">Peran</label>
                        <select name="peran" id="edit_peran" class="form-control" required>
                            <option value="A">Admin</option>
                            <option value="D">Dosen</option>
                            <option value="M">Mahasiswa</option>
                        </select>
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

<form id="form-hapus" method="POST" style="display: none;">
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
        var table = $('#tabel-pengguna').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: "{{ route('admin.data_pengguna') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center align-middle'},
                {data: 'username', name: 'username', className: 'text-center align-middle'},
                {data: 'nama', name: 'nama', className: 'text-center align-middle'},
                {data: 'peran', name: 'peran', className: 'text-center align-middle'},
                {data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center align-middle'}
            ]
        });

        $('#tabel-pengguna').on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            var username = $(this).data('username');
            var nama = $(this).data('nama');
            var peran = $(this).data('peran');

            $('#edit_username').val(username);
            $('#edit_nama').val(nama);
            $('#edit_peran').val(peran);
            $('#edit_sandi').val('');

            $('#form-edit').attr('action', '/admin/data_pengguna/' + id);
            $('#modal-edit').modal('show');
        });

        $('#tabel-pengguna').on('click', '.btn-hapus', function() {
            var id = $(this).data('id');

            if (confirm('Apakah kamu yakin ingin menghapus data pengguna?')) {
                $('#form-hapus').attr('action', '/admin/data_pengguna/' + id).submit();
            }
        });
    });
</script>
@endpush
