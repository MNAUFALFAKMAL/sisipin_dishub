<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 14mm 11mm 16mm 11mm; }
        body { font-family: "DejaVu Sans", sans-serif; font-size: 9px; margin: 0; padding: 0; color: #111; }
        .page { page-break-after: always; position: relative; }
        .page:last-child { page-break-after: avoid; }

        .kop { width: 100%; border-collapse: collapse; }
        .kop td { vertical-align: middle; border: none; padding: 0; }
        .kop-logo { width: 58px; height: 58px; }
        .kop-text { text-align: center; }
        .kop-pemprov { font-size: 13px; font-weight: bold; letter-spacing: 0.4px; text-transform: uppercase; }
        .kop-dinas { font-size: 17px; font-weight: bold; text-transform: uppercase; margin-top: 1px; }
        .kop-alamat { font-size: 9px; margin-top: 2px; }
        .kop-garis { border-top: 2px solid #111; border-bottom: 1px solid #111; height: 2px; margin: 6px 0 12px 0; }

        .judul { text-align: center; font-size: 13px; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .subjudul { text-align: center; font-size: 10px; margin-bottom: 2px; }
        .meta { text-align: center; font-size: 8px; color: #555; margin-bottom: 10px; }

        .category-title { font-size: 11px; font-weight: bold; color: #1e3a8a; border-bottom: 2px solid #1e3a8a; padding-bottom: 4px; margin-bottom: 8px; text-transform: uppercase; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #333; padding: 4px; text-align: center; vertical-align: middle; word-wrap: break-word; }
        table.data th { background-color: #e5e7eb; font-weight: bold; font-size: 8px; }
        .text-left-indent { text-align: left !important; padding-left: 12px !important; }

        .page-footer { margin-top: 10px; font-size: 8px; color: #555; }
        .page-footer .kanan { float: right; }
        .page-footer .kiri { float: left; }

        .ttd { float: right; width: 280px; margin-top: 14px; text-align: center; font-size: 10px; page-break-inside: avoid; }
        .ttd-kota { margin-bottom: 2px; }
        .ttd-space { height: 48px; }
        .ttd-nama { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    @php
        $logoSrc = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('images/logo-dishub.png')));
        $signer = $penandatangan ?? ['nama' => '................................', 'nip' => '................................'];
    @endphp

    @foreach($data as $label => $cat)
    <div class="page">
        <table class="kop">
            <tr>
                <td width="70"><img src="{{ $logoSrc }}" alt="Logo Dishub" class="kop-logo"></td>
                <td class="kop-text">
                    <div class="kop-pemprov">Pemerintah Provinsi Lampung</div>
                    <div class="kop-dinas">Dinas Perhubungan</div>
                    <div class="kop-alamat">Jl. Wolter Monginsidi No. 69, Teluk Betung, Bandar Lampung</div>
                </td>
                <td width="70"></td>
            </tr>
        </table>
        <div class="kop-garis"></div>

        <div class="judul">{{ $title }}</div>
        @if(! empty($bidang))
            <div class="subjudul">Bidang: {{ $bidang }}</div>
        @endif
        <div class="meta">Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i') }} WIB</div>

        <div class="category-title">Kategori: {{ $label }}</div>

        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No. Urut</th>
                    <th width="10%">Tanggal Terima</th>
                    <th width="7%">Kode</th>
                    <th width="12%">{{ $cat['type'] === 'masuk' ? 'Pengirim' : 'Tujuan' }}</th>
                    <th width="14%">Nomor Surat</th>
                    <th width="10%">Tanggal Surat</th>
                    <th width="20%">Uraian</th>
                    <th width="12%">Bidang</th>
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
                    <td colspan="9" style="padding: 18px; color: #888;">Tidak ada data untuk kategori ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($loop->last)
            <div class="ttd">
                <div class="ttd-kota">Bandar Lampung, {{ now()->translatedFormat('d F Y') }}</div>
                <div>Kepala Dinas Perhubungan<br>Provinsi Lampung</div>
                <div class="ttd-space"></div>
                <div class="ttd-nama">{{ $signer['nama'] }}</div>
                <div>NIP. {{ $signer['nip'] }}</div>
            </div>
            <div style="clear: both;"></div>
        @endif

        <div class="page-footer">
            <span class="kiri">SISIPIN DISHUB &middot; Dinas Perhubungan Provinsi Lampung</span>
            <span class="kanan">Halaman {{ $loop->iteration }} dari {{ $loop->count }}</span>
        </div>
        <div style="clear: both;"></div>
    </div>
    @endforeach
</body>
</html>
