# PRODUCT REQUIREMENTS DOCUMENT (PRD)

**Nama Proyek:** Hospital Company Profile & Outpatient Appointment System  
**Tech Stack:** Laravel 13, PHP 8.5, Livewire (SPA Mode `wire:navigate` & Component-Based Rendering), Alpine.js, MySQL/MariaDB, Spatie Permission  
**Arsitektur:** Domain-Driven Modular Monolith (Future-Ready untuk Integrasi SIMRS / REST API)  
**Target Rilis MVP:** 14–21 Hari Kerja  

---

## 1. Tujuan & Arsitektur Sistem

Membangun sistem manajemen konten (*company profile*) dan layanan janji temu pasien (*outpatient appointment*) berbasis web untuk rumah sakit dengan performa tinggi, ringan, dan pengalaman navigasi setara *Single Page Application* (SPA) tanpa kompleksitas *framework* frontend terpisah.

### Pilar Utama Arsitektur:
1. **Ultra-Light SPA Experience (Component-Based Rendering):**
   * Menggunakan **Livewire** dengan direktif `wire:navigate` pada navigasi publik dan admin sehingga perpindahan halaman terjadi secara *asynchronous* tanpa *full-page reload* (aset CSS/JS template tidak dimuat ulang).
   * Komponen berat (seperti direktori dokter, filter jadwal, dan kalender janji temu) dirender secara terisolasi (*isolated component re-rendering*) serta mendukung *Lazy Loading* (`#[Lazy]`) dengan tampilan *skeleton placeholder* agar *First Contentful Paint* (FCP) sangat cepat.
2. **Domain-Driven Modular Monolith (Laravel 13 & PHP 8.5):**
   * Memanfaatkan fitur modern PHP 8.5 (`readonly` classes/properties, strict typing, backed Enums, match expressions, dan pipe/chaining ergonomics) untuk meminimalkan *boilerplate* dan mencegah *bug*.
   * Memisahkan logika bisnis berdasarkan domain (`Medical`, `Appointment`, `Content`) menggunakan **Action Pattern**, **DTOs**, dan **Query Classes**.
3. **API-Ready & Future-Proof:**
   * Seluruh *business logic* berada di dalam *Action* dan *Service Layer*, sehingga ketika rumah sakit membutuhkan integrasi SIMRS, BPJS, atau aplikasi mobile di masa depan, REST API dapat mengekspos *Action* yang sama persis.

---

## 2. Peran Pengguna (*User Roles & Permissions*)

Implementasi otorisasi berbasis *role* menggunakan `spatie/laravel-permission`:

| Role | Akses & Tanggung Jawab Utama |
| :--- | :--- |
| **Super Admin (IT / Manajemen)** | Akses penuh ke seluruh modul, manajemen pengguna & *role*, pengaturan profil rumah sakit, serta konfigurasi global sistem. |
| **Admin Pendaftaran (Front Office)** | Mengelola data poli, profil & jadwal praktik dokter, memverifikasi pengajuan *appointment* pasien secara *real-time*, dan memantau laporan kunjungan. |
| **Admin Konten (Humas / Marketing)** | Menulis, menyunting, dan menerbitkan artikel edukasi kesehatan, berita rumah sakit, kategori konten, serta promo layanan. |
| **Pengunjung / Pasien (Public)** | Menjelajahi profil RS, mencari dokter dengan filter instan tanpa *reload*, melihat jadwal praktik, membaca artikel, dan mengajukan janji temu. |

---

## 3. Spesifikasi Fungsional & Komponen SPA

### Modul A: Direktori & Manajemen Dokter (`Domain\Medical`)
* **Komponen Publik (Livewire SPA):**
  * `DoctorDirectoryComponent`: Filter dokter secara *real-time* berdasarkan **Poli/Spesialisasi**, **Hari Praktik**, dan **Pencarian Nama** menggunakan `wire:model.live.debounce.300ms` tanpa memuat ulang halaman.
  * `DoctorProfileComponent`: Menampilkan detail dokter dan jadwal praktik aktif, dimuat instan melalui `wire:navigate`.
