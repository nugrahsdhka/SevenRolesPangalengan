# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

## [1.4.0](https://github.com/nugrahsdhka/SevenRolesPangalengan/compare/v1.3.6...v1.4.0) (2026-11-18)


### Features

* add public footer to all guest pages with S1 Teknik Komputer credit ([294c81f](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/294c81f2a2bfe1932b736ac758dd02efb359f3c6))

### [1.3.6](https://github.com/nugrahsdhka/SevenRolesPangalengan/compare/v1.3.5...v1.3.6) (2026-11-18)


### Features

* Changes to Village Data Collection in the Complaint Form ([fbd51b3](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/fbd51b3c3b567ae8f7810726e3053eaec489933e))
* Restrict complaint replies and list to the reported puskesmas (admin and camat view-only), add puskesmas to users, and seed 3 sample complaints per puskesmas ([20d7975](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/20d79757a4d3cbd4409f7dcb8e63abd171f0dccc))

### [1.3.5](https://github.com/nugrahsdhka/SevenRolesPangalengan/compare/v1.3.4...v1.3.5) (2026-11-18)


### Bug Fixes

* limit complaint submissions with throttle ([a6c44f3](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/a6c44f33120063e27b02c32955a37d89a8fec5d3))

### [1.3.4](https://github.com/nugrahsdhka/SevenRolesPangalengan/compare/v1.3.3...v1.3.4) (2026-11-15)


### Bug Fixes

* validasi period Camat (SQL injection), unik import Desa per periode, hapus route mati Gizi ([8a835c6](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/8a835c690a42e3abcfe641ac865e448eff4d90c5))

### [1.3.3](https://github.com/nugrahsdhka/SevenRolesPangalengan/compare/v1.3.2...v1.3.3) (2026-11-15)


### Bug Fixes

* security fixes mass assignment ([ace766c](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/ace766c0249b97ef828f5df053ae5b2fd4e9aec6))

### [1.3.2](https://github.com/nugrahsdhka/SevenRolesPangalengan/compare/v1.3.1...v1.3.2) (2026-11-15)


### Bug Fixes

* throttle login & token flow, hash tokens, uniform auth errors, fix seeder, remove /mem-test ([41619b9](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/41619b9be7e0ef4379da4ed486b867636e817988))

### [1.3.1](https://github.com/nugrahsdhka/SevenRolesPangalengan/compare/v1.3.0...v1.3.1) (2026-11-15)


### Bug Fixes

* hapus endpoint publik data per-anak ([33c515e](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/33c515e9b2712f15ff2586544db286679331d614))
* perbaiki urutan import CSS dan konfigurasi Tailwind v3 ([76f498f](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/76f498fa630d9dd164df5b4e54bc369c23bc1976))

## [1.3.0](https://github.com/nugrahsdhka/SevenRolesPangalengan/compare/v1.2.1...v1.3.0) (2026-11-15)


### Features

* penambahan fitur pengaduan ([6b529ee](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/6b529ee536659335b971d6ce1ba6d9de1a5855e3))


### Bug Fixes

* penghapusan chatbot dan penyesuaian responsive ([5c8b72b](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/5c8b72bacedab46233a0f16d4ce5338a3712ab7d))
* perbaikan penulisan Role and user ([4f86ec3](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/4f86ec36fb718999f6ab421a73dc859f9b384744))

### [1.2.1](https://github.com/nugrahsdhka/SevenRolesPangalengan/compare/v1.1.3...v1.2.1) (2026-07-08)


### Bug Fixes

* perbikan tampilan dan bug ([383b359](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/383b35968e005a4c9db94f2fe84cdbde96860279))

## [1.2.0](https://github.com/nugrahsdhka/SevenRolesPangalengan/compare/v1.1.3...v1.2.0) (2026-07-08)


### Features

* perbaikan tampilan dan bug ([365934b](https://github.com/nugrahsdhka/SevenRolesPangalengan/commit/365934b2656094167db5893bed55cde22e987099))

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