<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Presensi {{ $kelas->nama_kelas }}</title>
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
        $semester = $kelas->akademik?->semester ?? '';
        $label_semester = (strtoupper($semester) == 'GL' || $semester == '1') ? 'Ganjil' : 'Genap';
        $total_pertemuan = count($data_pertemuan);
        $bobot_kelas = (float)($kelas->bobot_kelas ?? 0);
    @endphp

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

    <div class="doc-title">DATA PRESENSI</div>

    <table class="info-table">
        <tr>
            <td width="18%">Nama Kelas</td>
            <td width="2%">:</td>
            <td width="30%">{{ $kelas->nama_kelas }}</td>
            <td width="18%">Mata Kuliah</td>
            <td width="2%">:</td>
            <td width="30%">{{ $kelas->matkul?->nama_matkul ?? $kelas->kode_matkul }}</td>
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
            <td>Total Pertemuan</td>
            <td>:</td>
            <td>{{ $total_pertemuan }} Pertemuan</td>
        </tr>
        <tr>
            <td>Kontrak Kehadiran</td>
            <td>:</td>
            <td>{{ $bobot_kelas }} %</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="6%">No</th>
                <th width="42%">Mahasiswa</th>
                <th width="18%">Jumlah Kehadiran</th>
                <th width="20%">Persentase Kehadiran</th>
                <th width="14%">Poin Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekap_presensi as $index => $baris)
                @php
                    $jumlah_hadir = $baris['jumlah_hadir'];
                    $persen_kehadiran = ($total_pertemuan > 0) ? round(($jumlah_hadir / $total_pertemuan) * 100, 1) : 0;
                    $poin_kehadiran = round(($persen_kehadiran * $bobot_kelas) / 100, 1);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $baris['nama'] }} ({{ $baris['nim'] }})</td>
                    <td class="text-center">{{ $jumlah_hadir }} / {{ $total_pertemuan }} Pertemuan</td>
                    <td class="text-center">{{ $persen_kehadiran }} %</td>
                    <td class="text-center">{{ $poin_kehadiran }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Data Mahasiswa Tidak Ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-page">
        Halaman <span class="page-number"></span>
    </div>

</body>
</html>