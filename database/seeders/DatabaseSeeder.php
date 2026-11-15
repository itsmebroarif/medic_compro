<?php

namespace Database\Seeders;

use App\Core\Services\MediaUploadService;
use App\Domains\Appointment\Models\Appointment;
use App\Domains\Content\Models\Article;
use App\Domains\Content\Models\ArticleCategory;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\SiteSetting;
use App\Domains\MasterData\Models\Employee;
use App\Domains\MasterData\Models\Medicine;
use App\Domains\MasterData\Models\Patient;
use App\Domains\Medical\Models\Department;
use App\Domains\Medical\Models\Doctor;
use App\Domains\Medical\Models\DoctorSchedule;
use App\Domains\Visit\Models\InpatientVisit;
use App\Domains\Visit\Models\OutpatientVisit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== 1. Roles =====
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = collect(['super-admin', 'admin-pendaftaran', 'admin-konten'])
            ->mapWithKeys(fn (string $role) => [$role => Role::findOrCreate($role, 'web')]);

        // ===== 2. Users =====
        $admin = User::firstOrCreate(
            ['email' => 'admin@medicservice.co.id'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')],
        );
        $admin->syncRoles(['super-admin']);

        $pendaftaran = User::firstOrCreate(
            ['email' => 'pendaftaran@medicservice.co.id'],
            ['name' => 'Admin Pendaftaran', 'password' => Hash::make('password')],
        );
        $pendaftaran->syncRoles(['admin-pendaftaran']);

        $konten = User::firstOrCreate(
            ['email' => 'konten@medicservice.co.id'],
            ['name' => 'Admin Konten', 'password' => Hash::make('password')],
        );
        $konten->syncRoles(['admin-konten']);

        // ===== 3. Pengaturan Situs =====
        $settings = [
            'hospital_name' => 'RS Medika Service',
            'tagline' => 'Layanan kesehatan terpadu: rawat jalan, rawat inap, poliklinik spesialis, dan IGD 24 jam.',
            'about_short' => 'Melayani dengan sepenuh hati, profesional, dan terpercaya untuk kesehatan Anda dan keluarga.',
            'about' => 'RS Medika Service adalah rumah sakit umum swasta yang berkomitmen memberikan pelayanan kesehatan paripurna: rawat jalan, rawat inap, poliklinik spesialis, laboratorium, radiologi, farmasi 24 jam, dan instalasi gawat darurat (IGD) yang siaga setiap saat.',
            'meta_description' => 'RS Medika Service - Rumah sakit dengan layanan rawat jalan, rawat inap, poliklinik spesialis, dan IGD 24 jam di Jakarta.',
            'igd_phone' => '0858 1704 8266',
            'phone' => '0858 1704 8266',
            'whatsapp' => '6285817048266',
            'email' => 'aripstrike@gmail.com',
            'address' => 'Jl. Kesehatan Raya No. 1, Jakarta Selatan 12140',
            'hours' => 'Senin–Sabtu, 08.00–20.00 WIB (IGD 24 Jam)',
            'maps_embed' => 'https://www.google.com/maps?q=RS%20Jakarta&output=embed',
            'facebook' => 'https://facebook.com/rsmedikaservice',
            'instagram' => 'https://instagram.com/rsmedikaservice',
            'tiktok' => 'https://tiktok.com/@rsmedikaservice',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value, str_starts_with($key, 'igd') || in_array($key, ['phone', 'whatsapp', 'email', 'address', 'hours', 'maps_embed'], true) ? 'contact' : (in_array($key, ['facebook', 'instagram', 'tiktok'], true) ? 'social' : 'general'));
        }

        // Hapus pengaturan lama yang tidak dipakai lagi
        SiteSetting::query()->whereIn('key', ['twitter', 'linkedin', 'youtube'])->delete();

        // ===== 4. Departemen =====
        $departmentData = [
            ['Poli Umum', 'Pelayanan kesehatan dasar untuk keluhan demam, flu, batuk, kontrol kesehatan, dan MCU.'],
            ['Poli Anak', 'Tumbuh kembang, imunisasi, penanganan infeksi dan penyakit kronis pada anak.'],
            ['Poli Jantung & Pembuluh Darah', 'Konsultasi jantung, EKG, ekokardiografi, dan manajemen hipertensi.'],
            ['Poli Kandungan & Kebidanan', 'Pemeriksaan kehamilan, USG, KB, dan persalinan.'],
            ['Poli Mata', 'Periksa mata, kacamata, katarak, dan glaukoma.'],
            ['Poli THT', 'Gangguan telinga, hidung, tenggorokan, dan sinusitis.'],
            ['Poli Gigi & Mulut', 'Tambal, scaling, cabut gigi, dan ortodonti.'],
            ['Poli Bedah', 'Konsultasi bedah umum, bedah minor, dan perawatan luka.'],
        ];

        $departments = [];
        foreach ($departmentData as [$name, $description]) {
            $departments[$name] = Department::firstOrCreate(['name' => $name], ['description' => $description]);
        }

        // ===== 5. Dokter =====
        $doctorData = [
            ['dr. Rendra Wijaya, Sp.PD', 'Poli Umum', 'SIP/2024/001001', 'Spesialis Penyakit Dalam dengan 12 tahun pengalaman menangani diabetes, hipertensi, dan penyakit infeksi. Lulusan FKUI dan 6 tahun praktik di RS rujukan nasional.'],
            ['dr. Sinta Maharani, Sp.A', 'Poli Anak', 'SIP/2024/001002', 'Spesialis Anak, fokus pada tumbuh kembang, imunisasi, dan alergi anak. Aktif sebagai pembicara edukasi parenting.'],
            ['dr. Bimo Aditama, Sp.JP', 'Poli Jantung & Pembuluh Darah', 'SIP/2024/001003', 'Spesialis Jantung dan Pembuluh Darah. Berpengalaman di ekokardiografi transesofageal dan kardiologi intervensi dasar.'],
            ['dr. Laras Puspita, Sp.OG', 'Poli Kandungan & Kebidanan', 'SIP/2024/001004', 'Spesialis Obstetri dan Ginekologi, fokus kehamilan risiko tinggi dan USG 4D.'],
            ['dr. Fajar Ramadhan, Sp.M', 'Poli Mata', 'SIP/2024/001005', 'Spesialis Mata, menangani katarak, glaukoma, dan kelainan refraksi pada anak dan dewasa.'],
            ['dr. Dewi Anggraini, Sp.THT-KL', 'Poli THT', 'SIP/2024/001006', 'Spesialis Telinga Hidung Tenggorok Bedah Kepala Leher. Ahli sinusitis kronik dan sleep apnea anak.'],
            ['dr. Gilang Pratama, Sp.KG', 'Poli Gigi & Mulut', 'SIP/2024/001007', 'Spesialis Konservasi Gigi. Ahli tambal, perawatan saluran akar, dan estetika gigi.'],
            ['dr. Nadia Putri, Sp.B', 'Poli Bedah', 'SIP/2024/001008', 'Spesialis Bedah Umum, menangani hernia, apendiks, dan luka kronis dengan pendekatan minimal invasif.'],
            ['dr. Yoga Saputra, Sp.PD', 'Poli Umum', 'SIP/2024/001009', 'Spesialis Penyakit Dalam dengan minat khusus gastroenterologi dan penyakit tropik.'],
            ['dr. Amanda Kirana, Sp.A', 'Poli Anak', 'SIP/2024/001010', 'Spesialis Anak, fokus nutrisi bayi-balita dan penanganan demam berdarah dengue.'],
        ];

        $doctors = [];
        foreach ($doctorData as [$name, $deptName, $sip, $bio]) {
            $doctors[$name] = Doctor::firstOrCreate(
                ['name' => $name],
                ['department_id' => $departments[$deptName]->id, 'sip_number' => $sip, 'bio' => $bio, 'is_active' => true],
            );
        }

        // ===== 6. Jadwal Praktik =====
        $scheduleData = [
            ['dr. Rendra Wijaya, Sp.PD', [1, 3, 5], '08:00', '12:00', 30],
            ['dr. Sinta Maharani, Sp.A', [1, 2, 4], '09:00', '13:00', 25],
            ['dr. Bimo Aditama, Sp.JP', [2, 4, 6], '13:00', '17:00', 20],
            ['dr. Laras Puspita, Sp.OG', [1, 3, 5], '10:00', '14:00', 20],
            ['dr. Fajar Ramadhan, Sp.M', [2, 5], '08:00', '11:00', 20],
            ['dr. Dewi Anggraini, Sp.THT-KL', [3, 6], '13:00', '16:00', 25],
            ['dr. Gilang Pratama, Sp.KG', [1, 4, 6], '09:00', '15:00', 15],
            ['dr. Nadia Putri, Sp.B', [2, 5], '10:00', '13:00', 15],
            ['dr. Yoga Saputra, Sp.PD', [2, 4, 6], '16:00', '20:00', 30],
            ['dr. Amanda Kirana, Sp.A', [3, 5, 6], '09:00', '12:00', 25],
        ];

        if (DoctorSchedule::count() === 0) {
            foreach ($scheduleData as [$doctorName, $days, $start, $end, $quota]) {
                foreach ($days as $day) {
                    DoctorSchedule::create([
                        'doctor_id' => $doctors[$doctorName]->id,
                        'day_of_week' => $day,
                        'start_time' => $start,
                        'end_time' => $end,
                        'quota' => $quota,
                    ]);
                }
            }
        }

        // ===== 7. Kategori & Artikel =====
        $categories = [];
        foreach (['Edukasi Kesehatan', 'Berita RS', 'Promo Layanan'] as $categoryName) {
            $categories[$categoryName] = ArticleCategory::firstOrCreate(['name' => $categoryName]);
        }

        if (Article::count() === 0) {
            $articles = [
                ['Edukasi Kesehatan', 'Kenali Tanda-Tanda Serangan Jantung Sejak Dini', '<p>Nyeri dada yang menjalar ke lengan kiri, sesak napas, keringat dingin, dan mual adalah gejala khas serangan jantung. Jangan tunda, segera hubungi IGD kami di <strong>021 5550 1234</strong> jika mengalami gejala tersebut.</p><h3>Langkah pencegahan</h3><ul><li>Kontrol tekanan darah dan kolesterol rutin</li><li>Berhenti merokok dan batasi alkohol</li><li>Olahraga minimal 30 menit sehari</li><li>Kelola stres dengan baik</li></ul><p>Pemeriksaan jantung berkala di poli jantung kami dianjurkan bagi Anda berusia di atas 40 tahun atau memiliki riwayat keluarga penyakit jantung.</p>'],
                ['Edukasi Kesehatan', 'Panduan Imunisasi Lengkap untuk Bayi dan Balita', '<p>Imunisasi dasar lengkap melindungi anak dari penyakit berbahaya seperti hepatitis B, polio, DPT, campak, dan Hib. Kunjungi Poli Anak RS Medika Service untuk jadwal imunisasi buah hati Anda.</p><h3>Jadwal umum imunisasi dasar</h3><ul><li>0–1 bulan: Hepatitis B</li><li>2, 3, 4 bulan: DPT-HB-Hib, polio, PCV, rotavirus</li><li>9 bulan: Campak-MR</li></ul><p>Konsultasikan riwayat imunisasi anak Anda dengan dokter spesialis anak kami.</p>'],
                ['Edukasi Kesehatan', '5 Kebiasaan Sederhana Menjaga Kesehatan Gigi dan Mulut', '<p>Sikat gigi dua kali sehari dengan pasta gigi berfluoride, gunakan benang gigi, batasi gula, dan kontrol 6 bulan sekali. Gigi berlubang yang dibiarkan dapat menjadi infeksi serius ke jantung.</p><p>Poli Gigi &amp; Mulut kami melayani scaling, tambal, dan perawatan saluran akar setiap Senin–Sabtu.</p>'],
                ['Berita RS', 'RS Medika Service Raih Akreditasi Paripurna KARS', '<p>Kami bangga mengumumkan bahwa RS Medika Service kembali meraih predikat tertinggi akreditasi KARS dalam standar keselamatan pasien internasional.</p><p>Pencapaian ini adalah hasil kerja keras seluruh tim medis dan manajemen dalam menjaga standar pelayanan terbaik.</p>'],
                ['Berita RS', 'Layanan MCU Korporat Kini Tersedia Setiap Sabtu', '<p>Mulai bulan ini, layanan Medical Check Up untuk perusahaan tersedia setiap Sabtu pukul 08.00–13.00 WIB dengan paket kesehatan pekerja yang komprehensif.</p><p>Hubungi marketing kami di 021 5550 1235 untuk informasi paket dan kerja sama korporat.</p>'],
                ['Promo Layanan', 'Paket Persalinan Normal Mulai Rp 7,5 Juta', '<p>Paket persalinan normal meliputi kamar rawat 2 hari, perawatan bayi baru lahir, konsultasi laktasi, dan kontrol 1 minggu pascapersalinan.</p><p>Konsultasikan kehamilan Anda di Poli Kandungan &amp; Kebidanan kami.</p>'],
            ];

            foreach ($articles as [$categoryName, $title, $content]) {
                Article::create([
                    'category_id' => $categories[$categoryName]->id,
                    'author_id' => $konten->id,
                    'title' => $title,
                    'content' => $content,
                    'meta_description' => str(strip_tags($content))->limit(150)->toString(),
                    'status' => 'published',
                    'published_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        // ===== 8. Karyawan =====
        if (Employee::count() === 0) {
            foreach ([
                ['K-001', 'Sari Wulandari', 'P', 'Perawat IGD', 1, '081234500001', 'sari@medicservice.co.id', '2019-03-12'],
                ['K-002', 'Budi Santoso', 'L', 'Apoteker', null, '081234500002', 'budi@medicservice.co.id', '2020-07-01'],
                ['K-003', 'Rina Marlina', 'P', 'Administrasi Pendaftaran', null, '081234500003', 'rina@medicservice.co.id', '2022-01-17'],
                ['K-004', 'Agus Setiawan', 'L', 'Radiografer', null, '081234500004', 'agus@medicservice.co.id', '2018-11-05'],
            ] as [$nip, $name, $gender, $position, $deptIndex, $phone, $email, $joinDate]) {
                Employee::create(['nip' => $nip, 'name' => $name, 'gender' => $gender, 'position' => $position, 'department_id' => $deptIndex ? array_values($departments)[$deptIndex - 1]->id : null, 'phone' => $phone, 'email' => $email, 'join_date' => $joinDate, 'is_active' => true]);
            }
        }

        // ===== 9. Pasien =====
        if (Patient::count() === 0) {
            foreach ([
                ['3174050604900001', 'Andi Wijaya', '1990-04-06', 'L', '081399880001', 'Jl. Melati No. 10, Jakarta Selatan'],
                ['3174055507850002', 'Maya Lestari', '1985-07-15', 'P', '081399880002', 'Jl. Kenanga No. 5, Jakarta Pusat'],
                ['3174051202990003', 'Reno Firmansyah', '1999-02-12', 'L', '081399880003', 'Jl. Mawar No. 22, Depok'],
            ] as [$nik, $name, $dob, $gender, $phone, $address]) {
                $patient = Patient::create(['mr_number' => 'TMP-'.uniqid(), 'nik' => $nik, 'name' => $name, 'dob' => $dob, 'gender' => $gender, 'phone' => $phone, 'address' => $address, 'is_active' => true]);
                $patient->update(['mr_number' => 'RM'.str_pad((string) $patient->id, 6, '0', STR_PAD_LEFT)]);
            }
        }

        // ===== 10. Obat =====
        if (Medicine::count() === 0) {
            foreach ([
                ['OBT-0001', 'Paracetamol 500 mg', 'Analgesik', 'strip', 1250, 200, 1500],
                ['OBT-0002', 'Amoxicillin 500 mg', 'Antibiotik', 'strip', 800, 150, 3500],
                ['OBT-0003', 'Cetirizine 10 mg', 'Antihistamin', 'strip', 60, 100, 2000],
                ['OBT-0004', 'Omeprazole 20 mg', 'Antasida', 'strip', 500, 100, 2500],
                ['OBT-0005', 'Vitamin C 50 mg', 'Vitamin', 'botol', 300, 50, 12000],
                ['OBT-0006', 'Infus NaCl 0,9%', 'Cairan Infus', 'kantong', 220, 80, 28000],
            ] as [$code, $name, $category, $unit, $stock, $min, $price]) {
                Medicine::create(['code' => $code, 'name' => $name, 'category' => $category, 'unit' => $unit, 'stock' => $stock, 'min_stock' => $min, 'price' => $price, 'is_active' => true]);
            }
        }

        // ===== 11. Janji Temu Contoh =====
        if (Appointment::count() === 0) {
            $seedAppointments = [
                ['APT-'.now()->format('Ymd').'-001', 'RM'.str_pad((string) (Patient::value('id') ?? 1), 6, '0', STR_PAD_LEFT), 0, 'Andi Wijaya', '081399880001', '1990-04-06', 'L', now(), 'pending'],
                ['APT-'.now()->format('Ymd').'-002', null, 1, 'Maya Lestari', '081399880002', '1985-07-15', 'P', now(), 'confirmed'],
                ['APT-'.now()->addDay()->format('Ymd').'-003', null, 2, 'Reno Firmansyah', '081399880003', '1999-02-12', 'L', now()->addDay(), 'pending'],
                ['APT-'.now()->subDay()->format('Ymd').'-004', null, 0, 'Budi Santoso', '081234500002', '1988-09-20', 'L', now()->subDay(), 'completed'],
            ];

            foreach ($seedAppointments as [$code, $mr, $doctorIndex, $name, $phone, $dob, $gender, $date, $status]) {
                Appointment::create([
                    'booking_code' => $code,
                    'doctor_id' => array_values($doctors)[$doctorIndex]->id,
                    'patient_name' => $name,
                    'patient_phone' => $phone,
                    'patient_dob' => $dob,
                    'patient_gender' => $gender,
                    'mr_number' => $mr,
                    'appointment_date' => $date->toDateString(),
                    'complaint' => 'Pemeriksaan rutin dan konsultasi.',
                    'status' => $status,
                ]);
            }
        }

        // ===== 12. Kunjungan Rawat Jalan & Inap Contoh =====
        if (OutpatientVisit::count() === 0 && Patient::count() > 0) {
            $firstPatient = Patient::first();
            $firstDoctor = Doctor::first();
            $firstDepartment = Department::first();

            OutpatientVisit::create([
                'visit_number' => 'RJ-'.now()->format('Ymd').'-001',
                'patient_id' => $firstPatient->id,
                'department_id' => $firstDepartment->id,
                'doctor_id' => $firstDoctor->id,
                'visit_date' => now()->setHour(9)->setMinute(0),
                'complaint' => 'Demam dan batuk 3 hari',
                'diagnosis' => null,
                'status' => 'waiting',
            ]);

            OutpatientVisit::create([
                'visit_number' => 'RJ-'.now()->format('Ymd').'-002',
                'patient_id' => Patient::skip(1)->first()?->id ?? $firstPatient->id,
                'department_id' => $firstDepartment->id,
                'doctor_id' => $firstDoctor->id,
                'visit_date' => now()->setHour(10)->setMinute(30),
                'complaint' => 'Kontrol pasca rawat inap',
                'diagnosis' => 'Observasi post-op hari ke-7, luka membaik',
                'status' => 'completed',
            ]);
        }

        if (InpatientVisit::count() === 0 && Patient::count() > 0) {
            InpatientVisit::create([
                'admission_number' => 'RI-'.now()->format('Ymd').'-001',
                'patient_id' => Patient::first()->id,
                'doctor_id' => Doctor::first()->id,
                'room_class' => 'Kelas 2',
                'room_number' => 'B-08',
                'admission_date' => now()->subDay(),
                'diagnosis' => 'Demam berdarah dengue grade II, observasi ketat',
                'status' => 'admitted',
                'notes' => 'Pantau hematokrit & trombosit per 8 jam',
            ]);
        }

        // ===== 13. Halaman Statis =====
        if (Page::count() === 0) {
            Page::create([
                'title' => 'Kebijakan Privasi',
                'content' => '<p>RS Medika Service menghormati privasi Anda. Seluruh data pribadi dan rekam medis Anda dijaga kerahasiaannya sesuai dengan Undang-Undang Nomor 17 Tahun 2023 tentang Kesehatan dan Permenkes terkait rekam medis elektronik.</p><h3>Penggunaan Data</h3><p>Data yang dikumpulkan melalui formulir janji temu dan pendaftaran hanya digunakan untuk keperluan pelayanan kesehatan Anda dan tidak dibagikan ke pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan oleh hukum.</p><h3>Keamanan</h3><p>Data tersimpan di server yang terenkripsi dengan kontrol akses berbasis peran (role-based access).</p>',
                'meta_description' => 'Kebijakan privasi dan perlindungan data pribadi di RS Medika Service.',
                'is_published' => true,
            ]);

            Page::create([
                'title' => 'FAQ - Pertanyaan Umum',
                'content' => '<h3>Bagaimana cara membuat janji temu?</h3><p>Pilih menu Janji Temu Online, pilih poli dan dokter, tentukan tanggal, isi data pasien, lalu kirim. Anda akan menerima kode booking.</p><h3>Apakah bisa tanpa janji temu?</h3><p>Bisa, namun pasien dengan janji temu diutamakan sesuai jam praktik dokter.</p><h3>Apakah menerima BPJS?</h3><p>Ya, kami bekerja sama dengan BPJS Kesehatan dan berbagai asuransi swasta.</p><h3>Jam berkunjung rawat inap?</h3><p>Pagi 11.00–13.00 dan sore 17.00–20.00 WIB, maksimal 2 penunggu.</p>',
                'meta_description' => 'Pertanyaan yang sering diajukan seputar janji temu, BPJS, dan kunjungan rawat inap.',
                'is_published' => true,
            ]);
        }
        // ===== 14. Foto Dokter & Thumbnail Artikel (dari aset lokal Unsplash, lisensi bebas) =====
        $media = app(MediaUploadService::class);

        $doctorPhotos = [
            'dr. Rendra Wijaya, Sp.PD' => 'doctor-rendra-wijaya.jpg',
            'dr. Bimo Aditama, Sp.JP' => 'doctor-bimo-aditama.jpg',
            'dr. Fajar Ramadhan, Sp.M' => 'doctor-fajar-ramadhan.jpg',
            'dr. Gilang Pratama, Sp.KG' => 'doctor-gilang-pratama.jpg',
            'dr. Yoga Saputra, Sp.PD' => 'doctor-yoga-saputra.jpg',
            'dr. Sinta Maharani, Sp.A' => 'doctor-sinta-maharani.jpg',
            'dr. Laras Puspita, Sp.OG' => 'doctor-laras-puspita.jpg',
            'dr. Dewi Anggraini, Sp.THT-KL' => 'doctor-dewi-anggraini.jpg',
            'dr. Nadia Putri, Sp.B' => 'doctor-nadia-putri.jpg',
            'dr. Amanda Kirana, Sp.A' => 'doctor-amanda-kirana.jpg',
        ];

        foreach ($doctorPhotos as $name => $file) {
            $doctor = Doctor::query()->where('name', $name)->whereNull('photo')->first();
            $source = public_path('generated/doctors/'.$file);
            if ($doctor && is_file($source)) {
                $doctor->update(['photo' => $media->storeAsWebpCropped(new UploadedFile($source, $file, 'image/jpeg', null, true), 'doctors', 480, 600, quality: 82)]);
            }
        }

        $articleThumbnails = [
            'Kenali Tanda-Tanda Serangan Jantung Sejak Dini' => 'article-1.jpg',
            'Panduan Imunisasi Lengkap untuk Bayi dan Balita' => 'article-2.jpg',
            '5 Kebiasaan Sederhana Menjaga Kesehatan Gigi dan Mulut' => 'article-3.jpg',
            'RS Medika Service Raih Akreditasi Paripurna KARS' => 'article-4.jpg',
            'Layanan MCU Korporat Kini Tersedia Setiap Sabtu' => 'article-5.jpg',
            'Paket Persalinan Normal Mulai Rp 7,5 Juta' => 'article-6.jpg',
        ];

        foreach ($articleThumbnails as $title => $file) {
            $article = Article::query()->where('title', $title)->whereNull('thumbnail')->first();
            $source = public_path('generated/articles/'.$file);
            if ($article && is_file($source)) {
                $article->update(['thumbnail' => $media->storeAsWebpCropped(new UploadedFile($source, $file, 'image/jpeg', null, true), 'articles', 880, 550, quality: 80)]);
            }
        }
    }
}
