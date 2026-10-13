@extends('layouts.app')

@section('title', 'Data Akademik')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Data Akademik</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Tahun Akademik</h3>
            </div>
            <div class="card-body">
                    <div class="d-flex align-items-center mb-4" style="gap: 8px;">
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

                <table class="table table-bordered table-striped" id="tabel-akademik" style="width: 100%">
                    <thead>
                        <tr align="center">
                            <th width="5%">No</th>
                            <th>Kode Akademik</th>
                            <th>Tahun</th>
                            <th>Semester</th>
                            <th>Status</th>
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

<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="modalTambah" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambah">Tambah Data Akademik</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.data_akademik.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kode_akademik">Kode Akademik</label>
                        <input type="text" name="kode_akademik" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="text" name="tahun" class="form-control" maxlength="4" required>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select name="semester" class="form-control" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="gl">Ganjil</option>
                            <option value="gn">Genap</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="isactive">Status</label>
                        <select name="isactive" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modalEdit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEdit">Edit Data Akademik</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_kode_akademik">Kode Akademik</label>
                        <input type="text" id="edit_kode_akademik" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_tahun">Tahun</label>
                        <input type="text" name="tahun" id="edit_tahun" class="form-control" maxlength="4" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_semester">Semester</label>
                        <select name="semester" id="edit_semester" class="form-control" required>
                            <option value="gl">Ganjil</option>
                            <option value="gn">Genap</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_isactive">Status</label>
                        <select name="isactive" id="edit_isactive" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary text text-white">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modalImport" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalImport">Import Data Akademik</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.data_akademik.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file_excel">Pilih File Excel</label>
                        <input type="file" name="file_excel" class="form-control-file" accept=".xls, .xlsx" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Import</button>
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
        var table = $('#tabel-akademik').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: "{{ route('admin.data_akademik') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center align-middle'},
                { data: 'kode_akademik', name: 'kode_akademik', className: 'text-center align-middle'},
                { data: 'tahun', name: 'tahun', className: 'text-center align-middle'},
                { data: 'semester', name: 'semester', className: 'text-center align-middle'},
                { data: 'isactive', name: 'isactive', className: 'text-center align-middle'},
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center align-middle'}
            ]
        });

        $('#tabel-akademik').on('click', '.btn-edit', function () {
            var kode_akademik = $(this).data('kode_akademik');
            var semester = $(this).data('semester');
            var tahun = $(this).data('tahun');
            var isactive = $(this).data('isactive');

            $('#edit_kode_akademik').val(kode_akademik);
            $('#edit_semester').val(semester);
            $('#edit_tahun').val(tahun);
            $('#edit_isactive').val(isactive);

            $('#form-edit').attr('action', '/admin/data_akademik/' + kode_akademik);

            $('#modal-edit').modal('show');
        });

        $('#tabel-akademik').on('click', '.btn-hapus', function () {
            var kode_akademik = $(this).data('kode_akademik');

            if (confirm('Apakah kamu yakin ingin menghapus data ini?')) {
                $('#form-hapus').attr('action', '/admin/data_akademik/' + kode_akademik).submit();
            }
        });
    });
</script>
@endpush
