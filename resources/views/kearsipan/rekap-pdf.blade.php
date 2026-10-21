<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; margin: 0; padding: 0; }
        .page { page-break-after: always; }
        .page:last-child { page-break-after: avoid; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; color: #1e3a8a; }
        .header p { margin: 4px 0 0 0; color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: center; vertical-align: middle; word-wrap: break-word; }
        th { background-color: #f3f4f6; font-weight: bold; font-size: 9px; }
        .text-left-indent { text-align: left !important; padding-left: 15px !important; }
        .category-title { font-size: 13px; font-weight: bold; color: #1e3a8a; border-bottom: 2px solid #1e3a8a; padding-bottom: 5px; margin-bottom: 10px; text-transform: uppercase; }
    </style>
</head>
<body>
    @foreach($data as $label => $cat)
    <div class="page">
        <div class="header">
            <h2>{{ $title }}</h2>
            <p>Dinas Perhubungan Provinsi Lampung · Periode: {{ $periodeStr }}</p>
            <p style="font-size: 9px; color: #999;">Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i') }} WIB</p>
        </div>

        <div class="category-title">Kategori: {{ $label }}</div>

        <table>
            <thead>
                <tr>
                    <th width="5%">No. Urut</th>
                    <th width="11%">Tanggal</th>
                    <th width="7%">Kode</th>
                    <th width="13%">{{ $cat['type'] === 'masuk' ? 'Pengirim' : 'Tujuan' }}</th>
                    <th width="15%">Nomor Surat</th>
                    <th width="10%">Tanggal Surat</th>
                    <th width="20%">Uraian</th>
                    <th width="9%">Disposisi</th>
                    <th width="10%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cat['items'] as $surat)
                <tr>
                    <td>{{ $surat->no_agenda }}</td>
                    <td>
                        {{ $surat->tanggal_terima ? \Carbon\Carbon::parse($surat->tanggal_terima)->translatedFormat('d M Y') : '-' }}
                    </td>
                    <td>{{ $surat->klasifikasi ? $surat->klasifikasi->kode : '-' }}</td>
                    <td>{{ ($cat['type'] === 'masuk' ? $surat->pengirim : $surat->tujuan) ?? '-' }}</td>
                    <td>{{ $surat->no_surat ?: '-' }}</td>
                    <td>
                        {{ $surat->tanggal_surat
                            ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d M Y')
                            : ($surat->tanggal_terima
                                ? \Carbon\Carbon::parse($surat->tanggal_terima)->translatedFormat('d M Y')
                                : '-') }}
                    </td>
                    <td class="text-left-indent">{{ $surat->perihal ?? '-' }}</td>
                    <td>
                        @if($cat['type'] === 'masuk')
                            {{ $surat->bidangTujuans->pluck('nama_bidang')->implode(', ') ?: '-' }}
                        @else
                            {{ $surat->bidangs->pluck('nama_bidang')->implode(', ') ?: '-' }}
                        @endif
                    </td>
                    <td>{{ $surat->keterangan ?: '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="padding: 20px; color: #888;">Tidak ada data untuk kategori ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach
</body>
</html>
