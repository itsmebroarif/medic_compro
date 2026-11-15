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
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('generated/favicon.png') }}" />

    {{-- Theme init (cegah flash tema yang salah) --}}
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

    {{-- Aset lokal (tanpa CDN) --}}
    <link rel="stylesheet" href="{{ asset('generated/vendor/source-sans-3.css') }}" />
    <link rel="stylesheet" href="{{ asset('generated/vendor/overlayscrollbars.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('generated/vendor/bootstrap-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_admin/css/adminlte.css') }}" />
    <link rel="stylesheet" href="{{ asset('generated/admin-overrides.css') }}" />
    <link rel="stylesheet" href="{{ asset('generated/vendor/summernote-bs5.min.css') }}" />
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    <x-admin.navbar />

    @persist('admin-sidebar')
        <x-admin.sidebar />
    @endpersist

    <main class="app-main">
        <x-admin.content-header :title="$title ?? 'Dashboard'" />

        <div class="app-content">
            <div class="container-fluid">
                {{ $slot }}
            </div>
        </div>
    </main>

    <x-admin.footer />
</div>

{{-- Aset lokal (tanpa CDN) --}}
<script src="{{ asset('generated/vendor/overlayscrollbars.browser.es6.min.js') }}"></script>
<script src="{{ asset('generated/vendor/popper.min.js') }}"></script>
<script src="{{ asset('generated/vendor/bootstrap.min.js') }}"></script>
<script src="{{ asset('template_admin/js/adminlte.js') }}"></script>
<script src="{{ asset('generated/vendor/jquery.min.js') }}"></script>
<script src="{{ asset('generated/vendor/summernote-bs5.min.js') }}"></script>
<script src="{{ asset('generated/vendor/chart.umd.min.js') }}"></script>
<script>
    // Sinkronkan item menu admin yang aktif saat navigasi SPA (wire:navigate)
    function syncAdminNav() {
        const path = window.location.pathname.replace(/\/+$/, '');
        document.querySelectorAll('#navigation a.nav-link').forEach(function (a) {
            const url = new URL(a.getAttribute('href'), window.location.origin);
            let p = url.pathname.replace(/\/+$/, '');
            a.classList.toggle('active', p === path);
        });
    }
    document.addEventListener('DOMContentLoaded', syncAdminNav);
    document.addEventListener('livewire:navigated', syncAdminNav);

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
                    ['insert', ['link', 'picture', 'table']],
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
