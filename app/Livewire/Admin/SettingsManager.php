<?php

namespace App\Livewire\Admin;

use App\Domains\Content\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin', ['title' => 'Pengaturan RS'])]
class SettingsManager extends Component
{
    /** @var array<string, string> */
    public array $settings = [];

    public string $flashMessage = '';

    private const DEFAULTS = [
        // Umum
        'hospital_name' => 'RS Medika Service',
        'tagline' => 'Layanan kesehatan terpadu: rawat jalan, rawat inap, dan IGD 24 jam.',
        'about_short' => 'Melayani dengan sepenuh hati, profesional, dan terpercaya untuk kesehatan Anda dan keluarga.',
        'about' => 'Kami adalah rumah sakit umum yang menyediakan layanan kesehatan terpadu mulai dari rawat jalan, rawat inap, poliklinik spesialis, laboratorium, radiologi, hingga instalasi gawat darurat (IGD) 24 jam.',
        'meta_description' => 'Rumah sakit dengan layanan rawat jalan, rawat inap, poliklinik spesialis, dan IGD 24 jam.',
        // Kontak
        'igd_phone' => '021 5550 1234',
        'phone' => '021 5550 1235',
        'whatsapp' => '6281234567890',
        'email' => 'info@medicservice.co.id',
        'address' => 'Jl. Kesehatan Raya No. 1, Jakarta',
        'hours' => 'Senin–Sabtu, 08.00–20.00 WIB (IGD 24 Jam)',
        'maps_embed' => '',
        // Sosial Media
        'facebook' => '',
        'instagram' => '',
        'twitter' => '',
        'linkedin' => '',
        'youtube' => '',
    ];

    private const GROUPS = [
        'general' => ['label' => 'Umum', 'keys' => ['hospital_name', 'tagline', 'about_short', 'about', 'meta_description']],
        'contact' => ['label' => 'Kontak & Lokasi', 'keys' => ['igd_phone', 'phone', 'whatsapp', 'email', 'address', 'hours', 'maps_embed']],
        'social' => ['label' => 'Sosial Media', 'keys' => ['facebook', 'instagram', 'twitter', 'linkedin', 'youtube']],
    ];

    public function mount(): void
    {
        $stored = SiteSetting::query()->pluck('value', 'key')->all();

        $this->settings = collect(self::DEFAULTS)
            ->map(fn ($default, $key) => $stored[$key] ?? $default)
            ->all();
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'settings.hospital_name.required' => 'Nama rumah sakit wajib diisi.',
            'settings.email.email' => 'Format email tidak valid.',
        ];
    }

    protected function rules(): array
    {
        return [
            'settings.hospital_name' => ['required', 'string', 'min:3', 'max:150'],
            'settings.tagline' => ['nullable', 'string', 'max:255'],
            'settings.about_short' => ['nullable', 'string', 'max:500'],
            'settings.about' => ['nullable', 'string', 'max:5000'],
            'settings.meta_description' => ['nullable', 'string', 'max:255'],
            'settings.igd_phone' => ['nullable', 'string', 'max:30'],
            'settings.phone' => ['nullable', 'string', 'max:30'],
            'settings.whatsapp' => ['nullable', 'string', 'max:30'],
            'settings.email' => ['nullable', 'email', 'max:150'],
            'settings.address' => ['nullable', 'string', 'max:500'],
            'settings.hours' => ['nullable', 'string', 'max:150'],
            'settings.maps_embed' => ['nullable', 'string', 'max:2000'],
            'settings.facebook' => ['nullable', 'string', 'max:255'],
            'settings.instagram' => ['nullable', 'string', 'max:255'],
            'settings.twitter' => ['nullable', 'string', 'max:255'],
            'settings.linkedin' => ['nullable', 'string', 'max:255'],
            'settings.youtube' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function saveGroup(string $group): void
    {
        abort_unless(array_key_exists($group, self::GROUPS), 404);

        $this->validate();

        foreach (self::GROUPS[$group]['keys'] as $key) {
            SiteSetting::set($key, $this->settings[$key] ?? null, $group);
        }

        SiteSetting::flushCache();
        $this->flashMessage = 'Pengaturan "'.self::GROUPS[$group]['label'].'" berhasil disimpan.';
    }

    public function render()
    {
        return view('livewire.admin.settings-manager', ['groups' => self::GROUPS]);
    }
}