* **Komponen Admin:**
  * CRUD Poli / Spesialisasi (`DepartmentManager`).
  * CRUD Dokter (`DoctorManager`) dengan unggah foto otomatis terkonversi ke format `.webp` yang ringan.
  * Manajemen Jadwal Praktik Mingguan (`ScheduleManager`) dengan validasi bentrok jam praktik.

### Modul B: Sistem Appointment Dokter (`Domain\Appointment`)
* **Komponen Publik (`AppointmentBookingForm`):**
  1. Pemilihan **Poli** $\rightarrow$ Otomatis memuat daftar **Dokter** pada poli tersebut secara reaktif.
  2. Pemilihan **Tanggal Kunjungan** $\rightarrow$ Validasi instan apakah dokter memiliki jadwal praktik pada hari di tanggal tersebut; menampilkan jam praktik yang tersedia.
  3. Pengisian data pasien: Nama Lengkap, NIK / No. Rekam Medis (opsional), Tanggal Lahir, Jenis Kelamin, Nomor WhatsApp aktif, dan keluhan singkat.
  4. Menghasilkan **Kode Booking Unik** (contoh: `APT-20261116-004`) dan menampilkan tiket ringkasan beserta tombol konfirmasi langsung ke WhatsApp Admin (`wa.me`).
* **Komponen Admin (`AppointmentBoard`):**
  * Daftar antrean janji temu masuk dengan filter tanggal, poli, dokter, dan status (`Pending`, `Confirmed`, `Completed`, `Cancelled`, `Rescheduled`).
  * Pembaruan status satu klik (*inline update*) tanpa *reload* halaman dan tombol kirim pesan konfirmasi otomatis ke WhatsApp pasien.

### Modul C: Portal Artikel & Berita (`Domain\Content`)
* **Komponen Publik:**
  * `ArticleFeedComponent`: Indeks artikel & berita dengan filter kategori, pencarian instan, dan paginasi SPA (`WithPagination`).
  * `ArticleDetailComponent`: Halaman baca artikel yang ramah SEO (*OpenGraph & Meta Tags* dinamis) beserta artikel terkait.
* **Komponen Admin:**
  * Manajemen kategori dan editor artikel (*Rich Text Editor* terintegrasi dengan template admin) dengan dukungan *thumbnail* WebP, *auto-slug*, dan penjadwalan terbit (`Draft` / `Published`).

### Modul D: Pengaturan Dinamis (`SiteSettings`)
* Pengelolaan informasi global rumah sakit (Nomor IGD, Alamat, Google Maps Embed, Sosial Media, Banner Utama) yang di-*cache* secara permanen dan otomatis di-*invalidate* saat diperbarui admin.

---

## 4. Rancangan Skema Database (Core Tables)

### 1. `departments` (Poliklinik / Spesialisasi)
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED (PK)` | Auto Increment |
| `name` | `VARCHAR(150)` | Nama poli (misal: Poli Umum, Poli Anak) |
| `slug` | `VARCHAR(170)` | Unique URL slug |
| `description` | `TEXT (Nullable)` | Deskripsi layanan poli |
| `icon` | `VARCHAR(255) (Nullable)` | Path ikon atau class icon template |
| `is_active` | `BOOLEAN` | Default: `true` |
| `timestamps` | `TIMESTAMP` | `created_at` & `updated_at` |

### 2. `doctors` (Data Dokter)
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED (PK)` | Auto Increment |
| `department_id` | `BIGINT UNSIGNED (FK)` | Relasi ke `departments.id` |
| `name` | `VARCHAR(150)` | Nama lengkap beserta gelar |
| `slug` | `VARCHAR(170)` | Unique URL slug |
| `photo` | `VARCHAR(255) (Nullable)` | Path file foto WebP di storage |
| `sip_number` | `VARCHAR(100) (Nullable)` | Nomor Surat Izin Praktik |
| `bio` | `TEXT (Nullable)` | Profil singkat / riwayat pendidikan |
| `is_active` | `BOOLEAN` | Default: `true` |
| `timestamps` | `TIMESTAMP` | `created_at` & `updated_at` |

