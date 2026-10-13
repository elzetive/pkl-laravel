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
                    <h3 class="card-title"><strong>Data Dosen</strong></h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4" style="gap: 8px;">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>
                        <a href="{{ route('admin.data_dosen.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-import">
                            <i class="fas fa-file-excel"></i> Import Excel
                        </button>
                        <form action="{{ route('admin.data_dosen.reset') }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin mereset data dosen?')">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sync-alt"></i> Reset Data
                            </button>
                        </form>
                        <a href="{{ route('admin.data_dosen.pdf') }}" target="_blank" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabel-dosen" style="width: 100%">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Kontak</th>
                                    <th>Email</th>
                                    <th>Kelamin</th>
                                    <th>Foto</th>
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

    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Data Dosen</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-left">
                        <div class="form-group">
                            <label for="edit_nik">NIK</label>
                            <input type="text" id="edit_nik" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_nama">Nama</label>
                            <input type="text" class="form-control" name="nama" id="edit_nama" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_kontak">Kontak</label>
                            <input type="text" class="form-control" name="kontak" id="edit_kontak" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_kelamin">Jenis Kelamin</label>
                            <select name="kelamin" id="edit_kelamin" class="form-control" required>
                                <option value="L">Laki-Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_img"></label>
                            <input type="file" class="form-control-file" name="img" id="edit_img" accept="image/*">
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Dosen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.data_dosen.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-left">
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik') }}" required>
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

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Data Dosen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.data_dosen.import') }}" method="POST" enctype="multipart/form-data">
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
    $(document).ready( function () {
        var table = $('#tabel-dosen').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: "{{ route('admin.data_dosen') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center align-middle' },
                { data: 'nik', name: 'nik', className: 'text-center align-middle'},
                { data: 'nama', name: 'nama', className: 'text-center align-middle'},
                { data: 'kontak', name: 'kontak', className: 'text-center align-middle'},
                { data: 'email', name: 'email', className: 'text-center align-middle'},
                { data: 'kelamin', name: 'kelamin', className: 'text-center align-middle' },
                { data: 'img', name: 'img', orderable: false, searchable: false, className: 'text-center align-middle' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center align-middle' }
            ]
        });

            $('#tabel-dosen').on('click', '.btn-edit', function() {
            var nik = $(this).data('nik');
            var nama = $(this).data('nama');
            var kontak = $(this).data('kontak');
            var email = $(this).data('email');
            var kelamin = $(this).data('kelamin');

            $('#edit_nik').val(nik);
            $('#edit_nama').val(nama);
            $('#edit_kontak').val(kontak);
            $('#edit_email').val(email);
            $('#edit_kelamin').val(kelamin);
            $('#edit_img').val('');

            $('#form-edit').attr('action', '/admin/data_dosen/' + nik);
            $('#modal-edit').modal('show');
        });

        $('#tabel-dosen').on('click', '.btn-hapus', function() {
            var nik = $(this).data('nik');

            if (confirm('Apakah kamu yakin ingin menghapus data dosen ini?')) {
                $('#form-hapus').attr('action', '/admin/data_dosen/' + nik).submit();
            }
        });
    });
</script>

@endpush
