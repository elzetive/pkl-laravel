@extends('layouts.app')

@section('content')
    <div class="content-header">
        <form action="{{ route('dosen.data_kelas_matkul') }}" method="GET" class="mb-3">
            <div class="row align-items-center">
                <div class="col-md-3 col-sm-6">
                    <select name="kode_akademik" id="kode_akademik" class="form-control" required>
                        <option value="">-- Pilih Tahun Akademik --</option>
                        @foreach ($akademik as $a)
                            @php
                                $is_ganjil = in_array(strtoupper($a->semester), ['GL', '1', 'GANJIL']);
                            @endphp
                            <option value="{{ $a->kode_akademik }}" {{ $pilih_akademik == $a->kode_akademik ? 'selected' : '' }}>
                                {{ $a->tahun }} - {{ $is_ganjil ? 'Ganjil' : 'Genap' }}
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
                                $is_ganjil = in_array(strtoupper($item->akademik->semester ?? ''), ['GL', '1', 'GANJIL']);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_kelas }}</td>
                                <td>{{ $item->akademik ? $item->akademik->tahun . ' - ' . ($is_ganjil ? 'Ganjil' : 'Genap') : $item->kode_akademik }}</td>
                                <td>{{ $item->matkul->nama_matkul ?? $item->kode_matkul }}</td>
                                <td>{{ $item->jurusan->nama_jurusan ?? $item->kode_jurusan }}</td>
                                <td>{{ $item->dosen->nama ?? $item->nik }}</td>
                                <td>
                                    <a href="{{ route('dosen.data_detail_kelas', $item->id_kelas) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-list"></i> Detail
                                    </a>

                                    <a href="{{ route('dosen.data_pertemuan', $item->id_kelas) }}" class="btn btn-danger btn-sm">
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
@endsection

@push('scripts')
<script>
    @if(session('success'))
        alert("{{ session('success') }}");
    @endif

    @if(session('error'))
        alert("{{ session('error') }}");
    @endif

</script>
@endpush