### 3. `doctor_schedules` (Jadwal Praktik Dokter)
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED (PK)` | Auto Increment |
| `doctor_id` | `BIGINT UNSIGNED (FK)` | Relasi ke `doctors.id` (Cascade Delete) |
| `day_of_week` | `TINYINT` | `1` (Senin) sampai `7` (Minggu) |
| `start_time` | `TIME` | Jam mulai praktik |
| `end_time` | `TIME` | Jam selesai praktik |
| `quota` | `INTEGER (Nullable)` | Maksimal pasien per sesi (opsional) |
| `notes` | `VARCHAR(255) (Nullable)` | Catatan tambahan jadwal |
| `timestamps` | `TIMESTAMP` | `created_at` & `updated_at` |

### 4. `appointments` (Transaksi Janji Temu)
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED (PK)` | Auto Increment |
| `booking_code` | `VARCHAR(50)` | Unique code (misal: `APT-20261116-001`) |
| `doctor_id` | `BIGINT UNSIGNED (FK)` | Relasi ke `doctors.id` |
| `patient_name` | `VARCHAR(150)` | Nama lengkap pasien |
| `patient_phone` | `VARCHAR(25)` | Nomor WhatsApp pasien |
| `patient_dob` | `DATE` | Tanggal lahir pasien |
| `patient_gender` | `ENUM('L', 'P')` | Jenis kelamin pasien |
| `mr_number` | `VARCHAR(50) (Nullable)` | No. Rekam Medis (jika pasien lama) |
| `appointment_date` | `DATE` | Tanggal rencana kunjungan |
| `complaint` | `TEXT (Nullable)` | Keluhan singkat pasien |
| `status` | `ENUM` | `pending`, `confirmed`, `completed`, `cancelled`, `rescheduled` |
| `admin_notes` | `TEXT (Nullable)` | Catatan internal petugas pendaftaran |
| `timestamps` | `TIMESTAMP` | `created_at` & `updated_at` |

### 5. `article_categories`, `articles`, & `settings`
* **`article_categories`:** `id`, `name`, `slug`, `timestamps`
* **`articles`:** `id`, `category_id` (FK), `author_id` (FK ke `users`), `title`, `slug`, `thumbnail`, `content` (LONGTEXT), `meta_description`, `status` (`draft`/`published`), `published_at`, `timestamps`
* **`settings`:** `id`, `key` (Unique), `value` (TEXT), `group` (`general`, `contact`, `social`), `timestamps`

---

## 5. Struktur Direktori Modular (Laravel 13 & PHP 8.5)

