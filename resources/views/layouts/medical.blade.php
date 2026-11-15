@php
    use App\Domains\Content\Models\SiteSetting;
    $hospitalName = SiteSetting::get('hospital_name', config('app.name'));
    $pageTitle = isset($title) && $title ? $title . ' | ' . $hospitalName : $hospitalName;
    $metaDesc = $metaDescription ?? SiteSetting::get('meta_description', 'Rumah sakit dengan layanan rawat jalan, rawat inap, dan poliklinik spesialis.');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $hospitalName }}">
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('generated/favicon.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('template_compro/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_compro/assets/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_compro/assets/css/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_compro/assets/css/font-awesome-all.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_compro/assets/css/flaticon.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_compro/assets/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_compro/assets/css/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_compro/assets/css/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_compro/assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('template_compro/assets/css/responsive.css') }}" />
    <link rel="stylesheet" href="{{ asset('generated/compro-overrides.css') }}" />
    @php
        $hospitalLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Hospital',
            'name' => $hospitalName,
            'address' => SiteSetting::get('address', 'Jl. Kesehatan Raya No. 1, Jakarta Selatan 12140'),
            'telephone' => SiteSetting::get('igd_phone', '0858 1704 8266'),
            'url' => url('/'),
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($hospitalLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
</head>
<body>

    <!-- Preloader -->
    <div class="loader js-preloader" id="preloader">
        <div></div>
        <div></div>
        <div></div>
    </div>

    <div class="page-wrapper">

        @persist('site-navbar')
        @php
            $navLinks = [
                'home' => 'Beranda',
                'about' => 'Tentang',
                'doctors.index' => 'Dokter',
                'articles.index' => 'Artikel',
                'contact' => 'Kontak',
            ];
            $igdHref = 'tel:'.preg_replace('/\D/', '', SiteSetting::get('igd_phone', '085817048266'));
        @endphp
        <header class="rs-navbar">
            <div class="container rs-navbar__inner">
                <a class="rs-logo" href="{{ route('home') }}" wire:navigate aria-label="{{ $hospitalName }}">
                    <img src="{{ asset('generated/logo-dark.png') }}" alt="{{ $hospitalName }}">
                </a>

                <nav class="rs-menu" aria-label="Menu utama">
                    @foreach ($navLinks as $route => $label)
                        <a href="{{ route($route) }}" wire:navigate @class(['active' => request()->routeIs($route)])>{{ $label }}</a>
                    @endforeach
                </nav>

                <div class="rs-actions">
                    <a class="rs-call" href="{{ $igdHref }}" aria-label="Telepon IGD">
                        <span class="rs-call__icon"><i class="fas fa-phone-alt"></i></span>
                        <span class="rs-call__text">
                            <small>IGD 24 Jam</small>
                            <strong>{{ SiteSetting::get('igd_phone', '0858 1704 8266') }}</strong>
                        </span>
                    </a>
                    <a class="thm-btn thm-btn--sm" href="{{ route('appointment') }}" wire:navigate>
                        <i class="fas fa-calendar-check"></i> Janji Temu
                    </a>
                    <button class="rs-burger" type="button" id="rsBurger" aria-label="Buka menu" aria-expanded="false" aria-controls="rsMobilePanel">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>

            {{-- Panel mobile --}}
            <div class="rs-mobile-panel" id="rsMobilePanel" hidden>
                <nav aria-label="Menu mobile">
                    @foreach ($navLinks as $route => $label)
                        <a class="rs-mlink @if(request()->routeIs($route)) active @endif" href="{{ route($route) }}" wire:navigate>
                            {{ $label }} <i class="fas fa-chevron-right"></i>
                        </a>
                    @endforeach
                </nav>
                <div class="rs-mobile-cta">
                    <a class="thm-btn" href="{{ route('appointment') }}" wire:navigate><i class="fas fa-calendar-check"></i> Janji Temu</a>
                    <a class="thm-btn thm-btn--danger" href="{{ $igdHref }}"><i class="fas fa-phone-alt"></i> IGD 24 Jam</a>
                </div>
            </div>
        </header>
        @endpersist

        {{ $slot }}

        @persist('site-footer')
        <footer class="site-footer">
            <div class="site-footer__top">
                <div class="container">
                    <div class="site-footer__top-inner">
                        <div class="row">
                            <div class="col-xl-4 col-lg-7 col-md-9">
                                <div class="footer-widget__about">
                                    <div class="footer-widget__about-logo">
                                        <a href="{{ route('home') }}" wire:navigate><img src="{{ asset('generated/logo-light.png') }}" alt="{{ $hospitalName }}" style="height: 44px; width: auto;"></a>
                                    </div>
                                    <p class="footer-widget__about-text">{{ SiteSetting::get('about_short', 'Melayani dengan sepenuh hati, profesional, dan terpercaya untuk kesehatan Anda dan keluarga.') }}</p>
                                    <div class="footer-widget__social">
                                        @if(SiteSetting::get('facebook'))<a href="{{ SiteSetting::get('facebook') }}" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>@endif
                                        @if(SiteSetting::get('instagram'))<a href="{{ SiteSetting::get('instagram') }}" aria-label="Instagram"><i class="fab fa-instagram"></i></a>@endif
                                        @if(SiteSetting::get('tiktok'))<a href="{{ SiteSetting::get('tiktok') }}" aria-label="TikTok"><svg viewBox="0 0 448 512" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M448 209.91a210.06 210.06 0 0 1-122.77-39.25v178.72A162.55 162.55 0 0 1 185 512C106.44 512 42.65 448.21 42.65 369.69S106.44 227.32 185 227.32c14.5 0 28.41 2.55 41.42 7.24v87.27a79.23 79.23 0 0 0-41.42-12.33c-44.15 0-79.72 35.57-79.72 79.69s35.57 79.7 79.72 79.7 79.4-35.56 79.4-79.7V0h88.06a163.3 163.3 0 0 0 96.03 96.38V210.1a210.06 210.06 0 0 1-1.03-.18z"/></svg></a>@endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="footer-widget__content-box">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-6">
                                            <div class="footer-widget__links">
                                                <h4 class="footer-widget__title">Link Cepat</h4>
                                                <ul class="footer-widget__links-list list-unstyled">
                                                    <li><a href="{{ route('home') }}" wire:navigate>Beranda</a></li>
                                                    <li><a href="{{ route('about') }}" wire:navigate>Tentang Kami</a></li>
                                                    <li><a href="{{ route('doctors.index') }}" wire:navigate>Cari Dokter</a></li>
                                                    <li><a href="{{ route('appointment') }}" wire:navigate>Janji Temu</a></li>
                                                    <li><a href="{{ route('articles.index') }}" wire:navigate>Artikel & Berita</a></li>
                                                    <li><a href="{{ route('contact') }}" wire:navigate>Kontak</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-6">
                                            <div class="footer-widget__services">
                                                <h4 class="footer-widget__title">Poliklinik</h4>
                                                <ul class="footer-widget__links-list list-unstyled">
                                                    @foreach (\App\Domains\Medical\Models\Department::where('is_active', true)->orderBy('name')->take(6)->get() as $dept)
                                                        <li><a href="{{ route('doctors.index') }}?poli={{ $dept->id }}" wire:navigate>{{ $dept->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-6">
                                            <div class="footer-widget__office">
                                                <h3 class="footer-widget__title">Kontak & IGD</h3>
                                                <ul class="footer-widget__office-list list-unstyled">
                                                    <li>
                                                        <p>Alamat</p>
                                                        <h5>{{ SiteSetting::get('address', 'Jl. Kesehatan Raya No. 1, Jakarta') }}</h5>
                                                    </li>
                                                    <li>
                                                        <p>IGD 24 Jam</p>
                                                        <h5><a href="tel:{{ preg_replace('/\D/', '', SiteSetting::get('igd_phone', '085817048266')) }}">{{ SiteSetting::get('igd_phone', '0858 1704 8266') }}</a></h5>
                                                    </li>
                                                    <li>
                                                        <p>Telepon / WhatsApp</p>
                                                        <h5><a href="https://wa.me/{{ preg_replace('/\D/', '', SiteSetting::get('whatsapp', '6285817048266')) }}">{{ SiteSetting::get('phone', '0858 1704 8266') }}</a></h5>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-footer__bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="site-footer__bottom-inner">
                                <div class="site-footer__copyright">
                                    <p class="site-footer__copyright-text">© {{ date('Y') }} {{ $hospitalName }}. Seluruh hak cipta dilindungi.</p>
                                </div>
                                <div class="site-footer__bottom-menu-box">
                                    <ul class="list-unstyled site-footer__bottom-menu">
                                        <li><a href="{{ route('appointment') }}" wire:navigate>Janji Temu Online</a></li>
                                        <li><a href="{{ route('login') }}">Login Admin</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        @endpersist

    </div><!-- /.page-wrapper -->

    <a href="#" data-target="html" class="scroll-to-target scroll-to-top" aria-label="Kembali ke atas">
        <span class="scroll-to-top__wrapper"><span class="scroll-to-top__inner"></span></span>
        <span class="scroll-to-top__text"> Go Back Top</span>
    </a>

    <script src="{{ asset('template_compro/assets/js/jquery-latest.js') }}"></script>
    <script src="{{ asset('template_compro/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template_compro/assets/js/aos.js') }}"></script>
    <script>
        // Re-inisialisasi UI template setelah perpindahan halaman SPA (wire:navigate)
        function initTemplateUi() {
            const loader = document.getElementById('preloader');
            if (loader) loader.style.display = 'none';

            if (window.AOS && typeof AOS.init === 'function') {
                try { AOS.init({ duration: 900, once: true }); } catch (e) { /* abaikan jika gagal */ }
            }
        }

        // Toggle menu mobile (absolute dropdown di bawah fixed navbar)
        document.addEventListener('click', function (e) {
            const burger = e.target.closest('#rsBurger');
            if (burger) {
                e.preventDefault();
                const panel = document.getElementById('rsMobilePanel');
                if (panel) {
                    panel.hidden = !panel.hidden;
                    burger.setAttribute('aria-expanded', String(!panel.hidden));
                }
                return;
            }
            const panel = document.getElementById('rsMobilePanel');
            if (panel && !panel.hidden && !e.target.closest('.rs-mobile-panel') && !e.target.closest('#rsBurger')) {
                panel.hidden = true;
                document.getElementById('rsBurger')?.setAttribute('aria-expanded', 'false');
            }
            const scrollTop = e.target.closest('.scroll-to-top');
            if (scrollTop) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        // Sinkronkan status "aktif" menu navbar dengan halaman berjalan (setelah wire:navigate)
        const NAV_MATCHERS = [
            { prefix: '/tentang', key: 'about' },
            { prefix: '/dokter', key: 'doctors.index' },
            { prefix: '/artikel', key: 'articles.index' },
            { prefix: '/kontak', key: 'contact' },
            { prefix: '/', key: 'home', exact: true },
        ];
        function syncNavActive() {
            let path = window.location.pathname.replace(/\/+$/, '');
            if (path === '') path = '/';
            document.querySelectorAll('.rs-menu a[href]').forEach(function (a) {
                const url = new URL(a.getAttribute('href'), window.location.origin);
                let p = url.pathname.replace(/\/+$/, '');
                if (p === '') p = '/';
                const match = path === p || (!NAV_MATCHERS.find(m => m.exact) && p !== '/' && path.startsWith(p + '/'));
                a.classList.toggle('active', match);
            });
            document.querySelectorAll('.rs-mlink').forEach(function (a) {
                const url = new URL(a.getAttribute('href'), window.location.origin);
                let p = url.pathname.replace(/\/+$/, '');
                if (p === '') p = '/';
                const match = path === p || (p !== '/' && path.startsWith(p + '/'));
                a.classList.toggle('active', match);
            });
            // tutup panel mobile setelah berpindah halaman
            const panel = document.getElementById('rsMobilePanel');
            if (panel) panel.hidden = true;
            document.getElementById('rsBurger')?.setAttribute('aria-expanded', 'false');
        }
        document.addEventListener('DOMContentLoaded', syncNavActive);
        document.addEventListener('livewire:navigated', syncNavActive);

        document.addEventListener('DOMContentLoaded', initTemplateUi);
        document.addEventListener('livewire:navigated', initTemplateUi);
        window.addEventListener('load', initTemplateUi);
    </script>
</body>
</html>
