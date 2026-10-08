<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pertemuan {{ $kelas->nama_kelas }}</title>
    <style>
        @page {
            margin: 15mm 20mm 15mm 20mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.3;
        }
        
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .kop-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }
        .kop-logo img {
            width: 70px;
            height: auto;
        }
        .kop-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 80px;
        }
        .kop-text h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
        }
        .kop-text h3 {
            font-size: 12pt;
            font-weight: bold;
            margin: 2px 0 4px 0;
        }
        .kop-text p {
            font-size: 8.5pt;
            margin: 0;
        }
        .line-double {
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        .doc-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 9.5pt;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 9.5pt;
        }
        .data-table th {
            font-weight: bold;
            text-align: center;
            background-color: #ffffff;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .page-break { page-break-after: always; }
        
        .footer-page {
            position: fixed;
            bottom: -8mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
        }
        .page-number:before {
            content: counter(page);
        }
    </style>
</head>
<body>

@php
    $label_semester = (strtoupper($kelas->akademik?->semester ?? '') == 'GL' || ($kelas->akademik?->semester ?? '') == '1') ? 'Ganjil' : 'Genap';
@endphp

@forelse($pertemuan as $item)
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(file_exists(public_path('storage/dosen/foto-dosen-1790320295.jpg')))
                    <img src="{{ public_path('storage/dosen/foto-dosen-1790320295.jpg') }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                <h2>Jurusan Komputer dan Bisnis</h2>
                <h3>Teknik Informatika</h3>
                <p>Alamat: Jl. Dr. Soetomo No.1, Karangcengis, Sidakaya,</p>
                <p>Kec. Cilacap Sel., Kabupaten Cilacap, Jawa Tengah 53212</p>
            </td>
        </tr>
    </table>

    <div class="line-double"></div>

    <div class="doc-title">DATA PERTEMUAN</div>

    <table class="info-table">
        <tr>
            <td width="18%">Nama Kelas</td>
            <td width="2%">:</td>
            <td width="30%">{{ $kelas->nama_kelas }}</td>
            <td width="18%">Hari / Tanggal</td>
            <td width="2%">:</td>
            <td width="30%">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Mata Kuliah</td>
            <td>:</td>
            <td>{{ $kelas->matkul?->nama_matkul ?? $kelas->kode_matkul }}</td>
            <td>Pertemuan Ke</td>
            <td>:</td>
            <td>{{ $item->pertemuan_ke }}</td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td>:</td>
            <td>{{ $kelas->jurusan?->nama_jurusan ?? $kelas->kode_jurusan }}</td>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $kelas->akademik?->tahun ?? '-' }} / {{ $label_semester }}</td>
        </tr>
        <tr>
            <td>Dosen Pengampu</td>
            <td>:</td>
            <td>{{ $kelas->dosen?->nama_dosen ?? $kelas->dosen?->nama ?? $kelas->nik }}</td>
            <td>Judul Materi</td>
            <td>:</td>
            <td>{{ $item->judul_pertemuan }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="8%">No</th>
                <th width="22%">NIM</th>
                <th width="45%">Nama Mahasiswa</th>
                <th width="25%">Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftar_mahasiswa as $index => $mhs)
                @php
                    $presensi = \App\Models\PresensiModel::where('id_pertemuan', $item->id_pertemuan)
                        ->where('nim', $mhs->nim)
                        ->first();

                    $status = $presensi?->status_kehadiran ?? $presensi?->status ?? 'A';

                    if (in_array($status, ['H', 'Hadir'])) {
                        $teks_status = 'Hadir';
                    } elseif (in_array($status, ['I', 'Izin'])) {
                        $teks_status = 'Izin';
                    } elseif (in_array($status, ['S', 'Sakit'])) {
                        $teks_status = 'Sakit';
                    } elseif (in_array($status, ['D', 'Dispensasi'])) {
                        $teks_status = 'Dispensasi';
                    } else {
                        $teks_status = 'Alpa';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $mhs->nim }}</td>
                    <td class="text-left">{{ $mhs->mahasiswa?->nama_mahasiswa ?? $mhs->mahasiswa?->nama ?? '-' }}</td>
                    <td class="text-center">{{ $teks_status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Data Mahasiswa Tidak Ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@empty
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(file_exists(public_path('storage/dosen/foto-dosen-1790320295.jpg')))
                    <img src="{{ public_path('storage/dosen/foto-dosen-1790320295.jpg') }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                <h2>Jurusan Komputer dan Bisnis</h2>
                <h3>Teknik Informatika</h3>
                <p>Alamat: Jl. Dr. Soetomo No.1, Karangcengis, Sidakaya,</p>
                <p>Kec. Cilacap Sel., Kabupaten Cilacap, Jawa Tengah 53212</p>
            </td>
        </tr>
    </table>
    <div class="line-double"></div>
    <div class="doc-title" style="margin-top: 30px;">Belum Ada Data Pertemuan untuk Kelas Ini</div>
@endforelse

    <div class="footer-page">
        Halaman <span class="page-number"></span>
    </div>

</body>
</html>