<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keluar - {{ $suratKeluar->jenis_surat }} - {{ $suratKeluar->no_agenda }}/{{ $suratKeluar->tahun }}</title>
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
        DETAIL SURAT KELUAR ({{ $suratKeluar->jenis_surat }})
    </h3>

    <table class="detail-table">
        <tr>
            <td class="label">Nomor Agenda</td>
            <td>{{ $suratKeluar->no_agenda }}/{{ $suratKeluar->tahun }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Surat</td>
            <td>
                <span style="background-color: #cce5ff; color: #004085; padding: 2px 8px; border-radius: 4px; font-weight: bold;">
                    {{ $suratKeluar->jenis_surat }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="label">Nomor Surat</td>
            <td>{{ $suratKeluar->no_surat }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Surat</td>
            <td>{{ $suratKeluar->tanggal_surat ? \Carbon\Carbon::parse($suratKeluar->tanggal_surat)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Keluar</td>
            <td>{{ $suratKeluar->tanggal_terima ? \Carbon\Carbon::parse($suratKeluar->tanggal_terima)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tujuan Surat</td>
            <td>{{ $suratKeluar->tujuan }}</td>
        </tr>
        <tr>
            <td class="label">Perihal / Uraian</td>
            <td>{{ $suratKeluar->perihal }}</td>
        </tr>
        <tr>
            <td class="label">Isi Ringkas</td>
            <td>{{ $suratKeluar->isi_ringkas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Klasifikasi</td>
            <td>
                @if($suratKeluar->klasifikasi)
                    {{ $suratKeluar->klasifikasi->kode }} - {{ $suratKeluar->klasifikasi->nama }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Bidang</td>
            <td>
                @if($suratKeluar->bidang)
                    {{ $suratKeluar->bidang->kode_bidang }} - {{ $suratKeluar->bidang->nama_bidang }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Penandatangan</td>
            <td>{{ $suratKeluar->penandatangan }}</td>
        </tr>
        <tr>
            <td class="label">Indeks Berkas</td>
            <td>{{ $suratKeluar->indeks_berkas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tingkat Keamanan</td>
            <td>
                @if($suratKeluar->tingkat_keamanan)
                    @if($suratKeluar->tingkat_keamanan == 'Rahasia')
                        <span style="background-color: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px; font-size: 8pt; font-weight: bold;">{{ $suratKeluar->tingkat_keamanan }}</span>
                    @elseif($suratKeluar->tingkat_keamanan == 'Sangat Rahasia')
                        <span style="background-color: #f8d7da; color: #721c24; padding: 2px 8px; border-radius: 4px; font-size: 8pt; font-weight: bold;">{{ $suratKeluar->tingkat_keamanan }}</span>
                    @elseif($suratKeluar->tingkat_keamanan == 'Terbatas')
                        <span style="background-color: #d1ecf1; color: #0c5460; padding: 2px 8px; border-radius: 4px; font-size: 8pt; font-weight: bold;">{{ $suratKeluar->tingkat_keamanan }}</span>
                    @else
                        <span style="background-color: #d4edda; color: #155724; padding: 2px 8px; border-radius: 4px; font-size: 8pt; font-weight: bold;">{{ $suratKeluar->tingkat_keamanan }}</span>
                    @endif
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Masa Simpan</td>
            <td>{{ $suratKeluar->masa_simpan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Keterangan</td>
            <td>{{ $suratKeluar->keterangan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>
                @if($suratKeluar->status == 'selesai')
                    <span style="background-color: #d4edda; color: #155724; padding: 2px 8px; border-radius: 4px; font-size: 8pt; font-weight: bold;">Selesai</span>
                @elseif($suratKeluar->status == 'dikirim')
                    <span style="background-color: #cce5ff; color: #004085; padding: 2px 8px; border-radius: 4px; font-size: 8pt; font-weight: bold;">Dikirim</span>
                @else
                    <span style="background-color: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px; font-size: 8pt; font-weight: bold;">Draft</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Dibuat Oleh</td>
            <td>{{ $suratKeluar->dibuatOleh->name ?? '-' }} ({{ $suratKeluar->dibuatOleh->email ?? '-' }})</td>
        </tr>
        <tr>
            <td class="label">Dibuat Pada</td>
            <td>{{ $suratKeluar->created_at ? \Carbon\Carbon::parse($suratKeluar->created_at)->translatedFormat('d F Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Terakhir Diupdate</td>
            <td>{{ $suratKeluar->updated_at ? \Carbon\Carbon::parse($suratKeluar->updated_at)->translatedFormat('d F Y H:i') : '-' }}</td>
        </tr>
    </table>

    {{-- Lampiran --}}
    @if(!empty($suratKeluar->file_surat))
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
            @foreach($suratKeluar->file_surat as $index => $path)
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
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('surat-keluar.show', $suratKeluar->id)) }}" alt="QR Code Verifikasi">
        <div class="verification">
            Scan QR Code untuk verifikasi keaslian surat<br>
            URL: {{ route('surat-keluar.show', $suratKeluar->id) }}
        </div>
    </div>
</body>
</html>