<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Laporan Surat Masuk' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; word-wrap: break-word; vertical-align: middle; }
        th { background-color: #f3f4f6; }
        .text-left-indent { text-align: left !important; padding-left: 15px !important; }
        .header { margin-bottom: 20px; text-align: center; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0 0; color: #666; }
    </style>
</head>
<body>
    @if(($showHeader ?? true))
    <div class="header">
        <h2>{{ $title ?? 'Laporan Data Surat Masuk' }}</h2>
        <p>Dinas Perhubungan Provinsi Lampung</p>
        <p>Tanggal Cetak: {{ now()->format('d F Y H:i') }}</p>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">Nomor Urut</th>
                <th width="10%">Tanggal</th>
                <th width="8%">Kode</th>
                <th width="12%">Tujuan</th>
                <th width="15%">Nomor Surat</th>
                <th width="10%">Tanggal Surat</th>
                <th width="20%">Uraian</th>
                <th width="10%">Disposisi</th>
                <th width="10%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratMasuks as $index => $surat)
            <tr>
                <td>{{ $surat->no_agenda }}</td>
                <td>{{ $surat->tanggal_terima ? \Carbon\Carbon::parse($surat->tanggal_terima)->translatedFormat('d F Y') : '-' }}</td>
                <td>{{ $surat->klasifikasi ? $surat->klasifikasi->kode : '-' }}</td>
                <td>{{ $surat->pengirim ?? '-' }}</td>
                <td>{{ $surat->no_surat ?: '-' }}</td>
                <td>{{ $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') : '-' }}</td>
                <td class="text-left-indent">{{ $surat->perihal ?? '-' }}</td>
                <td>{{ $surat->bidangTujuans->pluck('nama_bidang')->implode(', ') ?: '-' }}</td>
                <td>{{ $surat->keterangan ?: '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
