# RS Medika Service — Hospital Company Profile & Sistem Manajemen Klinik (SIMRS-Lite)

Aplikasi web rumah sakit yang menggabungkan **company profile (Compro) publik** dengan **panel administrasi (Back Office)** untuk operasional harian — mulai dari janji temu online, direktori dokter, CMS artikel/berita, hingga master data dan rekam kunjungan rawat jalan/rawat inap.

Dikembangkan dengan **Laravel 13 + PHP 8.5**, **Livewire 4 (SPA Mode `wire:navigate` & component-based rendering)**, **Alpine.js**, **MySQL/MariaDB**, dan **spatie/laravel-permission** (role-based access).

---

## ✨ Fitur Utama

### 🌐 Sisi Publik (Company Profile)
- **Beranda** — hero, daftar poliklinik, dokter pilihan, artikel terbaru, CTA IGD 24 Jam.
- **Direktori Dokter** — filter *real-time* berdasarkan Poli/Spesialisasi, Hari Praktik, dan pencarian nama (tanpa reload), tampilan kartu dokter.
- **Profil Dokter** — foto, SIP, bio, tabel jadwal praktik mingguan + kuota.
- **Janji Temu Online** — form reaktif (pilih poli → dokter → tanggal); validasi otomatis apakah dokter praktik pada tanggal tersebut & kuota belum penuh; menerbitkan **Kode Booking unik** (format `APT-YYYYMMDD-XXX`) + tiket dan tombol konfirmasi langsung ke WhatsApp Admin.
- **Artikel & Berita** — portal edukasi kesehatan dengan filter kategori, pencarian instan, paginasi, dan halaman detail SEO-friendly (Open Graph).
- **Tentang Kami** & **Kontak** — profil RS, visi-misi, peta lokasi (Google Maps embed), form pesan.
- **Halaman Dinamis** (`/halaman/{slug}`) — halaman statis yang bisa dibuat/diedit admin (mis. Kebijakan Privasi, FAQ).

### 🔐 Panel Admin (Back Office)
- **Dashboard** — statistik (janji temu hari ini, menunggu konfirmasi, dokter aktif, pasien, rawat inap aktif, obat menipis, artikel terbit), grafik 14 hari, janji temu terbaru.
- **Front Office**
  - **Janji Temu** — antrean real-time; filter tanggal/poli/dokter/status; ubah status satu klik (Pending/Confirmed/Completed/Cancelled/Rescheduled); catatan internal; tombol WhatsApp konfirmasi otomatis.
  - **Rawat Jalan** — input & manajemen kunjungan poliklinik (no. kunjungan otomatis `RJ-YYYYMMDD-XXX`, keluhan, diagnosis, status).
  - **Rawat Inap** — input & manajemen rawat inap (no. admit otomatis `RI-...`, kelas/no. kamar, DPJP, aksi "Pulangkan" satu klik).
- **Master Data**
  - **Poli** (CRUD + guard hapus bila masih ada dokter),
  - **Dokter** (CRUD + upload foto otomatis dikonversi ke **WebP**),
  - **Jadwal Praktik** (editor grid per dokter, validasi anti-bentrok otomatis),
  - **Karyawan**, **Pasien** (no. RM otomatis `RM000001`), **Obat** (stok & ambang minimum, badge "menipis").
- **Konten Web**
  - **Artikel/Berita** dengan editor **WYSIWYG** (Summernote), thumbnail WebP, auto-slug, status Draft/Published + jadwal terbit,
  - **Kategori Artikel**,
  - **Halaman Web** — editor WYSIWYG untuk halaman statis situs.
- **Konten Website (CMS)** — seluruh teks & kartu di halaman **Beranda** (hero, badge, USP, section rawat inap, label statistik, CTA darurat) dan **Tentang** (visi, misi, statistik) bisa diedit langsung dari menu **Konten Website** — tanpa menyentuh kode.
- **Pengaturan** — identitas RS (nama, tagline, tentang), kontak & IGD (**0858 1704 8266**, **aripstrike@gmail.com**), Google Maps embed, sosial media (**Facebook, Instagram, TikTok**); tersimpan permanen di cache dan otomatis ter-*invalidate* saat diubah.
- **Pengguna & Role** — manajemen akun admin + hak akses (super-admin, admin-pendaftaran, admin-konten).

### 👥 Hak Akses (Role)
| Role | Hak Akses |
| :--- | :--- |
| `super-admin` | Akses penuh ke **semua menu**, termasuk Pengaturan RS dan Pengguna & Role. |
| `admin-pendaftaran` | Dashboard, Janji Temu, Rawat Jalan, Rawat Inap, Master Data (Poli, Dokter, Jadwal, Karyawan, Pasien, Obat). |
| `admin-konten` | Dashboard, Artikel/Berita, Kategori Artikel, Halaman Web. |

---

## 🚀 Cara Menjalankan (Local)

### Prasyarat
- PHP **8.5** + ekstensi: `gd`, `pdo_mysql`, `mbstring`, `intl`, `fileinfo`
- Composer 2, MySQL/MariaDB (Laragon direkomendasikan), Node tidak wajib (aset template sudah statis)

### Langkah Instalasi
```bash
# 1. Install dependency PHP
composer install

# 2. Konfigurasi environment
cp .env.example .env
php artisan key:generate
# Sesuaikan DB_DATABASE / DB_USERNAME / DB_PASSWORD di .env

# 3. Buat database (contoh MySQL): db_medicservice

# 4. Migrasi + isi data awal (role, akun, poli, dokter, artikel, dll.)
php artisan migrate --seed

# 5. Link storage (agar foto dokter/thumbnail tampil)
php artisan storage:link

# 6. Jalankan server
php artisan serve
```

