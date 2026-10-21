# Panduan Kontribusi

Panduan lengkap ada di [`docs/git-flow.md`](docs/git-flow.md). Rencana yang sedang dibahas
tim ada di [`docs/rencana-git-flow.md`](docs/rencana-git-flow.md); peta 75 tugas kerja ada di
[`docs/peta-paket-cabang.md`](docs/peta-paket-cabang.md); strategi branching di
[`docs/git-branching.md`](docs/git-branching.md). Halaman ini hanya ringkasannya.

## Tiga aturan yang tidak boleh dilanggar

1. **Jangan pernah push langsung ke `main` atau `develop`.** Keduanya dilindungi; perubahan masuk lewat Pull Request.
2. **Satu paket kerja = satu cabang = satu PR.** Nama cabang memakai kode WBS, misalnya `feature/3.1-surat-masuk`.
3. **Jangan commit rahasia.** `.env`, kunci, kata sandi, dan berkas `akun.md` sudah diabaikan; jangan dipaksa masuk.

## Alur singkat

```bash
git checkout develop
git pull --ff-only
git checkout -b feature/3.4.1-surat-masuk-crud-bidang-tujuan-klasifikasi

# ...kerjakan, commit kecil dan sering...
git add -p
git commit -m "feat(3.4.1): catat surat masuk dan unggah lampiran" -m "Refs: WBS 3.4.1, R-04"

git push -u origin HEAD
gh pr create --base develop --fill
```

Setelah PR disetujui dan digabung (squash), hapus cabangnya.

## Format commit

```
<tipe>(<kode-wbs>): <ringkasan kalimat perintah, maksimal 72 karakter>

<penjelasan bila perlu: apa dan mengapa>
Refs: WBS <kode>, R-<nomor>
```

Tipe yang dipakai: `feat`, `fix`, `docs`, `test`, `chore`, `refactor`, `style`, `perf`,
`revert`. Satu commit satu alasan; jangan mencampur beberapa paket.

Contoh baik: `fix(4.2): perbaiki status disposisi tidak berubah setelah tindak lanjut`.
Contoh buruk: `update`, `fix bug`, `wip`.

## Push dan sinkronisasi

- Push pertama: `git push -u origin HEAD`.
- Push berikutnya: `git push`.
- Sinkron dengan `develop` sebelum membuka PR:
  ```bash
  git fetch origin
  git rebase origin/develop
  ```
- Bila push ditolak (`non-fast-forward`): `git fetch origin`, `git rebase origin/<cabang>`,
  lalu `git push`. Jangan langsung `--force`.
- `--force-with-lease` hanya untuk cabang sendiri yang belum ditinjau. `main` dan `develop`
  tidak pernah boleh di-force push.

Rincian lengkap ada pada [`docs/git-flow.md`](docs/git-flow.md) bagian 4 (commit),
5 (push), dan 6 (merapikan riwayat).

## Sebelum membuka PR

- [ ] `php artisan test` lulus (bila kode sudah ada)
- [ ] `npm run build` lulus (bila ada perubahan tampilan)
- [ ] Riwayat commit rapi (`git log --oneline origin/develop..HEAD`)
- [ ] Tidak ada `.env`, kunci, atau data pribadi ikut ter-commit
- [ ] Deskripsi PR menyebut kode WBS dan kebutuhan (R-xx / NF-xx)

## Peran peninjau

| Jenis perubahan | Wajib ditinjau oleh |
|---|---|
| Backend (`app/`, `database/`, `routes/`) | PM (Naufal) |
| Frontend (`resources/`) | PM (Naufal), atau BE bila PM berhalangan |
| Dokumen (`docs/`) | PM (Naufal) |
| Rilis ke `main` | PM (Naufal) |

SLA peninjauan: maksimal 1 × 24 jam pada hari kerja.
