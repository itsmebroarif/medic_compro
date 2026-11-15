{{-- Sidebar admin (AdminLTE v4) --}}
@php
    use App\Domains\Content\Models\SiteSetting;
    $hospitalName = SiteSetting::get('hospital_name', config('app.name'));
@endphp
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link" wire:navigate>
            <img src="{{ asset('generated/favicon.png') }}" alt="{{ $hospitalName }}" class="brand-image opacity-75 shadow" style="object-fit: contain; background: #fff; padding: 2px; border-radius: 8px;" />
            <span class="brand-text fw-light">{{ $hospitalName }}</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2" aria-label="Menu utama">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" id="navigation">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.dashboard')])>
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @hasanyrole('super-admin|admin-pendaftaran')
                <li class="nav-header">FRONT OFFICE / PELAYANAN</li>
                <li class="nav-item">
                    <a href="{{ route('admin.appointments') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.appointments')])>
                        <i class="nav-icon bi bi-calendar2-check"></i><p>Janji Temu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.outpatient') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.outpatient')])>
                        <i class="nav-icon bi bi-person-walking"></i><p>Rawat Jalan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.inpatient') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.inpatient')])>
                        <i class="nav-icon bi bi-hospital"></i><p>Rawat Inap</p>
                    </a>
                </li>

                <li class="nav-header">MASTER DATA</li>
                <li class="nav-item">
                    <a href="{{ route('admin.departments') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.departments')])>
                        <i class="nav-icon bi bi-diagram-3"></i><p>Data Master Poli</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.doctors') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.doctors')])>
                        <i class="nav-icon bi bi-person-badge"></i><p>Data Dokter</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.schedules') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.schedules')])>
                        <i class="nav-icon bi bi-clock-history"></i><p>Jadwal Praktik</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.employees') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.employees')])>
                        <i class="nav-icon bi bi-people"></i><p>Data Master Karyawan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.patients') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.patients')])>
                        <i class="nav-icon bi bi-person-vcard"></i><p>Data Master Pasien</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.medicines') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.medicines')])>
                        <i class="nav-icon bi bi-capsule"></i><p>Data Master Obat</p>
                    </a>
                </li>
                @endhasanyrole

                @hasanyrole('super-admin|admin-konten')
                <li class="nav-header">KONTEN WEB</li>
                <li class="nav-item">
                    <a href="{{ route('admin.articles') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.articles')])>
                        <i class="nav-icon bi bi-newspaper"></i><p>Artikel / Berita</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.article-categories') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.article-categories')])>
                        <i class="nav-icon bi bi-tags"></i><p>Kategori Artikel</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pages') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.pages')])>
                        <i class="nav-icon bi bi-file-earmark-richtext"></i><p>Halaman Web</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.site-content') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.site-content')])>
                        <i class="nav-icon bi bi-layout-text-window-reverse"></i><p>Konten Website</p>
                    </a>
                </li>
                @endhasanyrole

                @role('super-admin')
                <li class="nav-header">PENGATURAN</li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.settings')])>
                        <i class="nav-icon bi bi-gear"></i><p>Pengaturan RS</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" wire:navigate @class(['nav-link', 'active' => request()->routeIs('admin.users')])>
                        <i class="nav-icon bi bi-person-lock"></i><p>Pengguna &amp; Role</p>
                    </a>
                </li>
                @endrole
            </ul>
        </nav>
    </div>
</aside>
