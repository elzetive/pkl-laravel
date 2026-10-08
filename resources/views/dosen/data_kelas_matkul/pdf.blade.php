<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Kelas Mata Kuliah</title>
    @include('layouts.include.pdf')
</head>
<body>
    <table class="header-table" style="position: relative; width: 100%;">
        <tr>
            <td style="width: 15%; text-align: right; vertical-align: middle; padding-right: 10px;">
                <img src="{{ public_path('storage/dosen/foto-dosen-1790320295.jpg') }}" class="logo" style="display: inline-block;" alt="Logo">            
            </td>
            <td style="width: 70%;" class="header-text">
                <h2>Jurusan Komputer dan Informatika</h2>
                <h3>Teknik Informatika</h3>
                <p>
                    Alamat: Jl. Dr. Soetomo No. 1, Karangcengis, Sidakaya, <br>
                    Kec. Cilacap Selatan, Kab. Cilacap, Jawa Tengah 53212
                </p>
            </td>
            <td style="width:15%"></td>
        </tr>
    </table>

    <div class="line-double"></div>
    <div class="document-title" style="text-align: center; margin: 15px 0; font-weight: bold;">DATA KELAS MATA KULIAH</div>

    <table class="content-table" border="1" cellspacing="0" cellpadding="5" style="width: 100%; table-layout: fixed; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 12%">Nama Kelas</th>
                <th style="width: 18%">Akademik</th>
                <th style="width: 20%">Matkul</th>
                <th style="width: 20%">Jurusan</th>
                <th style="width: 25%">Dosen</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data_kelas as $index => $item)
                @php
                    $semester = $item->akademik->semester ?? '';
                    $is_ganjil = in_array(strtoupper($semester), ['GL', '1', 'GANJIL']);
                @endphp
                <tr>
                    <td class="text-center" style="text-align: center;">{{ $index + 1 }}</td>
                    <td class="text-center" style="text-align: center;">{{ $item->nama_kelas }}</td>
                    <td class="text-center" style="text-align: center;">
                        @if ($item->akademik)
                            {{ $item->akademik->tahun }} - {{ $is_ganjil ? 'Ganjil' : 'Genap' }}
                        @else
                            {{ $item->kode_akademik }} 
                        @endif    
                    </td> 

                    <td>{{ $item->matkul->nama_matkul ?? $item->kode_matkul }}</td>
                    <td>{{ $item->jurusan->nama_jurusan ?? $item->kode_jurusan }}</td>
                    <td>{{ $item->dosen->nama ?? $item->nik }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center">Data kelas tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>