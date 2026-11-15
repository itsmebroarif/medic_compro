<?php

namespace App\Livewire\Admin;

use App\Domains\Content\Models\SiteSetting;
use App\Domains\Content\Support\SiteContent;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin', ['title' => 'Konten Website'])]
class SiteContentManager extends Component
{
    /** @var array<string, string> */
    public array $content = [];

    public string $flashMessage = '';

    /**
     * Definisi field per bagian (disimpan sebagai SiteSetting).
     *
     * @var array<string, array<string, array{label: string, type: string}>>
     */
    private const SECTIONS = [
        'home' => [
            'label' => 'Beranda (Homepage)',
            'keys' => [
                'hero_badge' => ['label' => 'Badge Hero (ikon perisai)', 'type' => 'text'],
                'hero_kicker' => ['label' => 'Teks Kecil Hero (di atas judul)', 'type' => 'text'],
                'hero_title_1' => ['label' => 'Judul Hero Baris 1', 'type' => 'text'],
                'hero_title_2' => ['label' => 'Judul Hero Baris 2 (warna teal)', 'type' => 'text'],
                'hero_subtitle' => ['label' => 'Subjudul Hero', 'type' => 'textarea'],
                'hero_cta_primary' => ['label' => 'Teks Tombol Utama (Janji Temu)', 'type' => 'text'],
                'usp1_icon' => ['label' => 'Kartu 1 — Icon', 'type' => 'icon'],
                'usp1_title' => ['label' => 'Kartu 1 — Judul', 'type' => 'text'],
                'usp1_text' => ['label' => 'Kartu 1 — Teks', 'type' => 'text'],
                'usp2_icon' => ['label' => 'Kartu 2 — Icon', 'type' => 'icon'],
                'usp2_title' => ['label' => 'Kartu 2 — Judul', 'type' => 'text'],
                'usp2_text' => ['label' => 'Kartu 2 — Teks', 'type' => 'text'],
                'usp3_icon' => ['label' => 'Kartu 3 — Icon', 'type' => 'icon'],
                'usp3_title' => ['label' => 'Kartu 3 — Judul', 'type' => 'text'],
                'usp3_text' => ['label' => 'Kartu 3 — Teks', 'type' => 'text'],
                'usp4_icon' => ['label' => 'Kartu 4 — Icon', 'type' => 'icon'],
                'usp4_title' => ['label' => 'Kartu 4 — Judul', 'type' => 'text'],
                'usp4_text' => ['label' => 'Kartu 4 — Teks', 'type' => 'text'],
                'ward_kicker' => ['label' => 'Rawat Inap — Label Kecil', 'type' => 'text'],
                'ward_title_1' => ['label' => 'Rawat Inap — Judul Baris 1', 'type' => 'text'],
                'ward_title_2' => ['label' => 'Rawat Inap — Judul Baris 2 (teal)', 'type' => 'text'],
                'ward_bullets' => ['label' => 'Rawat Inap — Daftar Keunggulan (1 baris = 1 poin)', 'type' => 'textarea'],
                'ward_button' => ['label' => 'Rawat Inap — Teks Tombol', 'type' => 'text'],
                'stat_1_label' => ['label' => 'Statistik 1 — Label', 'type' => 'text'],
                'stat_2_label' => ['label' => 'Statistik 2 — Label', 'type' => 'text'],
                'stat_3_label' => ['label' => 'Statistik 3 — Label', 'type' => 'text'],
                'stat_4_label' => ['label' => 'Statistik 4 — Label', 'type' => 'text'],
                'emergency_title' => ['label' => 'CTA Darurat — Judul', 'type' => 'text'],
                'emergency_text' => ['label' => 'CTA Darurat — Teks', 'type' => 'text'],
            ],
        ],
        'about' => [
            'label' => 'Tentang Kami',
            'keys' => [
                'vision' => ['label' => 'Visi', 'type' => 'textarea'],
                'mission' => ['label' => 'Misi (1 baris = 1 poin)', 'type' => 'textarea'],
                'about_stat_1_num' => ['label' => 'Statistik 1 — Angka', 'type' => 'text'],
                'about_stat_1_label' => ['label' => 'Statistik 1 — Label', 'type' => 'text'],
                'about_stat_2_num' => ['label' => 'Statistik 2 — Angka', 'type' => 'text'],
                'about_stat_2_label' => ['label' => 'Statistik 2 — Label', 'type' => 'text'],
                'about_stat_3_num' => ['label' => 'Statistik 3 — Angka', 'type' => 'text'],
                'about_stat_3_label' => ['label' => 'Statistik 3 — Label', 'type' => 'text'],
                'about_stat_4_num' => ['label' => 'Statistik 4 — Angka', 'type' => 'text'],
                'about_stat_4_label' => ['label' => 'Statistik 4 — Label', 'type' => 'text'],
            ],
        ],
    ];

    private const ICON_OPTIONS = [
        'fas fa-award' => 'Piala (Akreditasi)',
        'fas fa-user-md' => 'Dokter',
        'fas fa-ambulance' => 'Ambulans / IGD',
        'fas fa-file-invoice-dollar' => 'Klaim / BPJS',
        'fas fa-hospital' => 'Gedung RS',
        'fas fa-shield-alt' => 'Perisai',
        'fas fa-heartbeat' => 'Jantung',
        'fas fa-stethoscope' => 'Stetoskop',
        'fas fa-tooth' => 'Gigi',
        'fas fa-baby' => 'Anak',
        'fas fa-pills' => 'Obat',
        'fas fa-x-ray' => 'Rontgen',
        'fas fa-phone-alt' => 'Telepon',
        'fas fa-map-marker-alt' => 'Lokasi',
    ];

    public function mount(): void
    {
        $this->content = SiteContent::map();
    }

    public function save(string $group): void
    {
        abort_unless(array_key_exists($group, self::SECTIONS), 404);

        $keys = array_keys(self::SECTIONS[$group]['keys']);

        $rules = [];
        foreach ($keys as $key) {
            $rules["content.{$key}"] = ['nullable', 'string', 'max:3000'];
        }
        $this->validate($rules, ['content.*.max' => 'Maksimal 3000 karakter.']);

        foreach ($keys as $key) {
            SiteSetting::set($key, $this->content[$key] ?? null, 'content');
        }

        SiteSetting::flushCache();
        $this->flashMessage = 'Konten "'.self::SECTIONS[$group]['label'].'" berhasil disimpan dan tampil di website.';
    }

    public function render()
    {
        return view('livewire.admin.site-content-manager', [
            'sections' => self::SECTIONS,
            'iconOptions' => self::ICON_OPTIONS,
        ]);
    }
}
