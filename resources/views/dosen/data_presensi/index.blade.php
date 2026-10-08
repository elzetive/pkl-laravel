@extends('layouts.app')

@section('title', 'Data Detail Kelas Matkul')

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
                                $nama_foto =$dosen->img ?? '';
                                if ($nama_foto) {
                                    $nama_foto = str_replace('dosen/', '',$nama_foto);
                                }
                            @endphp

                            @if(!empty($nama_foto) && file_exists(public_path('storage/dosen/' .$nama_foto)))
                                <img src="{{ asset('storage/dosen/' . $nama_foto) }}"
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
                                    <td>{{ \Carbon\Carbon::parse($pertemuan->tanggal)->translatedFormat('l') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal</strong></td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($pertemuan->tanggal)->translatedFormat('d F Y') }}</td>
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
                                    $kode_qr =$pertemuan->id_pertemuan;
                                @endphp
                                <div class="d-inline-block p-2 bg-white rounded shadow-sm border">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(160)->generate($kode_qr) !!}
                                </div>
                                <p class="text-muted small mt-2 mb-0">Scan QR ID: <strong>{{ $kode_qr }}</strong></p>
                            </div>

                            <div class="d-flex align-items-center justify-content-center">
                                @php
                                    $route_edit_status = Auth::user()->peran === 'D' ? 'dosen.data_presensi.edit_status' : 'admin.data_presensi.edit_status';
                                @endphp

                                @if ((string)$pertemuan->status_pertemuan === '1')
                                    <div class="mr-2">
                                        <span class="small font-weight-bold">Sisa Waktu:</span>
                                        <span id="countdown-timer" class="badge badge-danger p-2" style="font-size: 0.9rem;">05:00</span>
                                    </div>
                                    <form id="form-tutup-presensi"
                                          action="{{ route($route_edit_status, ['id_pertemuan' =>$pertemuan->id_pertemuan, 'status' => 0]) }}"
                                          method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menutup presensi?')">
                                            <i class="fas fa-lock mr-1"></i> Tutup Presensi
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route($route_edit_status, ['id_pertemuan' =>$pertemuan->id_pertemuan, 'status' => 1]) }}"
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
                                @forelse ($data_presensi as $index =>$item)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="text-left">{{ $item->nim }} - {{ $item->mahasiswa->nama_mahasiswa ?? $item->mahasiswa->nama ?? '-' }}</td>
                                    <td>
                                        @if (in_array($item->status_kehadiran, ['H', 'Hadir']))
                                            <span class="badge badge-success px-2 py-1">Hadir</span>
                                        @elseif (in_array($item->status_kehadiran, ['I', 'Izin']))
                                            <span class="badge badge-info px-2 py-1">Izin</span>
                                        @elseif (in_array($item->status_kehadiran, ['S', 'Sakit']))
                                            <span class="badge badge-warning px-2 py-1">Sakit</span>
                                        @elseif (in_array($item->status_kehadiran, ['D', 'Dispensasi']))
                                            <span class="badge badge-primary px-2 py-1">Dispensasi</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">Alpa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-warning btn-sm text-white btn-edit"
                                                data-id_presensi="{{ $item->id_presensi }}"
                                                data-nim="{{ $item->nim }}"
                                                data-nama="{{ $item->mahasiswa->nama_mahasiswa ?? $item->mahasiswa->nama ?? '-' }}"
                                                data-status_kehadiran="{{ $item->status_kehadiran }}">
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

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Status Kehadiran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @php
                $route_update_kehadiran = Auth::user()->peran === 'D' ? 'dosen.data_presensi.update_kehadiran' : 'admin.data_presensi.update_kehadiran';
            @endphp

            <form action="{{ route($route_update_kehadiran) }}" method="POST">
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let jumlah_hadir = {{ $data_presensi->whereIn('status_kehadiran', ['H', 'Hadir'])->count() }};
        const id_pertemuan = "{{ $pertemuan->id_pertemuan }}";
        const status_pertemuan = parseInt("{{ $pertemuan->status_pertemuan }}");
        const storage_key = 'timer_pertemuan_' + id_pertemuan;

        if (id_pertemuan) {
            const peran_path = "{{ Auth::user()->peran === 'D' ? '/dosen' : '/admin' }}";
            setInterval(function() {
                fetch(`${peran_path}/data_presensi/cek_presensi/${id_pertemuan}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.jumlah_hadir !== jumlah_hadir) {
                            window.location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }, 3000);
        }

        if (status_pertemuan === 1) {
            let waktu_selesai;
            const durasi_detik = 300;
            const simpan_timer = localStorage.getItem(storage_key);

            if (simpan_timer) {
                waktu_selesai = parseInt(simpan_timer);
            } else {
                @php
                    $updated_at_timestamp = $pertemuan->updated_at ? $pertemuan->updated_at->timestamp * 1000 : time() * 1000;
                @endphp
                const waktu_mulai = {{ $updated_at_timestamp }};
                waktu_selesai = waktu_mulai + (durasi_detik * 1000);
                localStorage.setItem(storage_key, waktu_selesai);
            }

            const timer_interval = setInterval(function() {
                const sekarang = new Date().getTime();
                const selisih = waktu_selesai - sekarang;

                if (selisih <= 0) {
                    clearInterval(timer_interval);
                    $('#countdown-timer').text('00:00');
                    localStorage.removeItem(storage_key);

                    const form_tutup = document.getElementById('form-tutup-presensi');
                    if (form_tutup) {
                        form_tutup.submit();
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
            localStorage.removeItem(storage_key);
        }

        $(document).on('click', '.btn-edit', function() {
            const id_presensi = $(this).data('id_presensi');
            const nim = $(this).data('nim');
            const nama = $(this).data('nama');
            const status_kehadiran = $(this).data('status_kehadiran');

            $('#edit_id_presensi').val(id_presensi);
            $('#edit_nim').val(nim);
            $('#edit_mahasiswa').val(nim + ' - ' + nama);
            $('#edit_status_kehadiran').val(status_kehadiran);
            $('#modal-edit').modal('show');
        });
    });
</script>
@endpush