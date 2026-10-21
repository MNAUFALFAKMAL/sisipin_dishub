# Panduan Kontribusi

Panduan lengkap ada di [`docs/git-flow.md`](docs/git-flow.md). Halaman ini hanya ringkasannya.

## Tiga aturan yang tidak boleh dilanggar

1. **Jangan pernah push langsung ke `main` atau `develop`.** Keduanya dilindungi; perubahan masuk lewat Pull Request.
2. **Satu paket kerja = satu cabang = satu PR.** Nama cabang memakai kode WBS, misalnya `feature/3.1-surat-masuk`.
3. **Jangan commit rahasia.** `.env`, kunci, kata sandi, dan berkas `akun.md` sudah diabaikan; jangan dipaksa masuk.

## Alur singkat

```bash
git checkout develop
git pull --ff-only
git checkout -b feature/3.1-surat-masuk

# ...kerjakan, commit kecil dan sering...
git add .
git commit -m "feat(3.1): catat surat masuk dan unggah lampiran"

git push -u origin feature/3.1-surat-masuk
gh pr create --base develop --fill
```

Setelah PR disetujui dan digabung (squash), hapus cabangnya.

## Format commit

```
<tipe>(<kode-wbs>): <ringkasan singkat>

<penjelasan bila perlu>
Refs: WBS <kode>, R-<nomor>
```

Tipe yang dipakai: `feat`, `fix`, `docs`, `test`, `chore`, `refactor`, `style`, `perf`.

## Sebelum membuka PR

- [ ] `php artisan test` lulus (bila kode sudah ada)
- [ ] `npm run build` lulus (bila ada perubahan tampilan)
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
