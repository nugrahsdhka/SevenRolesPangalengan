# Changelog

Semua perubahan penting pada project **Rumah Stunting Pangalengan** dicatat di file ini.

Format mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
dan versioning mengikuti [Semantic Versioning](https://semver.org/lang/id/).

> **Catatan:** Versi v1.0.0 s.d. v1.1.3 adalah rekonstruksi retroaktif dari histori
> `git log` sebelum formal versioning diterapkan. Detail per-commit tidak selalu presisi
> karena sebagian besar commit lama tidak memiliki pesan deskriptif (banyak berlabel `test`).
> Mulai versi berikutnya, changelog akan digenerate otomatis dari commit
> dengan format [Conventional Commits](https://www.conventionalcommits.org/).

---

## [v1.1.3] - HEAD saat ini
### Fixed
- Perbaikan perhitungan prevalensi (dua iterasi perbaikan)

## [v1.1.2]
### Fixed
- Perbaikan filter data anomali anak dan tampilannya

## [v1.1.1]
### Fixed
- Perbaikan animasi loading

## [v1.1.0]
### Added
- Fitur export data
### Fixed
- Perbaikan pada proses delete data berstatus anomali

## [v1.0.1]
### Note
- Rentang commit dengan pesan tidak deskriptif (`test`, `intial commit`) serta
  perbaikan import/header file. Dianggap sebagai iterasi internal, tidak ada
  perubahan fitur besar yang teridentifikasi dari pesan commit.

## [v1.0.0] - Baseline sistem inti
### Added
- Setup awal project (first commit)
- Sistem autentikasi (login, verifikasi email, session & waktu login)
- CRUD penuh Divisi Desa dengan validasi unik per periode
- Import data via Excel (termasuk drag & drop)
- Sistem periode di berbagai halaman (home, data stunting, admin, camat, divisi gizi)
- Halaman analisis hotspot dan peta (marker, dsb.)
- Halaman data stunting beserta chart
- Halaman data wilayah, faskes
- Pagination (admin & camat)
- Halaman profil
- Halaman error (500) dan maintenance
- Penyelarasan tema visual & navigasi tab persisten
### Changed
- Berbagai perbaikan tampilan/layout (home, header, data stunting, data wilayah)
- Perbaikan responsivitas dan pemisahan partisi file
### Fixed
- Perbaikan pada halaman auth
- Perbaikan verifikasi email

---

## Panduan Format Commit ke Depan

Gunakan format berikut agar versi bisa di-bump otomatis via `standard-version`:

| Prefix | Efek |
|---|---|
| `feat:` | MINOR bump (fitur baru) |
| `fix:` | PATCH bump (bug fix) |
| `feat!:` atau body berisi `BREAKING CHANGE:` | MAJOR bump |
| `chore:`, `docs:`, `style:`, `refactor:` | Tidak bump versi |

Contoh:
```
git commit -m "feat: tambah fitur hotspot analysis maps"
git commit -m "fix: benerin bug duplicate fetch interceptor loading"
```