Buka **http://localhost:8000** (publik) atau **http://localhost:8000/login** (admin).

### Akun Default (password: `password`)
| Email | Role |
| :--- | :--- |
| admin@medicservice.co.id | super-admin |
| pendaftaran@medicservice.co.id | admin-pendaftaran |
| konten@medicservice.co.id | admin-konten |

### Testing
```bash
php artisan test
```
Menjalankan 10 test (unit + feature) dengan database MySQL khusus `db_medicservice_test` (dibuat otomatis).

---

## 🏗️ Arsitektur & Struktur

**Domain-Driven Modular Monolith** — logika bisnis berada di `Action`/`Service`/`DTO`, bukan di komponen UI, sehingga siap diekspos sebagai REST API saat integrasi SIMRS/BPJS nanti.

```
app/
├── Core/               # Lintas domain: MediaUploadService (kompres & WebP), HasSlug, DateHelper
├── Domains/
│   ├── Medical/        # Department, Doctor, DoctorSchedule + UpsertDoctorAction, SyncScheduleAction (anti-bentrok), AvailableDoctorQuery, DoctorData (DTO)
│   ├── Appointment/    # Appointment + AppointmentStatus (enum), CreateAppointmentAction, BookingCodeGenerator, ScheduleValidationService (cek jadwal & kuota)
│   ├── Content/        # Article, ArticleCategory, SiteSetting (cache), Page + PublishArticleAction
│   ├── MasterData/     # Employee, Patient (no. RM otomatis), Medicine (stok & ambang)
│   └── Visit/          # OutpatientVisit, InpatientVisit + VisitNumberGenerator
├── Livewire/
│   ├── Web/            # Komponen publik (home, direktori dokter, booking, artikel, ...)
│   └── Admin/          # Komponen dashboard admin (statcard, CRUD, board antrean)
resources/views/
├── layouts/medical.blade.php   # Master layout publik (template Mediplace, @persist navbar/footer, wire:navigate)
├── layouts/admin.blade.php     # Master layout admin (AdminLTE 4, sidebar @persist, @role/@hasanyrole)
└── livewire/...                # View komponen
```

**Catatan performa & SPA:** navigasi publik/admin memakai `wire:navigate`; layout statis (navbar/footer/sidebar) dibungkus `@persist`; aset CSS/JS template dipisah antara Compro & Admin; gambar unggahan selalu dikonversi ke `.webp`; query utama eager-loaded; `SiteSetting` di-cache permanen.

---

## 🗺️ Roadmap — Rencana Pengembangan Selanjutnya

- **Integrasi SIMRS penuh** — modul pendaftaran BPJS (VClaim), e-SEP, rujukan, dan notifikasi WhatsApp gateway resmi (bukan wa.me manual).
- **Billing & Kasir** — tarif layanan, kwitansi, klaim asuransi/BPJS, integrasi mesin EDC.
- **Rekam Medis Elektronik (RME)** — anamnesa, diagnosis **ICD-10**, resep elektronik (e-Resep) ke farmasi, alergi otomatis terdeteksi.
- **Farmasi & Stok** — mutasi stok multi-gudang, kartu stok, expired date alert, purchase order otomatis ke supplier.
- **Laboratorium & Radiologi** — order lab online, input hasil, integrasi PACS/RIS.
- **Modul IGD & Triase** — alur triase, observasi, dan rujukan keluar.
- **Mobile App** — Android/iOS untuk pasien (booking, rekam medis, antrean real-time) via REST API yang sama.
- **Keuangan & HR** — penggajian, shift dokter/perawat, absensi karyawan.
- **Multi-cabang & Multi-RS** — satu codebase untuk jaringan rumah sakit.
- **Audit Log & Kepatuhan** — HIPAA/Kemenkes, retensi data, backup terjadwal.

---

## 📄 Lisensi & Kredit

- Template publik: **Mediplace** (themeforest) — aset pada `public/template_compro/`.
- Template admin: **AdminLTE 4** (ColorlibHQ) — aset pada `public/template_admin/`.
- **Foto** (dokter, gedung, resepsionis, artikel): [Unsplash](https://unsplash.com) — lisensi bebas pakai, diunduh dan disimpan lokal di `public/generated/`.
- **Logo & favicon** RS Medika Service dibuat khusus (lihat `scripts/generate-brand.php` dan `scripts/generate-placeholder.php`) — bebas dipakai ulang untuk presentasi.
- Ikon: **Font Awesome Free** & **Bootstrap Icons**.
- Catatan performa: navigasi publik/admin memakai `wire:navigate`; layout statis dibungkus `@persist`; gambar unggahan dikonversi ke `.webp`; `SiteSetting` di-cache permanen; container publik diperlebar (1560px/1740px) dan tipografi disesuaikan agar nyaman dibaca (lihat `public/generated/compro-overrides.css`).

---

## ✍️ Dibuat Oleh

**Arif Permana Putrasuryana**
Full-stack Developer — Laravel, Livewire, dan sistem informasi rumah sakit.

> Dibuat dengan ❤️ untuk RS Medika Service. Silakan sesuaikan nama RS, kontak, dan logo melalui menu **Admin → Pengaturan RS**.
