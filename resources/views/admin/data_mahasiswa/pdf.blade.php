<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Mahasiswa</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            margin: 20px 30px;
            color: #000;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
            margin-bottom: 5px;
        }
        .header-table td {
            border: none !important;
            padding: 0;
            vertical-align: middle;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .header-text {
            text-align: center;
        }
        .header-text h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }
        .header-text h3 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
            line-height: 1.3;
        }
        .line-double {
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-bottom: 20px;
        }

        .document-title {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-table th,
        .content-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 9.5pt;
        }
        .content-table th {
            font-weight: bold;
            text-align: center;
            background-color: #ffffff;
        }
        .text-center { text-align: center;}
        .text-left {text-align: left;}
    </style>
</head>
<body>
    <table class="header-table" style="position: relative">
        <tr>
            <td style="width: 15%; text-align: right; vertical-align: middle; padding-right: 10px;">
                <img src="{{ public_path('storage/dosen/foto-dosen-1790320295.jpg') }}" class="logo" style="display: inline-block;" alt="Logo">
            </td>
            <td style="width: 70%" class="header-text">
                <h2>Jurusan Komputer dan Bisnis</h2>
                <h3>Teknik Informatika</h3>
                <p>
                    Alamat : Jl. Dr. Soetomo No.1, Karangcengis, Sidakaya, <br>
                    Kec. Cilacap Selatan, Kab. Cilacap, Jawa Tengah 53212
                </p>
            </td>
            <td style="width: 15%"></td>
        </tr>
    </table>
    <div class="line-double"></div>
    <div class="document-title">DATA MAHASISWA</div>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 18%">NIM</th>
                <th>Nama Mahasiswa</th>
                <th style="width: 18%">Kontak</th>
                <th style="width: 25%">Email</th>
                <th style="width: 15%">Kelamin</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data_mahasiswa as $index => $item )
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->nim }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="text-center">{{ $item->kontak }}</td>
                    <td>{{ $item->email }}</td>
                    <td class="text-center">{{ $item->kelamin }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
