# Git Branching Strategy — SISIPIN DISHUB

**Versi:** 1.0 | **Tanggal:** 21 Oktober 2026 | **Penulis:** Naufal (PM)

---

## 1. Cabang Utama (Permanen)

| Cabang | Deskripsi | Proteksi |
|---|---|---|
| `main` | Production-ready code. Setiap commit = rilis (tag `v*x.x.x-M*n*`). | ✅ Require PR, status checks, linear history |
| `develop` | Integration branch. Semua fitur masuk di sini sebelum ke `main`. | ✅ Require PR, status checks |

---

## 2. Cabang Kerja (Temporary)

Semua cabang kerja **dibuat dari `develop` terbaru** dan **dihapus setelah PR di-merge**.

| Prefix | Tujuan | Dari | Ke | Umur Max | Contoh |
|---|---|---|---|---|---|
| `feature/` | Fitur baru (backend/frontend) | `develop` | `develop` | 3 hari | `feature/3.4.1-surat-masuk-crud` |
| `fix/` | Perbaikan bug | `develop` | `develop` | 1 hari | `fix/4.1.4-status-disposisi` |
| `docs/` | Dokumentasi, panduan, komentar | `develop` | `develop` | 3 hari | `docs/2.3.2-wireframe-halaman-publik` |
| `test/` | Test case, matriks uji, skenario UAT | `develop` | `develop` | 2 hari | `test/4.1.2-unit-test-model` |
| `chore/` | Config, tooling, CI, dependency | `develop` | `develop` | 2 hari | `chore/3.1.2-git-branching-conventional` |
| `release/` | Persiapan gerbang rilis | `develop` | `main` + `develop` | 2 hari | `release/M3` |
| `hotfix/` | Darurat di production | `main` | `main` + `develop` | 1 hari | `hotfix/login-csrf-token` |

---

## 3. Aturan Penamaan Cabang

```
<tipe>/<kode-wbs>-<slug-3-6-kata>
```

- Kode WBS: 3 tingkat (`fase.grup.tugas`), mis. `3.4.1`
- Slug: lowercase, hyphen, max 55 karakter, tanpa spasi/underscore/karakter khusus
- Contoh:
  - `feature/3.4.1-surat-masuk-crud-bidang-tujuan-klasifikasi`
  - `fix/4.1.4-perbaikan-status-disposisi-tidak-berubah`
  - `docs/2.3.2-wireframe-halaman-publik-dan-autentikasi`

---

## 4. Alur Kerja Harian

```bash
# 1. Pagi: sync dengan develop
git checkout develop
git pull --ff-only
git checkout <cabang-saya>
git fetch origin
git rebase origin/develop   # selesaikan konflik di lokal

# 2. Kerjakan, commit per satuan logis
git add -p
git commit -m "feat(3.4.1): catat surat masuk" -m "Refs: WBS 3.4.1, R-04"

# 3. Sebelum push: test & lint
php artisan test
./vendor/bin/pint --dirty
npm run build

# 4. Push & PR
git push -u origin HEAD
gh pr create --base develop --fill

# 5. Setelah review & approve: merge (squash), hapus cabang
gh pr merge <nomor> --squash --admin --delete-branch
```

---

## 5. Larangan

- ❌ Push langsung ke `main` atau `develop`
- ❌ Cabang dari cabang orang lain (selalu dari `develop`)
- ❌ Commit `.env`, `vendor/`, `node_modules/`, `*.log`, `storage/logs/`
- ❌ Migrasi yang sudah di-merge diubah (buat migrasi baru)
- ❌ Cabang > 3 hari tanpa progress → pecah atau restart dari `develop`