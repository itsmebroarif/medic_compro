<?php

namespace App\Domains\Content\Support;

use App\Domains\Content\Models\SiteSetting;

/**
 * Definisi & akses konten website yang bisa diedit admin (Simple CMS).
 */
class SiteContent
{
    /** @var array<string, string> */
    public const DEFAULTS = [
        'hero_badge' => 'Terakreditasi KARS · Kerja Sama BPJS & Asuransi',
        'hero_kicker' => 'IGD 24 Jam',
        'hero_title_1' => 'Kesehatan Anda,',
        'hero_title_2' => 'Prioritas Kami',
        'hero_subtitle' => 'Layanan kesehatan terpadu: rawat jalan, rawat inap, poliklinik spesialis, dan IGD 24 jam.',
        'hero_cta_primary' => 'Janji Temu Online',
        'usp1_icon' => 'fas fa-award',
        'usp1_title' => 'Terakreditasi Paripurna',
        'usp1_text' => 'Standar mutu & keselamatan pasien tertinggi (KARS).',
        'usp2_icon' => 'fas fa-user-md',
        'usp2_title' => 'Dokter Spesialis',
        'usp2_text' => 'Tenaga medis berpengalaman & tersertifikasi.',
        'usp3_icon' => 'fas fa-ambulance',
        'usp3_title' => 'IGD 24 Jam',
        'usp3_text' => 'Tim gawat darurat siaga setiap saat.',
        'usp4_icon' => 'fas fa-file-invoice-dollar',
        'usp4_title' => 'BPJS & Asuransi',
        'usp4_text' => 'Kerja sama BPJS Kesehatan & asuransi swasta.',
        'ward_kicker' => 'Fasilitas Rawat Inap',
        'ward_title_1' => 'Ruangan Nyaman,',
        'ward_title_2' => 'Pemulihan Lebih Cepat',
        'ward_bullets' => "VIP & Kelas 1–3, ICU, NICU & Ruang Isolasi\nPerawat 24 jam & kunjungan dokter DPJP terjadwal\nPerlengkapan lengkap & nurse call di setiap kamar\nKantin & apotek 24 jam di dalam area RS",
        'ward_button' => 'Tanyakan Kamar Tersedia',
        'stat_1_label' => 'Dokter Spesialis & Subspesialis',
        'stat_2_label' => 'Poliklinik & Layanan',
        'stat_3_label' => 'Pasien Terdaftar',
        'stat_4_label' => 'IGD & Rawat Inap Siaga',
        'emergency_title' => 'Kondisi Gawat Darurat?',
        'emergency_text' => 'IGD kami siaga 24 jam — dokter & perawat gawat darurat tersedia setiap saat.',
        'vision' => 'Menjadi rumah sakit pilihan utama masyarakat yang unggul dalam pelayanan, pendidikan, dan penelitian kesehatan dengan semangat kemanusiaan.',
        'mission' => "Memberikan pelayanan kesehatan paripurna yang aman dan bermutu.\nMeningkatkan kompetensi SDM kesehatan secara berkelanjutan.\nMengembangkan sarana, prasarana, dan teknologi medis modern.",
        'about_stat_1_num' => '150+',
        'about_stat_1_label' => 'Dokter Spesialis & Subspesialis',
        'about_stat_2_num' => '24/7',
        'about_stat_2_label' => 'IGD & Rawat Inap Aktif',
        'about_stat_3_num' => '25.000+',
        'about_stat_3_label' => 'Pasien Tertangani',
        'about_stat_4_num' => 'ISO',
        'about_stat_4_label' => 'Terakreditasi KARS',
    ];

    /**
     * Ambil seluruh konten sebagai map key => value (dengan fallback default).
     *
     * @return array<string, string>
     */
    public static function map(): array
    {
        $stored = SiteSetting::query()->whereIn('key', array_keys(self::DEFAULTS))->pluck('value', 'key')->all();

        return collect(self::DEFAULTS)
            ->map(fn ($default, $key) => filled($stored[$key] ?? null) ? (string) $stored[$key] : $default)
            ->all();
    }

    /**
     * Ambil satu nilai konten dengan fallback default.
     */
    public static function get(string $key): string
    {
        return static::map()[$key] ?? self::DEFAULTS[$key] ?? '';
    }
}
