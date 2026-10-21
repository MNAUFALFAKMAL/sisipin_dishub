<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Kp4Controller extends Controller
{
    public function index()
    {
        return view('kp4.index');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'nama'                  => 'required',
            'nip'                   => 'required',
            'tempat_lahir'          => 'required',
            'jenis_kelamin'         => 'required|in:Pria,Wanita',
            'agama'                 => 'required',
            'kebangsaan'            => 'required',
            'pangkat_golongan'      => 'required',
            'jabatan'               => 'required',
            'instansi'              => 'required',
            'masa_kerja_golongan'   => 'nullable',
            'masa_kerja_tambahan'   => 'nullable',
            'masa_kerja_total'      => 'nullable',
            'gaji_pp'               => 'nullable',
            'gaji_pokok'            => 'nullable',
            'alamat'                => 'required',
            'pekerjaan_sampingan'   => 'nullable',
            'penghasilan_sampingan' => 'nullable',
            'pensiun'               => 'nullable',
            'pasangan_nama'         => 'nullable',
            'pasangan_tgl_lahir'    => 'nullable',
            'pasangan_tgl_kawin'    => 'nullable',
            'pasangan_pekerjaan'    => 'nullable',
            'jumlah_anak'           => 'nullable',
            'anak_daftar_gaji'      => 'nullable|json',
            'anak_luar_daftar_gaji' => 'nullable|json',
        ]);

        $templatePath = resource_path('templates/kp4.docx');
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template KP4 tidak ditemukan.');
        }

        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) mkdir($tempDir, 0755, true);
        $tempPath = $tempDir . '/kp4_' . uniqid() . '.docx';
        copy($templatePath, $tempPath);

        $zip = new \ZipArchive();
        if ($zip->open($tempPath) !== true) {
            return back()->with('error', 'Gagal membuka template.');
        }

        $docXml = $zip->getFromName('word/document.xml');

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($docXml);
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        $validated['tempat']  = 'Bandar Lampung';
        $validated['tanggal'] = $this->todayIndonesian();

        // Safeguard nullable fields
        $fillNull = ['masa_kerja_golongan','masa_kerja_tambahan','masa_kerja_total',
                     'gaji_pp','gaji_pokok','pekerjaan_sampingan','penghasilan_sampingan',
                     'pensiun','pasangan_nama','pasangan_tgl_lahir','pasangan_tgl_kawin',
                     'pasangan_pekerjaan','jumlah_anak','anak_daftar_gaji','anak_luar_daftar_gaji'];
        foreach ($fillNull as $f) $validated[$f] ??= null;

        $anak1Count = collect(json_decode($validated['anak_daftar_gaji'] ?? '[]', true) ?: [])
                      ->reject(fn($c) => empty($c['nama']))->count();
        $anak2Count = collect(json_decode($validated['anak_luar_daftar_gaji'] ?? '[]', true) ?: [])
                      ->reject(fn($c) => empty($c['nama']))->count();

        // ── Hapus surplus baris tabel anak SEBELUM replacement ─────────
        // Identifikasi tabel anak dari sample data yg masih ada di template
        $allTables = $xpath->query('//w:tbl');
        $tableI = null; $tableII = null;
        foreach ($allTables as $tbl) {
            $tblText = '';
            foreach ($xpath->query('.//w:t', $tbl) as $tn) $tblText .= $tn->nodeValue;
            if (str_contains($tblText, 'Nazwa Salsabila Sudarso')) $tableI = $tbl;
            if (str_contains($tblText, 'ANDHIKA VALENTINO SUDARSO')) $tableII = $tbl;
        }

        // Tabel I (daftar gaji): rows 0-1 header/sub-header, data mulai idx 2
        if ($tableI) {
            $rows = $xpath->query('.//w:tr', $tableI);
            $keepI = max(1, $anak1Count);
            for ($i = $rows->length - 1; $i >= 2; $i--) {
                if (($i - 2) >= $keepI) {
                    $rows->item($i)->parentNode->removeChild($rows->item($i));
                }
            }
        }

        // Tabel II (luar daftar gaji): keep min 1 row (isi '-' jika kosong)
        if ($tableII) {
            $rows = $xpath->query('.//w:tr', $tableII);
            $keepII = max(1, $anak2Count);
            for ($i = $rows->length - 1; $i >= 2; $i--) {
                if (($i - 2) >= $keepII) {
                    $rows->item($i)->parentNode->removeChild($rows->item($i));
                }
            }
        }

        // ── Scope shared values tabel II agar tidak kena global replace ──
        // Teks di template terpecah lintas run, jadi combine paragraph-level
        // dulu baru replace placeholder unik
        if ($tableII) {
            $scopeMap = ['AK' => 'AK_II', 'Yussudarso, S.IP' => 'AYAH_II', 'Susiyati' => 'IBU_II'];
            $t2paras = $xpath->query('.//w:p', $tableII);
            foreach ($t2paras as $para) {
                $t2runs = $xpath->query('.//w:r', $para);
                if ($t2runs->length === 0) continue;
                $combined = '';
                foreach ($t2runs as $run) {
                    foreach ($xpath->query('.//w:t', $run) as $tn) {
                        $combined .= $tn->nodeValue;
                    }
                }
                $newCombined = str_replace(array_keys($scopeMap), array_values($scopeMap), $combined);
                if ($newCombined === $combined) continue;
                $first = true;
                foreach ($t2runs as $run) {
                    foreach ($xpath->query('.//w:t', $run) as $tn) {
                        if ($first) { $tn->nodeValue = $newCombined; $first = false; }
                        else { $tn->nodeValue = ''; }
                    }
                }
            }
        }

        $rules = $this->buildRules($validated);

        // Per-run replacement — setiap <w:r> diproses sendiri agar
        // <w:rPr> (bold, font, size) tetap utuh. Untuk teks yang
        // terpecah di beberapa run, fallback ke paragraph-level.
        $paras = $xpath->query('//w:p');
        foreach ($paras as $para) {
            $runs = $xpath->query('.//w:r', $para);
            if ($runs->length === 0) continue;

            foreach ($runs as $run) {
                $tNodes = $xpath->query('.//w:t', $run);
                if ($tNodes->length === 0) continue;

                $runText = '';
                foreach ($tNodes as $tn) {
                    $runText .= $tn->nodeValue;
                }

                $newRunText = $runText;
                $runChanged = false;
                foreach ($rules as $search => $replace) {
                    if (str_contains($newRunText, $search)) {
                        $newRunText = str_replace($search, $replace, $newRunText);
                        $runChanged = true;
                    }
                }

                if ($runChanged) {
                    $firstT = true;
                    foreach ($tNodes as $tn) {
                        if ($firstT) {
                            $tn->nodeValue = $newRunText;
                            $firstT = false;
                        } else {
                            $tn->nodeValue = '';
                        }
                    }
                }
            }

            // Cross-run handling: untuk rule yang match lintas run
            // (terpecah di beberapa <w:r>), ganti hanya di overlapping runs
            // pakai consumed tracking. Non-overlapping runs tetap utuh
            // (original text + formatting seperti bold).
            // Di-run selalu (tidak hanya saat !anyRunMatched) karena bisa
            // ada per-run + cross-run match dalam paragraf yang sama.
            $runTexts = []; $runStarts = []; $pos = 0;
            foreach ($runs as $run) {
                $rt = '';
                foreach ($xpath->query('.//w:t', $run) as $tn) $rt .= $tn->nodeValue;
                $runTexts[] = $rt;
                $runStarts[] = $pos;
                $pos += strlen($rt);
            }
            $combined = implode('', $runTexts);

            foreach ($rules as $search => $replace) {
                $mp = strpos($combined, $search);
                if ($mp === false) continue;

                // Cari overlapping runs
                $me = $mp + strlen($search);
                $overlapIdxs = [];
                foreach ($runTexts as $i => $rt) {
                    $rs = $runStarts[$i];
                    $re = $rs + strlen($rt);
                    if ($re > $mp && $rs < $me) $overlapIdxs[] = $i;
                }
                if (empty($overlapIdxs)) continue;

                // Skip jika sudah di-handle per-run
                $alreadyRun = false;
                foreach ($overlapIdxs as $i) {
                    if (str_contains($runTexts[$i], $search)) {
                        $alreadyRun = true; break;
                    }
                }
                if ($alreadyRun) continue;

                // Build combined overlapping runs & replace
                $oc = '';
                foreach ($overlapIdxs as $i) $oc .= $runTexts[$i];
                $newOc = str_replace($search, $replace, $oc);

                // Distribusi consumed tracking
                $consumed = 0;
                foreach ($overlapIdxs as $i) {
                    $origLen = strlen($runTexts[$i]);
                    $remain = strlen($newOc) - $consumed;
                    $segLen = min($origLen, $remain);
                    $segment = $segLen > 0 ? substr($newOc, $consumed, $segLen) : '';
                    $tNodes = $xpath->query('.//w:t', $runs->item($i));
                    foreach ($tNodes as $tn) $tn->nodeValue = $segment;
                    $runTexts[$i] = $segment;
                    $consumed += $segLen;
                }

                // Handle kelebihan teks — append ke run overlap terakhir
                if ($consumed < strlen($newOc)) {
                    $li = end($overlapIdxs);
                    $extra = substr($newOc, $consumed);
                    $tNodes = $xpath->query('.//w:t', $runs->item($li));
                    if ($tNodes->length > 0) {
                        $tNodes->item(0)->nodeValue .= $extra;
                        $runTexts[$li] .= $extra;
                    }
                }

                // Update runStarts & combined untuk rule selanjutnya
                $pos = 0;
                foreach ($runTexts as $i => $rt) {
                    $runStarts[$i] = $pos;
                    $pos += strlen($rt);
                }
                $combined = implode('', $runTexts);
            }
        }

        $zip->addFromString('word/document.xml', $dom->saveXML());
        $zip->close();

        $safeName = preg_replace('/[^A-Za-z0-9\s\-]/', '', $validated['nama']);
        return response()->download($tempPath, "KP4_{$safeName}.docx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    private function buildRules(array $d): array
    {
        $anak1 = collect(json_decode($d['anak_daftar_gaji'] ?? '[]', true) ?: [])
                 ->reject(fn($c) => empty($c['nama']))->values();
        $anak2 = collect(json_decode($d['anak_luar_daftar_gaji'] ?? '[]', true) ?: [])
                 ->reject(fn($c) => empty($c['nama']))->values();

        $angka = ['Nol','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh'];
        $today = $d['tanggal'];
        $istri = $d['jenis_kelamin'] === 'Wanita' ? 'Suami' : 'Istri';

        $r = [
            // ── Data Diri ───────────────────────────────────────────────────
            'YUSSUDARSO, S.IP'                     => $d['nama'],
            'NIP.197408282007011012'                => 'NIP.' . str_replace(' ', '', $d['nip']),
            '19740828 200701 1 012'                 => $d['nip'],
            'Kenali, 28 Agustus 1974'               => $d['tempat_lahir'],
            'Pria'                                  => $d['jenis_kelamin'],
            'Islam'                                 => $d['agama'],
            'Indonesia'                             => $d['kebangsaan'],
            'Pembina Muda TK.I / III.b'             => $d['pangkat_golongan'],
            'Pengatur Lalu- lintas Jalan'           => $d['jabatan'],
            'Dinas Perhubungan Provinsi Lampung'    => $d['instansi'],
            'Dusun Srimulyo RT.9 RW.003 Desa Negeri Sakti Kec. Gedong Tataan Kab. Pesawaran. Lampung' => $d['alamat'],

            // ── Masa Kerja ──────────────────────────────────────────────────
            '22 Tahun Masa kerja tambahan : 0 Th 9 bln,'
                => ($d['masa_kerja_golongan'] ?: '22 Tahun') . ', Masa kerja tambahan : ' . ($d['masa_kerja_tambahan'] ?: '0 Th 9 bln') . ',',
            'Masa kerja keseluruhan: 22 Tahun 9 Bulan'
                => 'Masa kerja keseluruhan: ' . ($d['masa_kerja_total'] ?: '22 Tahun 9 Bulan'),

            // ── Gaji ────────────────────────────────────────────────────────
            'PP 5 Tahun 2024 dengan gaji pokok Rp. 4.083.900'
                => ($d['gaji_pp'] ?: 'PP 5 Tahun 2024') . ' dengan gaji pokok Rp. ' . ($d['gaji_pokok'] ?: '4.083.900'),

            // ── Pekerjaan sampingan ─────────────────────────────────────────
            'disamping jabatan utama tersebut, bekerja sebagai: -'
                => 'disamping jabatan utama tersebut, bekerja sebagai: ' . ($d['pekerjaan_sampingan'] ?: '-'),
            'dengan mendapat penghasilan sebesar Rp. 0 Sebulan.'
                => 'dengan mendapat penghasilan sebesar Rp. ' . ($d['penghasilan_sampingan'] ?: '-') . ' Sebulan.',
            'Mempunyai pensiun (pensiun janda / duda) Rp. 0 Sebulan.'
                => 'Mempunyai pensiun (pensiun janda / duda) Rp. ' . ($d['pensiun'] ?: '-') . ' Sebulan.',

            // ── Pasangan ────────────────────────────────────────────────────
            'SUSIYATI'                              => $d['pasangan_nama'] ?: '-',
            '23-08-1978'                            => $d['pasangan_tgl_lahir'] ?: '-',
            '16-07-2005'                            => $d['pasangan_tgl_kawin'] ?: '-',
            'IRT'                                   => $d['pasangan_pekerjaan'] ?: '-',

            // ── Istri/Suami ─────────────────────────────────────────────────
            'Istri/Suami tanggungan'                => $istri,
            'Istri'                                 => $istri,

            // ── Jumlah anak ─────────────────────────────────────────────────
            '3 (Tiga)'                              => $this->jumlahAnak($d['jumlah_anak'] ?? '3', $angka),

            // ── Tempat & tanggal ────────────────────────────────────────────
            'Bandar Lampung, 31 Desember 2025'      => 'Bandar Lampung, ' . $today,
        ];

        // ── Anak dalam daftar gaji ─────────────────────────────────────────
        $fields = ['nama','status','tgl_lahir','kawin','sekolah','beasiswa','ayah','ibu','meninggal','keterangan'];
        $skipVals = ['-', 'Yussudarso, S.IP', 'Susiyati', 'AK'];

        $sample1 = [
            ['Nazwa Salsabila Sudarso', 'AK', '16-01-2010', '-', 'SMA', '-', 'Yussudarso, S.IP', 'Susiyati', '-', '-'],
            ['Alisha Humaira Sudarso',  'AK', '14-03-2019', '-', 'SD',  '-', 'Yussudarso, S.IP', 'Susiyati', '-', '-'],
        ];
        // Selalu proses semua sample — jika user punya <2 anak, sisanya dikosongkan
        foreach ($sample1 as $ci => $child) {
            $c = $anak1[$ci] ?? [];
            foreach ($child as $fi => $sampleVal) {
                if (in_array($sampleVal, $skipVals, true)) continue;
                $r[$sampleVal] = isset($anak1[$ci]) ? ($c[$fields[$fi]] ?: '-') : '-';
            }
        }
        if (isset($anak1[0])) {
            $c0 = $anak1[0];
            $r['Yussudarso, S.IP'] = $c0['ayah'] ?: '-';
            $r['Susiyati'] = $c0['ibu'] ?: '-';
            $r['AK'] = $c0['status'] ?: '-';
        } else {
            $r['Yussudarso, S.IP'] = '-';
            $r['Susiyati'] = '-';
            $r['AK'] = '-';
        }

        $sample2 = [['nama'=>'ANDHIKA VALENTINO SUDARSO','status'=>'AK','tgl_lahir'=>'14-02-2006','kawin'=>'-','sekolah'=>'-','beasiswa'=>'-','ayah'=>'Yussudarso, S.IP','ibu'=>'Susiyati','meninggal'=>'-','keterangan'=>'-']];
        foreach ($sample2 as $ci => $child) {
            $c = $anak2[$ci] ?? [];
            $hasData = isset($anak2[$ci]) && !empty($c['nama']);
            foreach ($child as $fi => $sampleVal) {
                if (in_array($sampleVal, $skipVals, true)) continue;
                $fname = $fields[$fi] ?? $fi;
                $r[$sampleVal] = $hasData ? ($c[$fname] ?: '-') : '-';
            }
        }

        // Scoped shared values untuk tabel II (placeholder unik, bukan global)
        $c2 = $anak2[0] ?? [];
        $hasData2 = isset($anak2[0]) && !empty($c2['nama']);
        $r['AK_II'] = $hasData2 ? ($c2['status'] ?: '-') : '-';
        $r['AYAH_II'] = $hasData2 ? ($c2['ayah'] ?: '-') : '-';
        $r['IBU_II'] = $hasData2 ? ($c2['ibu'] ?: '-') : '-';

        // Longest first
        $keys = array_keys($r);
        usort($keys, fn($a, $b) => strlen($b) - strlen($a));
        $sorted = [];
        foreach ($keys as $k) $sorted[$k] = $r[$k];
        return $sorted;
    }

    private function jumlahAnak(string $val, array $angka): string
    {
        $num = (int)$val;
        $word = isset($angka[$num]) ? $angka[$num] : $val;
        return $val . ' (' . $word . ')';
    }

    private function todayIndonesian(): string
    {
        $bulan = ['Januari','Februari','Maret','April','Mei','Juni',
                  'Juli','Agustus','September','Oktober','November','Desember'];
        return date('j') . ' ' . $bulan[(int)date('n') - 1] . ' ' . date('Y');
    }
}
