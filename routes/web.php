<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Admin;
use App\Livewire\Web;
use Illuminate\Support\Facades\Route;

// ===== Halaman Publik (Company Profile) =====
Route::get('/', Web\HomePage::class)->name('home');
Route::get('/tentang', Web\AboutPage::class)->name('about');
Route::get('/dokter', Web\DoctorDirectory::class)->name('doctors.index');
Route::get('/dokter/{slug}', Web\DoctorDetail::class)->name('doctors.show');
Route::get('/janji-temu', Web\AppointmentBooking::class)->name('appointment');
Route::get('/artikel', Web\ArticleList::class)->name('articles.index');
Route::get('/artikel/{slug}', Web\ArticleDetail::class)->name('articles.show');
Route::get('/halaman/{slug}', Web\PageDetail::class)->name('pages.show');
Route::get('/kontak', Web\ContactPage::class)->name('contact');

// ===== Autentikasi Admin =====
Route::get('/login', [LoginController::class, 'show'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->name('login.store')->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ===== Admin Panel =====
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', Admin\DashboardOverview::class)
        ->middleware('role:super-admin|admin-pendaftaran|admin-konten')
        ->name('dashboard');

    // Front Office & Master Data (Pendaftaran + Super Admin)
    Route::middleware('role:super-admin|admin-pendaftaran')->group(function (): void {
        Route::get('/janji-temu', Admin\AppointmentManager::class)->name('appointments');
        Route::get('/rawat-jalan', Admin\OutpatientManager::class)->name('outpatient');
        Route::get('/rawat-inap', Admin\InpatientManager::class)->name('inpatient');
        Route::get('/poli', Admin\DepartmentManager::class)->name('departments');
        Route::get('/dokter', Admin\DoctorManager::class)->name('doctors');
        Route::get('/jadwal-praktik', Admin\ScheduleManager::class)->name('schedules');
        Route::get('/karyawan', Admin\EmployeeManager::class)->name('employees');
        Route::get('/pasien', Admin\PatientManager::class)->name('patients');
        Route::get('/obat', Admin\MedicineManager::class)->name('medicines');
    });

    // Konten Web (Humas/Konten + Super Admin)
    Route::middleware('role:super-admin|admin-konten')->group(function (): void {
        Route::get('/artikel', Admin\ArticleManager::class)->name('articles');
        Route::get('/kategori-artikel', Admin\ArticleCategoryManager::class)->name('article-categories');
        Route::get('/halaman', Admin\PageManager::class)->name('pages');
        Route::get('/konten-website', Admin\SiteContentManager::class)->name('site-content');
    });

    // Pengaturan Sistem (Super Admin saja)
    Route::middleware('role:super-admin')->group(function (): void {
        Route::get('/pengaturan', Admin\SettingsManager::class)->name('settings');
        Route::get('/pengguna', Admin\UserManager::class)->name('users');
    });
});
