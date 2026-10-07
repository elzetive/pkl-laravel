@extends('layouts.app')

@section('title', 'Admin - Data Detail Kelas Matkul')

@push('css')
<style>
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after {
        font-family: "Font Awesome 5 Free" !important;
        font-weight: 900;
    }
</style>
@endpush

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
                    <h3 class="card-title"><strong>Data Kelas Mata Kuliah</strong></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 d-flex align-items-center justify-content-center mb-3">
                            @php
                                $namaFoto = $dosen->img ?? '';
                                if ($namaFoto) {
                                    $namaFoto = str_replace('dosen/', '', $namaFoto);
                                }
                            @endphp

                            @if(!empty($namaFoto) && file_exists(public_path('storage/dosen/' . $namaFoto)))
                                <img src="{{ asset('storage/dosen/' . $namaFoto) }}"
                                     alt="Foto Dosen"
                                     class="img-fluid img-thumbnail rounded shadow-sm"
                                     style="max-height: 220px; width: auto; object-fit: cover;">
                            @else
                                <img src="https://placehold.co/180x220?text=No+Foto"
                                     alt="Default Foto"
                                     class="img-fluid img-thumbnail rounded shadow-sm"
                                     style="max-height: 220px; width: auto; object-fit: cover;">
                            @endif
                        </div>

                        <div class="col-md-5 mb-3 d-flex align-items-center justify-content-center">
                            <table class="table table-borderless table-sm w-auto">
                                <tr>
                                    <td style="width: 140px;"><strong>NIK</strong></td>
                                    <td style="width: 10px;">:</td>
                                    <td>{{ $dosen->nik ?? $kelas->nik }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dosen Pengampu</strong></td>
                                    <td>:</td>
                                    <td>{{ $dosen->nama ?? $kelas->nik }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mata Kuliah</strong></td>
                                    <td>:</td>
                                    <td>{{ $matkul->nama_matkul ?? $kelas->kode_matkul }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Judul Materi</strong></td>
                                    <td>:</td>
                                    <td>{{ $pertemuan->judul_pertemuan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>:</td>
                                    <td>{{ $kelas->nama_kelas }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jurusan</strong></td>
                                    <td>:</td>
                                    <td>{{ $jurusan->nama_jurusan ?? $kelas->kode_jurusan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Hari</strong></td>
                                    <td>:</td>
                                    <td>{{ date('l', strtotime($pertemuan->tanggal)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal</strong></td>
                                    <td>:</td>
                                    <td>{{ date('j F Y', strtotime($pertemuan->tanggal)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pertemuan Ke</strong></td>
                                    <td>:</td>
                                    <td>{{ $pertemuan->pertemuan_ke }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-4 text-center d-flex flex-column align-items-center justify-content-center">
                            <div class="mb-3">
                                @php
                                    $kodeQr = $pertemuan->id_pertemuan;
                                @endphp
                                <div class="d-inline-block p-2 bg-white rounded shadow-sm border">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate($kodeQr) !!}
                                </div>
                                <p class="text-muted small mt-2 mb-0">Scan QR ID: <strong>{{ $kodeQr }}</strong></p>
                            </div>

                            <div class="d-flex align-items-center justify-content-center">
                                @if ((string)$pertemuan->status_pertemuan === '1')
                                    <div class="mr-2">
                                        <span class="small font-weight-bold">Sisa Waktu:</span>
                                        <span id="countdown-timer" class="badge badge-danger p-2" style="font-size: 0.9rem;">05:00</span>
                                    </div>
                                    <form id="form-tutup-presensi"
                                          action="{{ route('admin.data_presensi.edit_status', ['id_pertemuan' => $pertemuan->id_pertemuan, 'status' => 0]) }}"
                                          method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menutup presensi?')">
                                            <i class="fas fa-lock mr-1"></i> Tutup Presensi
                                        </button>
                                    </form>
                                @else
                                    <span></span>
                                    <form action="{{ route('admin.data_presensi.edit_status', ['id_pertemuan' => $pertemuan->id_pertemuan, 'status' => 1]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin membuka presensi?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm shadow-sm">
                                            <i class="fas fa-unlock mr-1"></i> Buka Presensi
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title font-weight-bold mb-0"><strong>Data Detail Kelas</strong></h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center align-middle" id="table-presensi">
                            <thead>
                                <tr>
                                    <th style="width: 70px">No</th>
                                    <th>Mahasiswa</th>
                                    <th style="width: 180px">Status</th>
                                    <th style="width: 150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataPresensi as $index => $item )
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="text-left">{{ $item->nim }} - {{ $item->mahasiswa->nama ?? '-'}}</td>
                                    <td>
                                        @if ($item->status_kehadiran == 'H')
                                        <span>Hadir</span>
                                        @elseif ($item->status_kehadiran == 'I')
                                        <span>Izin</span>
                                        @elseif ($item->status_kehadiran == 'S')
                                        <span>Sakit</span>
                                        @elseif ($item->status_kehadiran == 'D')
                                        <span>Dispensasi</span>
                                        @else
                                        <span>Alpa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-warning btn-sm text-white btn-edit"
                                                data-id="{{ $item->id_presensi }}"
                                                data-nim="{{ $item->nim }}"
                                                data-nama="{{ $item->mahasiswa->nama ?? '-' }}"
                                                data-status="{{ $item->status_kehadiran }}">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">
                                        Tidak ada mahasiswa yang terdaftar.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- Modal Edit Status Kehadiran --}}
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Status Kehadiran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.data_presensi.update_kehadiran') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_presensi" id="edit_id_presensi">
                    <input type="hidden" name="id_pertemuan" id="edit_id_pertemuan" value="{{ $pertemuan->id_pertemuan }}">
                    <input type="hidden" name="nim" id="edit_nim">

                    <div class="form-group">
                        <label for="edit_mahasiswa">Mahasiswa</label>
                        <input type="text" class="form-control" id="edit_mahasiswa" readonly>
                    </div>

                    <div class="form-group">
                        <label for="edit_status_kehadiran">Pilih Status Kehadiran</label>
                        <select name="status_kehadiran" id="edit_status_kehadiran" class="form-control" required>
                            <option value="H">Hadir</option>
                            <option value="I">Izin</option>
                            <option value="S">Sakit</option>
                            <option value="D">Dispensasi</option>
                            <option value="A">Alpa</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="btn_edit_kehadiran">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let currentJumlahHadir = {{ $dataPresensi->where('status_kehadiran', 'H')->count() }};
        const idPertemuan = "{{ $pertemuan->id_pertemuan }}";
        const status = parseInt("{{ $pertemuan->status_pertemuan }}");
        const storageKey = 'timer_pertemuan_' + idPertemuan;

        if (idPertemuan) {
            setInterval(function() {
                fetch(`/admin/data_presensi/cek_presensi/${idPertemuan}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.jumlah_hadir !== currentJumlahHadir) {
                            window.location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }, 3000);
        }

        if (status === 1) {
            let waktuSelesai;
            const durasiDetik = 300;
            const simpanTimer = localStorage.getItem(storageKey);

            if (simpanTimer) {
                waktuSelesai = parseInt(simpanTimer);
            } else {
                @php
                    $updatedAt = !($pertemuan->updated_at)
                    ? strtotime($pertemuan->updated_at) * 1000
                    : time() * 1000;
                @endphp
                const waktuMulai = {{ $updatedAt }};
                waktuSelesai = waktuMulai + (durasiDetik * 1000);
                localStorage.setItem(storageKey, waktuSelesai);
            }

            const timer = setInterval(function() {
                const sekarang = new Date().getTime();
                const selisih = waktuSelesai - sekarang;

                if (selisih <= 0) {
                    clearInterval(timer);
                    $('#countdown-timer').text('00:00');
                    localStorage.removeItem(storageKey);

                    const formTutup = document.getElementById('form-tutup-presensi');
                    if (formTutup) {
                        formTutup.submit();
                    }
                    return;
                }

                let menit = Math.floor(selisih / (1000 * 60));
                let detik = Math.floor((selisih % (1000 * 60)) / 1000);

                menit = menit < 10 ? '0' + menit : menit;
                detik = detik < 10 ? '0' + detik : detik;

                $('#countdown-timer').text(menit + ':' + detik);
            }, 1000);
        } else {
            localStorage.removeItem(storageKey);
        }

        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            const nim = $(this).data('nim');
            const nama = $(this).data('nama');
            const statusKehadiran = $(this).data('status');

            $('#edit_id_presensi').val(id);
            $('#edit_nim').val(nim);
            $('#edit_mahasiswa').val(nim + ' - ' + nama);
            $('#edit_status_kehadiran').val(statusKehadiran);
            $('#modal-edit').modal('show');
        });
    });
</script>
@endpush
