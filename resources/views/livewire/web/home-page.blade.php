<div>
    {{-- ===== HERO ===== --}}
    <section class="hero-hospital" style="background-image: url('{{ asset('generated/hero-hospital.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-7">
                    <span class="hero-badge"><i class="fas fa-shield-alt"></i> Terakreditasi KARS &middot; Kerja Sama BPJS &amp; Asuransi</span>
                    <p class="banner-one__sub-title" style="color: #2dd4bf; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; margin-bottom: 6px;">IGD 24 Jam</p>
                    <h1 class="banner-one__title" style="color: #fff; font-size: 2.9rem; font-weight: 800;">
                        Kesehatan Anda,<br><span style="color: #2dd4bf;">Prioritas Kami</span>
                    </h1>
                    <p class="banner-one__text" style="color: rgba(255,255,255,.88); margin: 18px 0 28px; font-size: 1.08rem; max-width: 560px;">
                        {{ \App\Domains\Content\Models\SiteSetting::get('tagline', 'Layanan kesehatan terpadu: rawat jalan, rawat inap, poliklinik spesialis, dan IGD 24 jam.') }}
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('appointment') }}" wire:navigate class="thm-btn">
                            <span class="fas fa-calendar-check"></span>
                            Janji Temu Online
                        </a>
                        <a href="tel:{{ preg_replace('/\D/', '', $this->stats['igd']) }}" class="thm-btn" style="background: #dc2626;">
                            <span class="fas fa-ambulance"></span>
                            IGD: {{ $this->stats['igd'] }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== USP STRIP ===== --}}
    <section style="padding: 54px 0 10px;">
        <div class="container">
            <div class="row g-3">
                @foreach ([
                    ['fas fa-award', 'Terakreditasi Paripurna', 'Standar mutu &amp; keselamatan pasien tertinggi (KARS).'],
                    ['fas fa-user-md', 'Dokter Spesialis', 'Tenaga medis berpengalaman &amp; tersertifikasi.'],
                    ['fas fa-ambulance', 'IGD 24 Jam', 'Tim gawat darurat siaga setiap saat.'],
                    ['fas fa-file-invoice-dollar', 'BPJS &amp; Asuransi', 'Kerja sama BPJS Kesehatan &amp; asuransi swasta.'],
                ] as [$icon, $title, $text])
                    <div class="col-6 col-lg-3">
                        <div class="usp-card">
                            <div class="usp-icon"><i class="{{ $icon }}"></i></div>
                            <h6 style="font-weight: 700; margin-bottom: 6px;">{{ $title }}</h6>
                            <p class="text-muted small mb-0">{!! $text !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== POLIKLINIK ===== --}}
    <section class="services-one" style="padding: 54px 0;">
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 36px;">
                <div class="section-title__tagline-box" style="justify-content: center;">
                    <p class="section-title__tagline">Poliklinik Kami</p>
                </div>
                <h2 class="section-title__title">Layanan <span>Spesialis</span> Kami</h2>
            </div>
            <div class="row">
                @php
                    $poliIcons = [
                        'umum' => 'fas fa-stethoscope',
                        'anak' => 'fas fa-baby',
                        'jantung' => 'fas fa-heartbeat',
                        'kandungan' => 'fas fa-female',
                        'mata' => 'fas fa-eye',
                        'tht' => 'fas fa-ear-listen',
                        'gigi' => 'fas fa-tooth',
                        'bedah' => 'fas fa-user-md',
                    ];
                @endphp
                @foreach ($this->departments as $department)
                    @php
                        $name = mb_strtolower($department->name);
                        $icon = collect($poliIcons)->first(fn ($i, $key) => str_contains($name, $key), 'fas fa-hospital');
                    @endphp
                    <div class="col-xl-3 col-lg-4 col-md-6" style="margin-bottom: 22px;">
                        <a href="{{ route('doctors.index') }}?poli={{ $department->id }}" wire:navigate style="text-decoration: none; color: inherit;">
                            <div class="card h-100 text-center border-0 shadow-sm" style="border-radius: 16px; padding: 22px 14px;">
                                <div class="poli-icon"><i class="{{ $icon }}"></i></div>
                                <h5 style="font-weight: 700; font-size: 1.02rem;">{{ $department->name }}</h5>
                                <p class="text-muted small mb-2">{{ $department->doctors_count }} dokter</p>
                                <span class="text-primary small" style="font-weight: 600;">Lihat Dokter &rarr;</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== RAWAT INAP HIGHLIGHT ===== --}}
    <section style="padding: 20px 0 54px;">
        <div class="container">
            <div class="row g-4 align-items-center" style="background: #f6fbff; border-radius: 20px; overflow: hidden;">
                <div class="col-lg-6">
                    <img src="{{ asset('generated/ward.jpg') }}" alt="Kamar rawat inap RS Medika Service" style="width: 100%; height: 100%; min-height: 320px; object-fit: cover;">
                </div>
                <div class="col-lg-6" style="padding: 40px 34px;">
                    <div class="section-title text-left" style="margin-bottom: 14px;">
                        <div class="section-title__tagline-box">
                            <p class="section-title__tagline">Fasilitas Rawat Inap</p>
                        </div>
                        <h2 class="section-title__title" style="font-size: 1.9rem;">Ruangan Nyaman,<br><span>Pemulihan Lebih Cepat</span></h2>
                    </div>
                    <ul class="list-unstyled" style="line-height: 2.15;">
                        <li><span class="fas fa-check-circle text-success"></span> VIP &amp; Kelas 1–3, ICU, NICU &amp; Ruang Isolasi</li>
                        <li><span class="fas fa-check-circle text-success"></span> Perawat 24 jam &amp; kunjungan dokter DPJP terjadwal</li>
                        <li><span class="fas fa-check-circle text-success"></span> Perlengkapan lengkap &amp; nurse call di setiap kamar</li>
                        <li><span class="fas fa-check-circle text-success"></span> Kantin &amp; apotek 24 jam di dalam area RS</li>
                    </ul>
                    <a href="{{ route('contact') }}" wire:navigate class="thm-btn"><span class="fas fa-bed"></span> Tanyakan Kamar Tersedia</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== DOKTER ===== --}}
    <section class="team-section" style="padding: 20px 0 54px;">
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 36px;">
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
                                <div class="team-two__img" style="height: 280px; overflow: hidden;">
                                    <img src="{{ $doctor->photoUrl() ?? asset('generated/doctor-placeholder.png') }}" alt="{{ $doctor->name }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; object-position: center 18%;">
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

    {{-- ===== STATS BAND ===== --}}
    <section class="stats-band" style="padding: 44px 0;">
        <div class="container">
            <div class="row text-center g-4">
                @foreach ([
                    [$this->stats['doctors'] . '+', 'Dokter Spesialis &amp; Subspesialis'],
                    [$this->stats['departments'], 'Poliklinik &amp; Layanan'],
                    [number_format($this->stats['patients']) . '+', 'Pasien Terdaftar'],
                    ['24/7', 'IGD &amp; Rawat Inap Siaga'],
                ] as [$num, $label])
                    <div class="col-6 col-lg-3">
                        <div class="stat-num">{!! $num !!}</div>
                        <div class="stat-label">{!! $label !!}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== ARTIKEL ===== --}}
    @if ($this->latestArticles->isNotEmpty())
        <section class="blog-section" style="padding: 54px 0;">
            <div class="container">
                <div class="section-title text-center" style="margin-bottom: 36px;">
                    <div class="section-title__tagline-box" style="justify-content: center;">
                        <p class="section-title__tagline">Edukasi &amp; Berita</p>
                    </div>
                    <h2 class="section-title__title">Artikel <span>Terbaru</span></h2>
                </div>
                <div class="row">
                    @foreach ($this->latestArticles as $article)
                        @php $fallback = 'generated/articles/article-' . (($article->id % 6) + 1) . '.jpg'; @endphp
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="blog-one__single">
                                <div class="blog-one__img">
                                    <img src="{{ $article->thumbnailUrl() ?? asset($fallback) }}" alt="{{ $article->title }}" loading="lazy" style="height: 210px; object-fit: cover;">
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

    {{-- ===== EMERGENCY CTA ===== --}}
    <section style="padding: 10px 0 54px;">
        <div class="container">
            <div class="emergency-band">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-ambulance" style="font-size: 3rem; opacity: .9;"></i>
                    <div>
                        <h3 style="color: #fff; margin: 0 0 4px; font-size: 1.5rem;">Kondisi Gawat Darurat?</h3>
                        <p style="margin: 0; opacity: .92;">IGD kami siaga 24 jam — dokter &amp; perawat gawat darurat tersedia setiap saat.</p>
                    </div>
                </div>
                <a href="tel:{{ preg_replace('/\D/', '', $this->stats['igd']) }}" class="btn btn-light btn-lg" style="font-weight: 800; color: #b91c1c; border-radius: 100px; padding: 12px 28px;">
                    <i class="fas fa-phone-alt"></i> {{ $this->stats['igd'] }}
                </a>
            </div>
        </div>
    </section>

    {{-- ===== LOKASI & JAM LAYANAN ===== --}}
    <section style="padding: 0 0 70px;">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-7">
                    @if (\App\Domains\Content\Models\SiteSetting::get('maps_embed'))
                        <div class="maps-frame" style="height: 100%; min-height: 320px;">
                            <iframe src="{{ \App\Domains\Content\Models\SiteSetting::get('maps_embed') }}" width="100%" height="100%" style="border: 0; min-height: 320px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi RS Medika Service"></iframe>
                        </div>
                    @endif
                </div>
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; padding: 26px;">
                        <h4 style="font-weight: 800;"><i class="fas fa-map-marker-alt text-danger"></i> Lokasi &amp; Jam Layanan</h4>
                        <p class="text-muted">{{ \App\Domains\Content\Models\SiteSetting::get('address', 'Jl. Kesehatan Raya No. 1, Jakarta') }}</p>
                        <ul class="list-unstyled" style="line-height: 2.2; margin-bottom: 6px;">
                            <li><i class="fas fa-clock text-primary"></i> {{ \App\Domains\Content\Models\SiteSetting::get('hours', 'Senin–Sabtu 08.00–20.00 WIB') }}</li>
                            <li><i class="fas fa-ambulance text-danger"></i> IGD 24 Jam: <a href="tel:{{ preg_replace('/\D/', '', $this->stats['igd']) }}" style="color: inherit; font-weight: 700;">{{ $this->stats['igd'] }}</a></li>
                            <li><i class="fas fa-phone text-primary"></i> Telepon: {{ \App\Domains\Content\Models\SiteSetting::get('phone', '021 5550 1235') }}</li>
                            <li><i class="fab fa-whatsapp text-success"></i> WhatsApp: {{ \App\Domains\Content\Models\SiteSetting::get('whatsapp', '6281234567890') }}</li>
                        </ul>
                        <a href="{{ route('contact') }}" wire:navigate class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i> Kirim Pesan ke Kami</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
