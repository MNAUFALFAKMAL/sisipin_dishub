<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Masuk - {{ $suratMasuk->no_agenda }}/{{ $suratMasuk->tahun }}</title>
    <style>
        @page {
            margin: 2cm 2cm 2cm 2cm;
            size: A4;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1B4F72;
            padding-bottom: 15px;
        }
        .header img {
            max-height: 80px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 10px 0 5px;
            color: #1B4F72;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 14pt;
            font-weight: normal;
            margin: 5px 0;
            color: #333;
        }
        .header p {
            font-size: 10pt;
            color: #666;
            margin: 2px 0;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .detail-table td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .detail-table .label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 30%;
        }
        .detail-table .value {
            width: 70%;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #1B4F72;
            margin: 20px 0 10px;
            border-bottom: 2px solid #1B4F72;
            padding-bottom: 5px;
        }
        .disposisi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        .disposisi-table th, .disposisi-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .disposisi-table th {
            background-color: #1B4F72;
            color: white;
        }
        .disposisi-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .files-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        .files-table th, .files-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .files-table th {
            background-color: #1B4F72;
            color: white;
        }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
            color: #666;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        .qr-code img {
            width: 80px;
            height: 80px;
        }
        .verification {
            font-size: 9pt;
            color: #666;
            text-align: center;
            margin-top: 5px;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .badge-info { background-color: #d1ecf1; color: #0c5460; }
        .badge-primary { background-color: #cce5ff; color: #004085; }
    </style>
</head>
<body>
    <div class="header">
        @if(file_exists(public_path('images/logo-dishub.png')))
            <img src="{{ public_path('images/logo-dishub.png') }}" alt="Logo Dinas Perhubungan">
        @endif
        <h1>PEMERINTAH PROVINSI LAMPUNG</h1>
        <h2>DINAS PERHUBUNGAN</h2>
        <p>Jl. Gatot Subroto No. 1, Bandar Lampung | Telp. (0721) 481123</p>
        <p>Website: dishub.lampungprov.go.id | Email: dishub@lampungprov.go.id</p>
    </div>

    <h3 style="text-align: center; font-size: 14pt; font-weight: bold; margin-bottom: 20px; text-decoration: underline;">
        DETAIL SURAT MASUK
    </h3>

    <table class="detail-table">
        <tr>
            <td class="label">Nomor Agenda</td>
            <td>{{ $suratMasuk->no_agenda }}/{{ $suratMasuk->tahun }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Surat</td>
            <td>{{ $suratMasuk->jenis_surat }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Surat</td>
            <td>{{ $suratMasuk->no_surat }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Surat</td>
            <td>{{ $suratMasuk->tanggal_surat ? \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Terima</td>
            <td>{{ $suratMasuk->tanggal_terima ? \Carbon\Carbon::parse($suratMasuk->tanggal_terima)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Pengirim</td>
            <td>{{ $suratMasuk->pengirim }}</td>
        </tr>
        <tr>
            <td class="label">Perihal</td>
            <td>{{ $suratMasuk->perihal }}</td>
        </tr>
        <tr>
            <td class="label">Isi Ringkas</td>
            <td>{{ $suratMasuk->isi_ringkas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Klasifikasi</td>
            <td>
                @if($suratMasuk->klasifikasi)
                    {{ $suratMasuk->klasifikasi->kode }} - {{ $suratMasuk->klasifikasi->nama }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Bidang Tujuan</td>
            <td>
                @if($suratMasuk->bidangTujuan)
                    {{ $suratMasuk->bidangTujuan->kode_bidang }} - {{ $suratMasuk->bidangTujuan->nama_bidang }}
                @elseif($suratMasuk->bidangTujuans->count() > 0)
                    @foreach($suratMasuk->bidangTujuans as $bidang)
                        {{ $bidang->kode_bidang }} - {{ $bidang->nama_bidang }}<br>
                    @endforeach
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Disposisi</td>
            <td>
                @if($suratMasuk->bidangTujuans->count() > 0)
                    @foreach($suratMasuk->bidangTujuans as $bidang)
                        {{ $bidang->nama_bidang }}<br>
                    @endforeach
                @else
                    Belum didisposisikan
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Status Disposisi</td>
            <td>
                @if($suratMasuk->status_disposisi == 'selesai')
                    <span class="badge badge-success">Selesai</span>
                @elseif($suratMasuk->status_disposisi == 'diproses')
                    <span class="badge badge-warning">Diproses</span>
                @else
                    <span class="badge badge-danger">Belum</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Keterangan</td>
            <td>{{ $suratMasuk->keterangan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Diinput Oleh</td>
            <td>{{ $suratMasuk->diinputOleh->name ?? '-' }} ({{ $suratMasuk->diinputOleh->email ?? '-' }})</td>
        </tr>
        <tr>
            <td class="label">Dibuat Pada</td>
            <td>{{ $suratMasuk->created_at ? \Carbon\Carbon::parse($suratMasuk->created_at)->translatedFormat('d F Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Terakhir Diupdate</td>
            <td>{{ $suratMasuk->updated_at ? \Carbon\Carbon::parse($suratMasuk->updated_at)->translatedFormat('d F Y H:i') : '-' }}</td>
        </tr>
    </table>

    {{-- Disposisi History --}}
    @if($suratMasuk->disposisis->count() > 0)
    <div class="section-title">Riwayat Disposisi</div>
    <table class="disposisi-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">Dari</th>
                <th style="width: 20%">Kepada</th>
                <th style="width: 25%">Instruksi</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratMasuk->disposisis as $index => $disposisi)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $disposisi->created_at ? \Carbon\Carbon::parse($disposisi->created_at)->translatedFormat('d F Y H:i') : '-' }}</td>
                <td>{{ $disposisi->dariUser->name ?? '-' }}</td>
                <td>
                    {{ $disposisi->kepadaUser->name ?? '-' }}<br>
                    @if($disposisi->kepadaBidang)
                        <small>({{ $disposisi->kepadaBidang->nama_bidang }})</small>
                    @endif
                </td>
                <td>{{ $disposisi->instruksi }}</td>
                <td>
                    @if($disposisi->status == 'selesai')
                        <span class="badge badge-success">Selesai</span>
                    @elseif($disposisi->status == 'diproses')
                        <span class="badge badge-warning">Diproses</span>
                    @else
                        <span class="badge badge-danger">Menunggu</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Lampiran --}}
    @if(!empty($suratMasuk->file_surat))
    <div class="section-title">Lampiran Surat</div>
    <table class="files-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 40%">Nama File</th>
                <th style="width: 15%">Tipe</th>
                <th style="width: 20%">Ukuran</th>
                <th style="width: 20%">Path</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratMasuk->file_surat as $index => $path)
            @php
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $fullPath = storage_path('app/public/' . $path);
                $size = file_exists($fullPath) ? filesize($fullPath) : 0;
                $sizeFormatted = $size < 1024 ? $size . ' B' : ($size < 1048576 ? round($size/1024, 1) . ' KB' : round($size/1048576, 1) . ' MB');
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ basename($path) }}</td>
                <td>{{ strtoupper($ext) }}</td>
                <td>{{ $sizeFormatted }}</td>
                <td style="font-size: 8pt;">{{ $path }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <div>
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}<br>
            Oleh: {{ Auth::user()->name ?? 'Sistem' }}
        </div>
        <div>
            Halaman {{ $page }} dari {{ $totalPages }}
        </div>
    </div>

    {{-- QR Code untuk Verifikasi --}}
    <div class="qr-code">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('surat-masuk.show', $suratMasuk->id)) }}" alt="QR Code Verifikasi">
        <div class="verification">
            Scan QR Code untuk verifikasi keaslian surat<br>
            URL: {{ route('surat-masuk.show', $suratMasuk->id) }}
        </div>
    </div>
</body>
</html>