```text
app/
├── Core/                                # Utilitas lintas domain
│   ├── Services/
│   │   └── MediaUploadService.php       # Auto-resize & konversi WebP
│   ├── Traits/
│   │   └── HasSlug.php
│   └── Helpers/
│       └── DateHelper.php               # Format tanggal & nama hari Indonesia
│
├── Domains/                             # Business Logic Terisolasi per Domain
│   ├── Medical/
│   │   ├── Models/
│   │   │   ├── Department.php
│   │   │   ├── Doctor.php
│   │   │   └── DoctorSchedule.php
│   │   ├── Actions/
│   │   │   ├── UpsertDoctorAction.php
│   │   │   └── SyncScheduleAction.php
│   │   ├── Queries/
│   │   │   └── AvailableDoctorQuery.php
│   │   └── DTOs/
│   │       └── DoctorData.php
│   │
│   ├── Appointment/
│   │   ├── Models/
│   │   │   └── Appointment.php
│   │   ├── Enums/
│   │   │   └── AppointmentStatus.php    # PHP 8.5 Backed Enum
│   │   ├── Actions/
│   │   │   ├── CreateAppointmentAction.php
│   │   │   └── UpdateAppointmentStatusAction.php
│   │   ├── Services/
│   │   │   ├── BookingCodeGenerator.php
│   │   │   └── ScheduleValidationService.php
│   │   └── DTOs/
│   │       └── AppointmentBookingData.php
│   │
│   └── Content/
│       ├── Models/
│       │   ├── Article.php
│       │   ├── ArticleCategory.php
│       │   └── SiteSetting.php
│       └── Actions/
│           └── PublishArticleAction.php
│
└── Livewire/                            # Komponen UI Reaktif (SPA Layer)
    ├── Web/                             # Komponen Publik (Compro Medical)
    │   ├── HomePage.php
    │   ├── DoctorDirectory.php
    │   ├── DoctorDetail.php
    │   ├── AppointmentBooking.php
    │   └── ArticleList.php
    └── Admin/                           # Komponen Dashboard Admin
        ├── DashboardOverview.php
        ├── DepartmentManager.php
        ├── DoctorManager.php
        ├── ScheduleManager.php
        ├── AppointmentManager.php
        └── ArticleManager.php

resources/
└── views/
    ├── layouts/
    │   ├── medical.blade.php            # Master layout publik (dengan @persist & wire:navigate)
    │   └── admin.blade.php              # Master layout admin (dengan @persist sidebar/navbar)
    ├── components/
    │   ├── medical/                     # Reusable Blade UI (doctor-card, skeleton, dll.)
    │   └── admin/                       # Reusable Admin UI (modal, badge, stat-card)
    └── livewire/
        ├── web/                         # View komponen publik
        └── admin/                       # View komponen admin
```

---

## 6. Strategi Optimasi Performa & SPA Lightweight

1. **`@persist` untuk Layout Statis:** Membungkus bagian *Navbar*, *Sidebar Admin*, dan *Footer* dengan direktif `@persist` agar elemen DOM tersebut tidak di-render ulang saat pengguna berpindah halaman via `wire:navigate`.
2. **Isolasi Aset CSS & JS:** Template *Compro Medical* dan *Admin Dashboard* memiliki *master layout* masing-masing (`medical.blade.php` & `admin.blade.php`) agar skrip jQuery/plugin template admin tidak membebani halaman publik.
3. **Re-inisialisasi Plugin Template pada Event SPA:** Menangani *lifecycle hook* `document.addEventListener('livewire:navigated', ...)` agar slider, animasi AOS, atau datepicker dari template *compro medical* tetap berjalan mulus setelah perpindahan halaman SPA.
4. **Optimasi Query & Caching:**
   * *Eager Loading* ketat (`with(['department', 'schedules'])`) untuk mencegah *N+1 query*.
   * Penggunaan `#[Computed]` property di Livewire untuk meng-cache hasil query di dalam siklus *request* komponen.
   * Format gambar wajib terkonversi otomatis ke `.webp` saat diunggah admin untuk menjaga ukuran halaman tetap di bawah 1 MB.

---

## 7. Rencana Pengembangan Bertahap (*Roadmap*)

* **Fase 1 (Hari 1–3):** Setup **Laravel 13 (PHP 8.5)**, instalasi Livewire & Spatie Permission, konfigurasi database, migrasi tabel, dan integrasi *master layout* SPA (`medical.blade.php` & `admin.blade.php`).
* **Fase 2 (Hari 4–8):** Pengembangan Domain `Medical` (Poli, Dokter, WebP Image Service, Jadwal Praktik) beserta komponen SPA direktori dokter dengan filter *real-time*.
* **Fase 3 (Hari 9–13):** Pengembangan Domain `Appointment` (validasi jadwal vs tanggal kunjungan, generator kode booking, form booking reaktif) dan manajemen antrean admin.
* **Fase 4 (Hari 14–17):** Pengembangan Domain `Content` (CMS Artikel/Berita SEO-friendly & Pengaturan Dinamis RS).
* **Fase 5 (Hari 18–21):** Pengujian performa (*Lighthouse & Core Web Vitals*), *security audit* (*rate limiting* & sanitasi XSS), input data awal, dan *deployment* ke server produksi.