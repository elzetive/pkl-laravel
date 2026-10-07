@extends('layouts.app')

@section('content')
    <div class="content-header">
        <form action="{{ route('admin.data_kelas_matkul') }}" method="GET" class="mb-3">
            <div class="row align-items-center">
                <div class="col-md-3 col-sm-6">
                    <select name="kode_akademik" id="kode_akademik" class="form-control" required>
                        <option value="">-- Pilih Tahun Akademik --</option>
                        @foreach ($akademik as $a)
                            @php
                                $idGanjil = in_array(strtoupper($a->semester), ['GL', '1', 'GANJIL']);
                            @endphp
                            <option value="{{ $a->kode_akademik }}" {{ $pilih_akademik == $a->kode_akademik ? 'selected' : '' }}>
                                {{ $a->tahun }} - {{ $idGanjil ? 'Ganjil' : 'Genap' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search mr-1"></i> Tampilkan Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="content">
        <div class="container-fluid">
            @if($pilih_akademik)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Kelas Mata Kuliah</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center flex-wrap mb-3" style="gap: 8px;">
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-tambah">
                            <i class="fas fa-plus"></i> Tambah Data
                        </button>
                        <a href="{{ route('admin.data_kelas_matkul.export') }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-import">
                            <i class="fas fa-file-excel"></i> Import Excel
                        </button>
                        <a href="{{ route('admin.data_kelas_matkul.pdf', request()->query()) }}" target="_blank" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>

                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Kelas</th>
                                <th>Akademik</th>
                                <th>Matkul</th>
                                <th>Jurusan</th>
                                <th>Dosen</th>
                                <th width="28%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelas as $item)
                            @php
                                $isGanjil = in_array(strtoupper($item->akademik->semester ?? ''), ['GL', '1', 'GANJIL']);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_kelas }}</td>
                                <td>{{ $item->akademik ? $item->akademik->tahun . ' - ' . ($isGanjil ? 'Ganjil' : 'Genap') : $item->kode_akademik }}</td>
                                <td>{{ $item->matkul->nama_matkul ?? $item->kode_matkul }}</td>
                                <td>{{ $item->jurusan->nama_jurusan ?? $item->kode_jurusan }}</td>
                                <td>{{ $item->dosen->nama ?? $item->nik }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit"
                                        data-id="{{ $item->id_kelas }}"
                                        data-kode-akademik="{{ $item->kode_akademik }}"
                                        data-kode-matkul="{{ $item->kode_matkul }}"
                                        data-kode-jurusan="{{ $item->kode_jurusan }}"
                                        data-nik="{{ $item->nik }}"
                                        data-nama-kelas="{{ $item->nama_kelas }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <form action="{{ route('admin.data_kelas_matkul.destroy', $item->id_kelas) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="filter_akademik" value="{{ $pilih_akademik }}">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.data_detail_kelas', $item->id_kelas) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-list"></i> Detail
                                    </a>

                                    <a href="{{ route('admin.data_pertemuan', $item->id_kelas) }}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-calendar-alt"></i> Pertemuan
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Data kelas belum tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Data Kelas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.data_kelas_matkul.store') }}" method="POST">
                @csrf
                <input type="hidden" name="filter_akademik" value="{{ $pilih_akademik }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tambah_kode_akademik">Tahun Akademik</label>
                        <select name="kode_akademik" id="tambah_kode_akademik" class="form-control" required>
                            <option value="">-- Pilih Akademik --</option>
                            @foreach ($akademik as $a)
                                @php $isGanjil = in_array(strtoupper($a->semester), ['GL', '1', 'GANJIL']); @endphp
                                <option value="{{ $a->kode_akademik }}" {{ $pilih_akademik == $a->kode_akademik ? 'selected' : '' }}>
                                    {{ $a->tahun }} - {{ $isGanjil ? 'Ganjil' : 'Genap' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tambah_kode_matkul">Mata Kuliah</label>
                        <select name="kode_matkul" id="tambah_kode_matkul" class="form-control" required>
                            <option value="">-- Pilih Matkul --</option>
                            @foreach ($matkul as $m)
                                <option value="{{ $m->kode_matkul }}">{{ $m->kode_matkul }} - {{ $m->nama_matkul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tambah_kode_jurusan">Jurusan</label>
                        <select name="kode_jurusan" id="tambah_kode_jurusan" class="form-control" required>
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach ($jurusan as $j)
                                <option value="{{ $j->kode_jurusan }}">{{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tambah_nik">Dosen Pengampu</label>
                        <select name="nik" id="tambah_nik" class="form-control" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach ($dosen as $d)
                                <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tambah_nama_kelas">Nama Kelas</label>
                        <input type="text" name="nama_kelas" id="tambah_nama_kelas" class="form-control" placeholder="Contoh: TI-2B" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Data Kelas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="filter_akademik" value="{{ $pilih_akademik }}">
                <input type="hidden" name="id_kelas" id="edit_id_kelas">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_kode_akademik">Tahun Akademik</label>
                        <select name="kode_akademik" id="edit_kode_akademik" class="form-control" required>
                            <option value="">-- Pilih Akademik --</option>
                            @foreach ($akademik as $a)
                                @php $isGanjil = in_array(strtoupper($a->semester), ['GL', '1', 'GANJIL']); @endphp
                                <option value="{{ $a->kode_akademik }}">{{ $a->tahun }} - {{ $isGanjil ? 'Ganjil' : 'Genap' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_kode_matkul">Mata Kuliah</label>
                        <select name="kode_matkul" id="edit_kode_matkul" class="form-control" required>
                            <option value="">-- Pilih Matkul --</option>
                            @foreach ($matkul as $m)
                                <option value="{{ $m->kode_matkul }}">{{ $m->kode_matkul }} - {{ $m->nama_matkul }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_kode_jurusan">Jurusan</label>
                        <select name="kode_jurusan" id="edit_kode_jurusan" class="form-control" required>
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach ($jurusan as $j)
                                <option value="{{ $j->kode_jurusan }}">{{ $j->kode_jurusan }} - {{ $j->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_nik">Dosen Pengampu</label>
                        <select name="nik" id="edit_nik" class="form-control" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach ($dosen as $d)
                                <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_kelas">Nama Kelas</label>
                        <input type="text" name="nama_kelas" id="edit_nama_kelas" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
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
                <h4 class="modal-title">Import Data Kelas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.data_kelas_matkul.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file_excel">Pilih File Excel (.xls / .xlsx)</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control-file" accept=".xls,.xlsx" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(session('success'))
        alert("{{ session('success') }}");
    @endif

    @if(session('error'))
        alert("{{ session('error') }}");
    @endif

    $(document).ready(function () {
        $('#modal-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);

            var id            = button.attr('data-id');
            var kode_akademik = button.attr('data-kode-akademik');
            var kode_matkul   = button.attr('data-kode-matkul');
            var kode_jurusan  = button.attr('data-kode-jurusan');
            var nik           = button.attr('data-nik');
            var nama_kelas    = button.attr('data-nama-kelas');

            var modal = $(this);

            var updateUrl = "{{ route('admin.data_kelas_matkul.update', ':id') }}".replace(':id', id);
            modal.find('#form-edit').attr('action', updateUrl);

            modal.find('#edit_id_kelas').val(id);
            modal.find('#edit_kode_akademik').val(kode_akademik).trigger('change');
            modal.find('#edit_kode_matkul').val(kode_matkul).trigger('change');
            modal.find('#edit_kode_jurusan').val(kode_jurusan).trigger('change');
            modal.find('#edit_nik').val(nik).trigger('change');
            modal.find('#edit_nama_kelas').val(nama_kelas);
        });
    });
</script>
@endpush

