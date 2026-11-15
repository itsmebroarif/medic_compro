<div>
    {{-- HERO --}}
    <section class="hero-section" style="position: relative; min-height: 620px; display: flex; align-items: center; background-image: linear-gradient(rgba(9, 39, 58, .82), rgba(9, 39, 58, .82)), url('{{ asset('template_compro/assets/images/backgrounds/slider-1-1.jpg') }}'); background-size: cover; background-position: center;">
        <div class="container" style="position: relative; z-index: 1; padding: 80px 0;">
            <div class="row">
                <div class="col-lg-8 col-xl-7">
                    <p class="banner-one__sub-title" style="color: #ffc107; letter-spacing: 2px; text-transform: uppercase; font-weight: 700;">
                        IGD 24 Jam &middot; {{ $this->stats['departments'] }} Poliklinik &middot; {{ $this->stats['doctors'] }} Dokter Spesialis
                    </p>
                    <h1 class="banner-one__title" style="color: #fff; font-size: 3.4rem; line-height: 1.15;">
                        Kesehatan Anda, <span style="color: #22c1a3;">Prioritas Kami</span>
                    </h1>
                    <p class="banner-one__text" style="color: rgba(255,255,255,.85); margin: 20px 0 30px; font-size: 1.1rem;">
                        {{ \App\Domains\Content\Models\SiteSetting::get('tagline', 'Layanan kesehatan terpadu: rawat jalan, rawat inap, dan IGD 24 jam dengan tenaga medis profesional berpengalaman.') }}
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('appointment') }}" wire:navigate class="thm-btn">
                            <span class="fas fa-calendar-check"></span>
                            Janji Temu Online
                        </a>
                        <a href="tel:{{ preg_replace('/\D/', '', $this->stats['igd']) }}" class="thm-btn" style="background: #dc3545;">
                            <span class="fas fa-phone-alt"></span>
                            IGD: {{ $this->stats['igd'] }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- POLIKLINIK --}}
    <section class="services-one" style="padding: 70px 0;">
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 40px;">
                <div class="section-title__tagline-box" style="justify-content: center;">
                    <p class="section-title__tagline">Poliklinik Kami</p>
                </div>
                <h2 class="section-title__title">Layanan <span>Spesialis</span> Kami</h2>
            </div>
            <div class="row">
                @foreach ($this->departments as $department)
                    <div class="col-xl-3 col-lg-4 col-md-6" style="margin-bottom: 24px;">
                        <a href="{{ route('doctors.index') }}?poli={{ $department->id }}" wire:navigate style="text-decoration: none; color: inherit;">
                            <div class="card h-100 text-center border-0 shadow-sm" style="border-radius: 16px; padding: 22px 14px; transition: transform .2s ease;">
                                <div style="font-size: 2.2rem; color: #0d6efd; margin-bottom: 12px;"><i class="flaticon flaticon-stethoscope"></i><span class="icon-stethoscope" style="font-size: 2rem;"></span></div>
                                <h5 style="font-weight: 700;">{{ $department->name }}</h5>
                                <p class="text-muted small mb-2">{{ $department->doctors_count }} dokter</p>
                                <span class="text-primary small" style="font-weight: 600;">Lihat Dokter &rarr;</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- DOKTER --}}
    <section class="team-section" style="padding: 70px 0; background: #f6f9fc;">
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 40px;">
                <div class="section-title__tagline-box" style="justify-content: center;">
                    <p class="section-title__tagline">Tenaga Medis</p>
                </div>
                <h2 class="section-title__title">Dokter <span>Pilihan</span> Kami</h2>
            </div>
            <div class="row filter-layout">
                @foreach ($this->featuredDoctors as $doctor)
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="team-two__single">
                            <div class="team-two__img-box">
                                <div class="team-two__img">
                                    <img src="{{ $doctor->photoUrl() ?? asset('template_compro/assets/images/team/team-page-v1-' . (($doctor->id % 12) + 1) . '.jpg') }}" alt="{{ $doctor->name }}" loading="lazy">
                                </div>
                            </div>
                            <div class="team-two__content">
                                <p class="team-two__designation">{{ $doctor->department->name }}</p>
                                <h3 class="team-two__name"><a href="{{ route('doctors.show', $doctor->slug) }}" wire:navigate>{{ $doctor->name }}</a></h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center" style="margin-top: 30px;">
                <a href="{{ route('doctors.index') }}" wire:navigate class="thm-btn"><span class="fas fa-user-md"></span> Lihat Semua Dokter</a>
            </div>
        </div>
    </section>

    {{-- ARTIKEL --}}
    @if ($this->latestArticles->isNotEmpty())
        <section class="blog-section" style="padding: 70px 0;">
            <div class="container">
                <div class="section-title text-center" style="margin-bottom: 40px;">
                    <div class="section-title__tagline-box" style="justify-content: center;">
                        <p class="section-title__tagline">Edukasi & Berita</p>
                    </div>
                    <h2 class="section-title__title">Artikel <span>Terbaru</span></h2>
                </div>
                <div class="row">
                    @foreach ($this->latestArticles as $article)
                        @php $fallback = 'template_compro/assets/images/blog/blog-1-' . (($article->id % 6) + 1) . '.jpg'; @endphp
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="blog-one__single">
                                <div class="blog-one__img">
                                    <img src="{{ $article->thumbnailUrl() ?? asset($fallback) }}" alt="{{ $article->title }}" loading="lazy">
                                </div>
                                <div class="blog-one__content">
                                    <div class="blog-one__content-inner">
                                        <ul class="blog-one__meta list-unstyled">
                                            <li><a href="{{ route('articles.show', $article->slug) }}" wire:navigate><span class="fas fa-tag"></span>{{ $article->category?->name ?? 'Artikel' }}</a></li>
                                            <li><a href="{{ route('articles.show', $article->slug) }}" wire:navigate><span class="fas fa-calendar-alt"></span>{{ \App\Core\Helpers\DateHelper::formatDate($article->published_at) }}</a></li>
                                        </ul>
                                        <h3 class="blog-one__title"><a href="{{ route('articles.show', $article->slug) }}" wire:navigate>{{ $article->title }}</a></h3>
                                        <div class="blog-one__btn">
                                            <a href="{{ route('articles.show', $article->slug) }}" wire:navigate class="thm-btn">
                                                <span class="fas fa-arrow-right"></span>
                                                Baca Selengkapnya
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
