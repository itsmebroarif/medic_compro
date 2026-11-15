@php
    use App\Domains\Content\Models\SiteSetting;
    $hospitalName = SiteSetting::get('hospital_name', config('app.name'));
    $pageTitle = isset($title) && $title ? $title . ' | Admin ' . $hospitalName : 'Admin ' . $hospitalName;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $pageTitle }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('template_compro/assets/images/favicons/favicon-32x32.png') }}" />

    <script>
        (() => {
            'use strict';
            const root = document.documentElement;
            if (root.getAttribute('data-lte-color-mode') === 'off') return;
            let stored = null;
            try { stored = localStorage.getItem('lte-theme'); } catch {}
            const authored = root.getAttribute('data-bs-theme');
            let resolved = 'light';
            if (stored === 'dark' || stored === 'light') resolved = stored;
            else if (authored === 'dark' || authored === 'light') resolved = authored;
            else if (matchMedia('(prefers-color-scheme: dark)').matches) resolved = 'dark';
            root.setAttribute('data-bs-theme', resolved);
        })();
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.3.0/index.css" media="print" onload="this.media = 'all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('template_admin/css/adminlte.css') }}" />
    <!-- Summernote (WYSIWYG Editor) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.9.2/dist/summernote-bs5.min.css" crossorigin="anonymous" />
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    <!--begin::Header-->
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="{{ route('home') }}" target="_blank" class="nav-link">
                        <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i> Lihat Situs
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown user-menu">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span class="d-none d-md-inline">{{ auth()->user()?->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text small text-secondary">{{ strtoupper(str_replace('-', ' ', auth()->user()?->getRoleNames()->first() ?? 'user')) }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!--end::Header-->

    @persist('admin-sidebar')
    <!--begin::Sidebar-->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <img src="{{ asset('template_compro/assets/images/resources/logo-2.png') }}" alt="{{ $hospitalName }}" class="brand-image opacity-75 shadow" style="object-fit: contain; background: #fff; padding: 2px;" />
                <span class="brand-text fw-light">{{ $hospitalName }}</span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2" aria-label="Menu utama">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" id="navigation">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    @hasanyrole('super-admin|admin-pendaftaran')
                    <li class="nav-header">FRONT OFFICE / PELAYANAN</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.appointments') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.appointments') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-calendar2-check"></i><p>Janji Temu</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.outpatient') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.outpatient') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-walking"></i><p>Rawat Jalan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.inpatient') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.inpatient') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-hospital"></i><p>Rawat Inap</p>
                        </a>
                    </li>

                    <li class="nav-header">MASTER DATA</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.departments') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.departments') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-diagram-3"></i><p>Data Master Poli</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.doctors') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.doctors') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-badge"></i><p>Data Dokter</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.schedules') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.schedules') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clock-history"></i><p>Jadwal Praktik</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.employees') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.employees') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i><p>Data Master Karyawan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.patients') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.patients') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-vcard"></i><p>Data Master Pasien</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.medicines') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.medicines') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-capsule"></i><p>Data Master Obat</p>
                        </a>
                    </li>
                    @endhasanyrole

                    @hasanyrole('super-admin|admin-konten')
                    <li class="nav-header">KONTEN WEB</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.articles') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.articles') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-newspaper"></i><p>Artikel / Berita</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.article-categories') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.article-categories') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-tags"></i><p>Kategori Artikel</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.pages') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.pages') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-file-earmark-richtext"></i><p>Halaman Web</p>
                        </a>
                    </li>
                    @endhasanyrole

                    @role('super-admin')
                    <li class="nav-header">PENGATURAN</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-gear"></i><p>Pengaturan RS</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users') }}" wire:navigate class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-lock"></i><p>Pengguna & Role</p>
                        </a>
                    </li>
                    @endrole
                </ul>
            </nav>
        </div>
    </aside>
    <!--end::Sidebar-->
    @endpersist

    <!--begin::App Content-->
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6"><h3 class="mb-0">{{ $title ?? 'Dashboard' }}</h3></div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb float-sm-end mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $title ?? 'Dashboard' }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                {{ $slot }}
            </div>
        </div>
    </main>
    <!--end::App Content-->

    <!--begin::Footer-->
    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">{{ $hospitalName }}</div>
        <strong>Copyright &copy; {{ date('Y') }} {{ $hospitalName }}.</strong> Dikembangkan oleh <a href="#" class="text-decoration-none">Arif Permana Putrasuryana</a>.
    </footer>
    <!--end::Footer-->
</div>

<!--begin::Script-->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.16.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('template_admin/js/adminlte.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.2/dist/summernote-bs5.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js" crossorigin="anonymous"></script>
<script>
    // Inisialisasi Summernote + sinkronisasi nilainya ke state Livewire
    function initEditors() {
        if (!window.jQuery || !jQuery.fn.summernote) return;

        jQuery('.summernote').each(function () {
            const $el = jQuery(this);
            if ($el.data('summernote-ready')) return;

            const modelAttr = $el.attr('wire:model') || $el.attr('wire:model.live') || 'content';
            $el.summernote({
                height: 340,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'table', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                ],
                callbacks: {
                    onChange: function (contents) {
                        const root = $el.closest('[wire\\:id]');
                        const id = root.attr('wire:id');
                        const comp = window.Livewire && id ? window.Livewire.find(id) : null;
                        if (comp) comp.set(modelAttr, contents);
                    },
                },
            });
            $el.data('summernote-ready', true);
        });
    }

    document.addEventListener('DOMContentLoaded', initEditors);
    document.addEventListener('livewire:navigated', initEditors);
    document.addEventListener('livewire:initialized', initEditors);
</script>
</body>
</html>
