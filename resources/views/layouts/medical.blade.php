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
            'telephone' => SiteSetting::get('igd_phone', '021 5550 1234'),
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
        <header class="main-header">
            <nav class="main-menu">
                <div class="main-menu__wrapper">
                    <div class="container">
                        <div class="main-menu__wrapper-inner">
                            <div class="main-menu__logo">
                                <a href="{{ route('home') }}" wire:navigate aria-label="{{ $hospitalName }}">
                                    <img src="{{ asset('generated/logo-dark.png') }}" alt="{{ $hospitalName }}" style="height: 46px; width: auto;">
                                </a>
                            </div>
                            <div class="main-menu__main-menu-box-outer">
                                <div class="main-menu__main-menu-box-outer-top">
                                    <ul class="list-unstyled main-menu__contact-list">
                                        <li>
                                            <div class="icon"><i class="icon-phone-call"></i></div>
                                            <div class="text">
                                                <p>IGD 24 Jam: <a href="tel:{{ preg_replace('/\D/', '', SiteSetting::get('igd_phone', '02155501234')) }}">{{ SiteSetting::get('igd_phone', '021 5550 1234') }}</a></p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="icon"><i class="icon-email"></i></div>
                                            <div class="text">
                                                <p><a href="mailto:{{ SiteSetting::get('email', 'info@medicservice.co.id') }}">{{ SiteSetting::get('email', 'info@medicservice.co.id') }}</a></p>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="main-menu__social">
                                        @if(SiteSetting::get('facebook'))<a href="{{ SiteSetting::get('facebook') }}"><i class="icon-facebook-app-symbol"></i></a>@endif
                                        @if(SiteSetting::get('twitter'))<a href="{{ SiteSetting::get('twitter') }}"><i class="icon-twitter"></i></a>@endif
                                        @if(SiteSetting::get('instagram'))<a href="{{ SiteSetting::get('instagram') }}"><i class="icon-instagram"></i></a>@endif
                                        @if(SiteSetting::get('linkedin'))<a href="{{ SiteSetting::get('linkedin') }}"><i class="icon-linkedin"></i></a>@endif
                                    </div>
                                </div>
                                <div class="main-menu__main-menu-box-content">
                                    <div class="main-menu__main-menu-box">
                                        <a href="#" class="mobile-nav__toggler" aria-label="Buka menu"><i class="fa fa-bars"></i></a>
                                        <ul class="main-menu__list">
                                            <li><a href="{{ route('home') }}" wire:navigate>Beranda</a></li>
                                            <li><a href="{{ route('about') }}" wire:navigate>Tentang</a></li>
                                            <li><a href="{{ route('doctors.index') }}" wire:navigate>Dokter</a></li>
                                            <li><a href="{{ route('articles.index') }}" wire:navigate>Artikel</a></li>
                                            <li><a href="{{ route('contact') }}" wire:navigate>Kontak</a></li>
                                        </ul>
                                    </div>
                                    <div class="main-menu__search-cart-btn-box">
                                        <a href="{{ route('appointment') }}" wire:navigate class="thm-btn" style="padding: 12px 22px;">
                                            <span class="fas fa-calendar-check"></span>
                                            Janji Temu
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <div class="stricky-header stricked-menu main-menu"><div class="sticky-header__content"></div></div>
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
                                        @if(SiteSetting::get('facebook'))<a href="{{ SiteSetting::get('facebook') }}" aria-label="Facebook"><i class="fab fa-facebook"></i></a>@endif
                                        @if(SiteSetting::get('instagram'))<a href="{{ SiteSetting::get('instagram') }}" aria-label="Instagram"><i class="fab fa-instagram"></i></a>@endif
                                        @if(SiteSetting::get('twitter'))<a href="{{ SiteSetting::get('twitter') }}" aria-label="Twitter"><i class="fab fa-twitter"></i></a>@endif
                                        @if(SiteSetting::get('linkedin'))<a href="{{ SiteSetting::get('linkedin') }}" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>@endif
                                        @if(SiteSetting::get('youtube'))<a href="{{ SiteSetting::get('youtube') }}" aria-label="YouTube"><i class="fab fa-youtube"></i></a>@endif
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
                                                        <h5><a href="tel:{{ preg_replace('/\D/', '', SiteSetting::get('igd_phone', '02155501234')) }}">{{ SiteSetting::get('igd_phone', '021 5550 1234') }}</a></h5>
                                                    </li>
                                                    <li>
                                                        <p>Telepon / WhatsApp</p>
                                                        <h5><a href="https://wa.me/{{ preg_replace('/\D/', '', SiteSetting::get('whatsapp', '6281234567890')) }}">{{ SiteSetting::get('phone', '021 5550 1235') }}</a></h5>
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

    <!-- Mobile Navigation -->
    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>
            <div class="logo-box">
                <a href="{{ route('home') }}" wire:navigate aria-label="{{ $hospitalName }}"><img src="{{ asset('generated/logo-light.png') }}" width="190" alt="{{ $hospitalName }}" /></a>
            </div>
            <ul class="mobile-nav__contact list-unstyled" style="padding: 0 30px;">
                <li><i class="fa fa-envelope"></i><a href="mailto:{{ SiteSetting::get('email', 'info@medicservice.co.id') }}">{{ SiteSetting::get('email', 'info@medicservice.co.id') }}</a></li>
                <li><i class="fas fa-phone"></i><a href="tel:{{ preg_replace('/\D/', '', SiteSetting::get('igd_phone', '02155501234')) }}">{{ SiteSetting::get('igd_phone', '021 5550 1234') }}</a></li>
            </ul>
            <ul class="list-unstyled" style="padding: 0 30px; line-height: 2.4; font-size: 18px;">
                <li><a href="{{ route('home') }}" wire:navigate>Beranda</a></li>
                <li><a href="{{ route('about') }}" wire:navigate>Tentang Kami</a></li>
                <li><a href="{{ route('doctors.index') }}" wire:navigate>Cari Dokter</a></li>
                <li><a href="{{ route('articles.index') }}" wire:navigate>Artikel & Berita</a></li>
                <li><a href="{{ route('appointment') }}" wire:navigate>Janji Temu</a></li>
                <li><a href="{{ route('contact') }}" wire:navigate>Kontak</a></li>
            </ul>
        </div>
    </div>

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

        // Toggle mobile nav (delegated agar tetap bekerja setelah wire:navigate)
        document.addEventListener('click', function (e) {
            const toggler = e.target.closest('.mobile-nav__toggler');
            if (toggler) {
                e.preventDefault();
                const wrapper = document.querySelector('.mobile-nav__wrapper');
                if (wrapper) wrapper.classList.toggle('expanded');
                return;
            }
            const scrollTop = e.target.closest('.scroll-to-top');
            if (scrollTop) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        document.addEventListener('DOMContentLoaded', initTemplateUi);
        document.addEventListener('livewire:navigated', initTemplateUi);
        window.addEventListener('load', initTemplateUi);
    </script>
</body>
</html>
