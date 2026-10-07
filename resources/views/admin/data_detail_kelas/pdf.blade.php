<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kelas - {{ $kelas->nama_kelas }}</title>
    @include('layouts.include.pdf')
    <style>
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 8px;
            font-size: 12px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 20%;
        }
        .info-colon {
            width: 2%;
        }
    </style>
</head>
<body>
    <table class="header-table" style="width: 100%;">
        <tr>
            <td style="width: 15%; text-align: right; vertical-align: middle; padding-right: 10px;">
                <img src="{{ public_path('storage/dosen/foto-dosen-1790320295.jpg') }}" class="logo" style="display: inline-block;" alt="Logo">            
            </td>
            <td style="width: 70%; text-align: center;" class="header-text">
                <h2>Jurusan Komputer dan Informatika</h2>
                <h3>Teknik Informatika</h3>
                <p>
                    Alamat: Jl. Dr. Soetomo No. 1, Karangcengis, Sidakaya, <br>
                    Kec. Cilacap Selatan, Kab. Cilacap, Jawa Tengah 53212
                </p>
            </td>
            <td style="width: 15%"></td>
        </tr>
    </table>

    <div class="line-double"></div>
    <div class="document-title" style="text-align: center; margin: 15px 0; font-weight: bold;">
        DETAIL DATA KELAS
    </div>

    @php
        $semester = $kelas->akademik->semester ?? '';
        $isGanjil = in_array(strtoupper($semester), ['GL', '1', 'GANJIL']);
    @endphp

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Kelas</td>
            <td class="info-colon">:</td>
            <td><strong>{{ $kelas->nama_kelas }}</strong></td>
            <td class="info-label">Mata Kuliah</td>
            <td class="info-colon">:</td>
            <td>{{ $kelas->matkul->nama_matkul ?? $kelas->kode_matkul }}</td>
        </tr>
        <tr>
            <td class="info-label">Tahun Akademik</td>
            <td class="info-colon">:</td>
            <td>
                @if ($kelas->akademik)
                    {{ $kelas->akademik->tahun }} - {{ $isGanjil ? 'Ganjil' : 'Genap' }}
                @else
                    {{ $kelas->kode_akademik }}
                @endif
            </td>
            <td class="info-label">Jurusan</td>
            <td class="info-colon">:</td>
            <td>{{ $kelas->jurusan->nama_jurusan ?? $kelas->kode_jurusan }}</td>
        </tr>
        <tr>
            <td class="info-label">Dosen Pengampu</td>
            <td class="info-colon">:</td>
            <td colspan="4">{{ $kelas->dosen->nama ?? $kelas->nik }}</td>
        </tr>
    </table>

    <h4 style="margin-bottom: 8px;">Daftar Mahasiswa</h4>
    <table class="content-table" border="1" cellspacing="0" cellpadding="5" style="width: 100%; table-layout: fixed; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 25%;">NIM</th>
                <th style="width: 45%;">Nama Mahasiswa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kelas->detail_kelas as $index => $detail)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $detail->mahasiswa->nim ?? $detail->nim }}</td>
                    <td>{{ $detail->mahasiswa->nama ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Belum ada mahasiswa yang terdaftar di kelas ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>