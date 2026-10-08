@extends('layouts.app')

@section('title', 'Data Detail Kelas Matkul')

@section('content')

<div class="content-header">
    <div class="container-fluid"></div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <strong>Data Kelas Mata Kuliah</strong>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table>
                            <tr>
                                <td><strong>Nama Kelas</strong></td>
                                <td class="px-2">=</td>
                                <td>{{ $kelas?->nama_kelas }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tahun / Semester</strong></td>
                                <td class="px-2">=</td>
                                <td>
                                    {{ $kelas?->akademik?->tahun ?? '-' }} /
                                    {{ in_array(strtoupper($kelas?->akademik?->semester ?? ''), ['GL', '1']) ? 'Ganjil' : 'Genap' }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Mata Kuliah</strong></td>
                                <td class="px-2">=</td>
                                <td>{{ $kelas?->matkul?->nama_matkul ?? $kelas?->kode_matkul }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table>
                            <tr>
                                <td><strong>Jurusan</strong></td>
                                <td class="px-2">=</td>
                                <td>{{ $kelas?->jurusan?->nama_jurusan ?? $kelas?->kode_jurusan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dosen Pengampu</strong></td>
                                <td class="px-2">=</td>
                                <td>{{ $kelas?->dosen?->nama ?? $kelas?->nik }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><strong>Data Detail Kelas</strong></h3>
            </div>
            <div class="card-body">
                @php
                    $peran = auth()->user()->peran === 'D' ? 'dosen.' : 'admin.';
                @endphp
                <div align="right">
                    <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                    <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#modal-import">
                        <i class="fas fa-file-excel"></i> Import Data
                    </button>
                    <a href="{{ route($peran . 'data_detail_kelas.export', $kelas->id_kelas) }}" class="btn btn-info mb-2">
                        <i class="fas fa-file-download"></i> Export Excel
                    </a>
                    <a href="{{ route($peran . 'data_detail_kelas.pdf', $kelas->id_kelas) }}" 
                       target="_blank" 
                       class="btn btn-danger mb-2">
                        <i class="fas fa-file-pdf me-1"></i> Cetak PDF
                    </a>                    
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr align="center">
                            <th width="5%">No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detail_kelas as $index => $item)
                            <tr>
                                <td align="center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->mahasiswa?->nama ?? '-' }}</td>
                                <td align="center">
                                    <form action="{{ route($peran . 'data_detail_kelas.destroy', ['id_kelas' => $kelas->id_kelas, 'nim' => $item->nim]) }}" method="POST" onsubmit="return confirm('Yakin Hapus Data Ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" align="center">Data Tidak Ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-tambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Data Detail</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route($peran . 'data_detail_kelas.store', $kelas->id_kelas) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="nim">Pilih Mahasiswa</label>
            <select name="nim" class="form-control select2" id="nim" required style="width: 100%;">
              <option value="">Pilih Mahasiswa</option>
              @foreach ($data_mahasiswa as $mhs)
                <option value="{{ $mhs->nim }}">{{ $mhs->nim }} - {{ $mhs->nama }}</option>
              @endforeach
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
                <h4 class="modal-title">Import Mahasiswa Kelas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route($peran . 'data_detail_kelas.import', $kelas->id_kelas) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file_excel">Pilih File Excel (.xls / .xlsx)</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control-file mb-3" accept=".xls,.xlsx" required>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#modal-tambah').on('shown.bs.modal', function () {
            $('#nim').select2({
                dropdownParent: $('#modal-tambah'),
                placeholder: "Pilih Mahasiswa",
                allowClear: true
            });
        });
    });
</script>
@endpush

@endsection