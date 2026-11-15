<div>
    @if ($flashMessage)
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Umum --}}
        <div class="col-xl-6">
            <form wire:submit="saveGroup('general')" class="card h-100">
                <div class="card-header"><h3 class="card-title mb-0"><i class="bi bi-hospital me-2"></i>Umum</h3></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="s-name">Nama Rumah Sakit *</label>
                        <input id="s-name" type="text" class="form-control" wire:model="settings.hospital_name">
                        @error('settings.hospital_name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s-tagline">Tagline / Hero Text</label>
                        <input id="s-tagline" type="text" class="form-control" wire:model="settings.tagline">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s-about-short">Tentang Singkat <span class="text-muted">(footer)</span></label>
                        <textarea id="s-about-short" class="form-control" rows="2" wire:model="settings.about_short"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s-about">Tentang Lengkap <span class="text-muted">(halaman Tentang)</span></label>
                        <textarea id="s-about" class="form-control" rows="3" wire:model="settings.about"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="s-meta">Meta Description <span class="text-muted">(SEO)</span></label>
                        <input id="s-meta" type="text" class="form-control" wire:model="settings.meta_description" maxlength="255">
                    </div>
                </div>
                <div class="card-footer"><button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Bagian Umum</button></div>
            </form>
        </div>

        {{-- Kontak --}}
        <div class="col-xl-6">
            <form wire:submit="saveGroup('contact')" class="card h-100">
                <div class="card-header"><h3 class="card-title mb-0"><i class="bi bi-telephone me-2"></i>Kontak & Lokasi</h3></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="s-igd">Nomor IGD 24 Jam</label>
                            <input id="s-igd" type="text" class="form-control" wire:model="settings.igd_phone" placeholder="0858 1704 8266">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="s-phone">Telepon</label>
                            <input id="s-phone" type="text" class="form-control" wire:model="settings.phone">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="s-wa">WhatsApp <span class="text-muted">(62xxx)</span></label>
                            <input id="s-wa" type="text" class="form-control" wire:model="settings.whatsapp" placeholder="6285817048266">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="s-email">Email</label>
                            <input id="s-email" type="email" class="form-control" wire:model="settings.email">
                            @error('settings.email') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="s-address">Alamat</label>
                            <textarea id="s-address" class="form-control" rows="2" wire:model="settings.address"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="s-hours">Jam Layanan</label>
                            <input id="s-hours" type="text" class="form-control" wire:model="settings.hours">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="s-maps">Google Maps Embed <span class="text-muted">(tempel URL src dari iframe embed)</span></label>
                            <input id="s-maps" type="text" class="form-control" wire:model="settings.maps_embed" placeholder="https://www.google.com/maps?q=...&output=embed">
                        </div>
                    </div>
                </div>
                <div class="card-footer"><button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Bagian Kontak</button></div>
            </form>
        </div>

        {{-- Sosial Media --}}
        <div class="col-xl-6">
            <form wire:submit="saveGroup('social')" class="card h-100">
                <div class="card-header"><h3 class="card-title mb-0"><i class="bi bi-share me-2"></i>Sosial Media</h3></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="s-fb"><i class="bi bi-facebook text-primary"></i> Facebook</label>
                        <input id="s-fb" type="url" class="form-control" wire:model="settings.facebook" placeholder="https://facebook.com/...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="s-ig"><i class="bi bi-instagram text-danger"></i> Instagram</label>
                        <input id="s-ig" type="url" class="form-control" wire:model="settings.instagram" placeholder="https://instagram.com/...">
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="s-tt"><i class="bi bi-tiktok"></i> TikTok</label>
                        <input id="s-tt" type="url" class="form-control" wire:model="settings.tiktok" placeholder="https://tiktok.com/@...">
                    </div>
                </div>
                <div class="card-footer"><button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Sosial Media</button></div>
            </form>
        </div>
    </div>
    <p class="text-muted small mt-3 mb-0"><i class="bi bi-info-circle"></i> Perubahan langsung diterapkan ke website (cache pengaturan di-reset otomatis setiap disimpan).</p>
</div>
