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
                        <table class="table table-bordered table-striped" id="tabel-matkul">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%">No</th>
                                    <th>Kode Matkul</th>
                                    <th>Nama Matkul</th>
                                    <th>Jumlah SKS</th>
                                    <th>Jumlah CPMK</th>
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

    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Mata Kuliah</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-left">
                        <div class="form-group">
                            <label for="edit_kode">Kode Mata Kuliah</label>
                            <input type="text" id="edit_kode" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_nama">Nama Mata Kuliah</label>
                            <input type="text" class="form-control" name="nama_matkul" id="edit_nama" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_jumlah_sks">Jumlah SKS</label>
                            <input type="number" class="form-control" name="jumlah_sks" id="edit_jumlah_sks" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_jml_cpmk">Jumlah CPMK</label>
                            <input type="number" class="form-control" name="jml_cpmk" id="edit_jml_cpmk" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
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
        var table = $('#tabel-matkul').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: "{{ route('admin.data_matkul') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center align-middle'},
                { data: 'kode_matkul', name: 'kode_matkul', className: 'text-center align-middle'},
                { data: 'nama_matkul', name: 'nama_matkul', className: 'align-middle '},
                { data: 'jumlah_sks', name:'jumlah_sks', className: 'text-center align-middle'},
                { data: 'jml_cpmk', name: 'jml_cpmk', className: 'text-center align-middle'},
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center align-middle'}
            ]
        });

        $('#tabel-matkul').on('click', '.btn-edit', function() {
            var kode = $(this).data('kode');
            var nama = $(this).data('nama');
            var jumlah_sks = $(this).data('jumlah_sks');
            var jml_cpmk = $(this).data('jml_cpmk');

            $('#edit_kode').val(kode);
            $('#edit_nama').val(nama);
            $('#edit_jumlah_sks').val(jumlah_sks);
            $('#edit_jml_cpmk').val(jml_cpmk);

            $('#form-edit').attr('action', '/admin/data_matkul/' + kode);
            $('#modal-edit').modal('show');
        });

        $('#tabel-matkul').on('click', '.btn-hapus', function() {
            var kode = $(this).data('kode');

            if (confirm('Apakah kamu yakin ingin menghapus data mata kuliah?')) {
                $('#form-hapus').attr('action', '/admin/data_matkul/' + kode).submit();
            }
        });
    });
</script>
@endpush
