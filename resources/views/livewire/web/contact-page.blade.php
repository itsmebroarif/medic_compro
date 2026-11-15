@php use App\Domains\Content\Models\SiteSetting; @endphp
<div>
    <section class="page-header" style="background-image: linear-gradient(rgba(9,39,58,.8), rgba(9,39,58,.8)), url('{{ asset('generated/hero-hospital.jpg') }}'); background-size: cover; background-position: center; padding: 80px 0; text-align: center;">
        <div class="container">
            <h1 style="color: #fff; font-size: 2.4rem;">Hubungi Kami</h1>
            <p style="color: rgba(255,255,255,.8);"><a href="{{ route('home') }}" wire:navigate style="color: #ffc107; text-decoration: none;">Beranda</a> &nbsp;/&nbsp; Kontak</p>
        </div>
    </section>

    <section style="padding: 60px 0 80px;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; padding: 28px;">
                        <h4 style="font-weight: 700;">Informasi Kontak</h4>
                        <ul class="list-unstyled" style="line-height: 2.1;">
                            <li><i class="fas fa-map-marker-alt text-primary"></i> {{ SiteSetting::get('address', 'Jl. Kesehatan Raya No. 1, Jakarta') }}</li>
                            <li><i class="fas fa-phone-alt text-danger"></i> <strong>IGD 24 Jam: <a href="tel:{{ preg_replace('/\D/', '', SiteSetting::get('igd_phone', '085817048266')) }}" style="color: inherit; text-decoration: none;">{{ SiteSetting::get('igd_phone', '0858 1704 8266') }}</a></strong></li>
                            <li><i class="fas fa-phone text-primary"></i> Telepon: {{ SiteSetting::get('phone', '0858 1704 8266') }}</li>
                            <li><i class="fab fa-whatsapp text-success"></i> WhatsApp: {{ SiteSetting::get('whatsapp', '6285817048266') }}</li>
                            <li><i class="fas fa-envelope text-primary"></i> {{ SiteSetting::get('email', 'info@medicservice.co.id') }}</li>
                            <li><i class="fas fa-clock text-primary"></i> {{ SiteSetting::get('hours', 'Senin–Sabtu, 08.00–20.00 WIB (IGD 24 Jam)') }}</li>
                        </ul>
                        @if (SiteSetting::get('maps_embed'))
                            <div style="border-radius: 12px; overflow: hidden;">
                                <iframe src="{{ SiteSetting::get('maps_embed') }}" width="100%" height="220" style="border: 0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Rumah Sakit"></iframe>
                            </div>
                        @else
                            <img src="{{ asset('generated/about-reception.jpg') }}" alt="Resepsionis RS Medika Service" style="width: 100%; aspect-ratio: 16/10; object-fit: cover; border-radius: 12px;">
                        @endif
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; padding: 28px;">
                        <h4 style="font-weight: 700;">Kirim Pesan</h4>
                        @if ($sent)
                            <div class="alert alert-success" role="alert">Terima kasih! Pesan Anda telah kami terima. Tim kami akan segera menindaklanjuti.</div>
                        @endif
                        <form wire:submit="send" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="c-name">Nama *</label>
                                <input id="c-name" type="text" class="form-control" wire:model="name">
                                @error('name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="c-email">Email *</label>
                                <input id="c-email" type="email" class="form-control" wire:model="email">
                                @error('email') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="c-subject">Subjek *</label>
                                <input id="c-subject" type="text" class="form-control" wire:model="subject">
                                @error('subject') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="c-message">Pesan *</label>
                                <textarea id="c-message" class="form-control" rows="5" wire:model="message"></textarea>
                                @error('message') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="thm-btn"><span class="fas fa-paper-plane"></span> Kirim Pesan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
