@php use App\Domains\Content\Models\SiteSetting; @endphp
<div>
    <section class="page-header" style="background-image: linear-gradient(rgba(9,39,58,.8), rgba(9,39,58,.8)), url('{{ asset('generated/hero-hospital.jpg') }}'); background-size: cover; background-position: center; padding: 80px 0; text-align: center;">
        <div class="container">
            <h1 style="color: #fff; font-size: 2.4rem;">Tentang Kami</h1>
            <p style="color: rgba(255,255,255,.8);"><a href="{{ route('home') }}" wire:navigate style="color: #ffc107; text-decoration: none;">Beranda</a> &nbsp;/&nbsp; Tentang</p>
        </div>
    </section>

    <section style="padding: 60px 0 80px;">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <img src="{{ asset('generated/about-reception.jpg') }}" alt="Resepsionis RS Medika Service" style="width: 100%; aspect-ratio: 16/10; object-fit: cover; border-radius: 16px; box-shadow: 0 18px 45px rgba(9,39,58,.25);">
                </div>
                <div class="col-lg-6">
                    <div class="section-title text-left">
                        <div class="section-title__tagline-box">
                            <p class="section-title__tagline">Tentang {{ SiteSetting::get('hospital_name', config('app.name')) }}</p>
                        </div>
                        <h2 class="section-title__title" style="font-size: 2rem;">Melayani Sepenuh Hati, <span>Sejak Berdiri</span></h2>
                    </div>
                    <p class="about-one__text" style="margin-bottom: 18px;">
                        {{ SiteSetting::get('about', 'Kami adalah rumah sakit umum yang menyediakan layanan kesehatan terpadu mulai dari rawat jalan, rawat inap, poliklinik spesialis, laboratorium, radiologi, hingga instalasi gawat darurat (IGD) 24 jam dengan tenaga medis profesional.') }}
                    </p>
                    <ul class="about-one__points-box list-unstyled">
                        <li class="d-flex gap-2 align-items-center mb-2"><span class="fas fa-check text-success"></span> Dokter spesialis berpengalaman & bersertifikasi</li>
                        <li class="d-flex gap-2 align-items-center mb-2"><span class="fas fa-check text-success"></span> IGD 24 Jam dengan tim siaga</li>
                        <li class="d-flex gap-2 align-items-center mb-2"><span class="fas fa-check text-success"></span> Fasilitas modern & nyaman</li>
                        <li class="d-flex gap-2 align-items-center"><span class="fas fa-check text-success"></span> Kerja sama BPJS & asuransi swasta</li>
                    </ul>
                </div>
            </div>

            @php $c = \App\Domains\Content\Support\SiteContent::map(); @endphp
            <div class="row text-center g-4" style="margin-top: 50px;">
                @foreach ([1 => 'fas fa-user-md', 2 => 'fas fa-hospital', 3 => 'fas fa-users', 4 => 'fas fa-award'] as $i => $icon)
                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm" style="border-radius: 16px; padding: 26px 12px;">
                            <div style="font-size: 2rem; color: #0d6efd;"><i class="{{ $icon }}"></i></div>
                            <h3 style="margin: 8px 0 2px;">{{ $c['about_stat_'.$i.'_num'] }}</h3>
                            <p class="text-muted small mb-0">{{ $c['about_stat_'.$i.'_label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row g-4" style="margin-top: 30px;">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; padding: 28px;">
                        <h5 style="font-weight: 700;"><i class="fas fa-eye text-primary"></i> Visi</h5>
                        <p class="text-muted mb-0">{{ $c['vision'] }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; padding: 28px;">
                        <h5 style="font-weight: 700;"><i class="fas fa-bullseye text-primary"></i> Misi</h5>
                        <ul class="text-muted mb-0" style="line-height: 1.9;">
                            @foreach (preg_split('/\r\n|\r|\n/', trim($c['mission'])) as $line)
                                @if (trim($line) !== '')
                                    <li>{{ trim($line) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
