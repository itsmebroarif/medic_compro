<div>
    <section class="page-header" style="background-image: linear-gradient(rgba(9,39,58,.8), rgba(9,39,58,.8)), url('{{ asset('generated/hero-hospital.jpg') }}'); background-size: cover; background-position: center; padding: 80px 0; text-align: center;">
        <div class="container">
            <h1 style="color: #fff; font-size: 2.4rem;">Janji Temu Online</h1>
            <p style="color: rgba(255,255,255,.8);"><a href="{{ route('home') }}" wire:navigate style="color: #ffc107; text-decoration: none;">Beranda</a> &nbsp;/&nbsp; Janji Temu</p>
        </div>
    </section>

    <section style="padding: 60px 0 80px;">
        <div class="container">
            @if ($bookingCode && $bookingSummary)
                {{-- ===== TIKET BOOKING ===== --}}
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                            <div class="text-center" style="background: #0d6efd; color: #fff; padding: 24px;">
                                <i class="fas fa-check-circle" style="font-size: 3rem;"></i>
                                <h3 class="mt-2" style="color: #fff;">Janji Temu Berhasil Diajukan!</h3>
                                <p style="opacity: .85; margin-bottom: 0;">Simpan kode booking Anda</p>
                            </div>
                            <div class="card-body" style="padding: 30px;">
                                <div class="text-center" style="background: #f0f7ff; border: 2px dashed #0d6efd; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                                    <p class="mb-1 small text-muted">KODE BOOKING</p>
                                    <h2 style="letter-spacing: 3px; color: #0d6efd; margin: 0;">{{ $bookingSummary['kode'] }}</h2>
                                </div>
                                <table class="table table-borderless" style="margin-bottom: 8px;">
                                    <tr><td class="text-muted">Nama Pasien</td><td class="text-end"><strong>{{ $bookingSummary['nama'] }}</strong></td></tr>
                                    <tr><td class="text-muted">WhatsApp</td><td class="text-end"><strong>{{ $bookingSummary['telepon'] }}</strong></td></tr>
                                    <tr><td class="text-muted">Dokter</td><td class="text-end"><strong>{{ $bookingSummary['dokter'] }}</strong></td></tr>
                                    <tr><td class="text-muted">Poli</td><td class="text-end"><strong>{{ $bookingSummary['poli'] }}</strong></td></tr>
                                    <tr><td class="text-muted">Tanggal Kunjungan</td><td class="text-end"><strong>{{ $bookingSummary['tanggal'] }}</strong></td></tr>
                                </table>
                                <p class="small text-muted">Status awal: <span class="badge text-bg-warning">Menunggu Konfirmasi</span> — petugas pendaftaran akan mengonfirmasi jadwal Anda via WhatsApp.</p>
                                <div class="d-grid gap-2">
                                    <a class="btn btn-success btn-lg" target="_blank" rel="noopener"
                                       href="https://wa.me/{{ $whatsappAdmin }}?text={{ urlencode('Halo Admin, saya ' . $bookingSummary['nama'] . ' ingin konfirmasi janji temu. Kode booking: ' . $bookingSummary['kode']) }}">
                                        <i class="fab fa-whatsapp"></i> Konfirmasi via WhatsApp Admin
                                    </a>
                                    <button type="button" class="btn btn-outline-primary" wire:click="bookAnother">
                                        <i class="fas fa-redo"></i> Buat Janji Temu Lagi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- ===== FORM BOOKING ===== --}}
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm" style="border-radius: 16px; padding: 28px;">
                            <h4 style="font-weight: 700; margin-bottom: 6px;">Form Pengajuan Janji Temu</h4>
                            <p class="text-muted">Isi data di bawah ini, petugas kami akan mengonfirmasi via WhatsApp.</p>

                            <form wire:submit="book">
                                {{-- Step 1: Poli & Dokter --}}
                                <h6 class="text-uppercase text-secondary" style="letter-spacing: 1px; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">1. Pilih Poli & Dokter</h6>
                                <div class="row g-3" style="margin-bottom: 18px;">
                                    <div class="col-md-6">
                                        <label class="form-label" for="dept">Poli *</label>
                                        <select id="dept" class="form-select" wire:model.live="department_id">
                                            <option value="">-- Pilih Poli --</option>
                                            @foreach ($this->departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('department_id') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="dokter">Dokter *</label>
                                        <select id="dokter" class="form-select" wire:model.live="doctor_id" @disabled(! $this->department_id)>
                                            <option value="">{{ $this->department_id ? '-- Pilih Dokter --' : 'Pilih poli dulu' }}</option>
                                            @foreach ($this->doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('doctor_id') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                {{-- Step 2: Tanggal & Jadwal --}}
                                <h6 class="text-uppercase text-secondary" style="letter-spacing: 1px; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">2. Tanggal Kunjungan</h6>
                                <div class="row g-3" style="margin-bottom: 18px;">
                                    <div class="col-md-6">
                                        <label class="form-label" for="tanggal">Tanggal Kunjungan *</label>
                                        <input id="tanggal" type="date" class="form-control" min="{{ now()->toDateString() }}" wire:model.live="appointment_date" @disabled(! $this->doctor_id)>
                                        @error('appointment_date') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jam Praktik Tersedia</label>
                                        <div class="form-control" style="min-height: 38px; height: auto; background: #f8f9fa;">
                                            @if ($this->selectedDoctor && $this->appointment_date)
                                                @forelse ($this->availableSlots as $slot)
                                                    <span class="badge text-bg-primary me-1 mb-1">{{ $slot->dayName() }}, {{ $slot->timeRange() }}@if($slot->quota) (kuota {{ $slot->quota }})@endif</span>
                                                @empty
                                                    <span class="text-danger small">Dokter tidak praktik pada tanggal ini.</span>
                                                @endforelse
                                            @else
                                                <span class="text-muted small">Pilih dokter dan tanggal untuk melihat jadwal.</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Step 3: Data Pasien --}}
                                <h6 class="text-uppercase text-secondary" style="letter-spacing: 1px; border-bottom: 2px solid #e9ecef; padding-bottom: 8px;">3. Data Pasien</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="nama">Nama Lengkap *</label>
                                        <input id="nama" type="text" class="form-control" wire:model="patient_name" placeholder="Nama lengkap pasien">
                                        @error('patient_name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="wa">No. WhatsApp Aktif *</label>
                                        <input id="wa" type="text" class="form-control" wire:model="patient_phone" placeholder="08xxxxxxxxxx" inputmode="tel">
                                        @error('patient_phone') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="tgl_lahir">Tanggal Lahir *</label>
                                        <input id="tgl_lahir" type="date" class="form-control" wire:model="patient_dob" max="{{ now()->subDay()->toDateString() }}">
                                        @error('patient_dob') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="jk">Jenis Kelamin *</label>
                                        <select id="jk" class="form-select" wire:model="patient_gender">
                                            <option value="">-- Pilih --</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        @error('patient_gender') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="rm">No. Rekam Medis <span class="text-muted">(opsional)</span></label>
                                        <input id="rm" type="text" class="form-control" wire:model="mr_number" placeholder="Pasien lama saja">
                                        @error('mr_number') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="keluhan">Keluhan Singkat</label>
                                        <textarea id="keluhan" class="form-control" rows="3" wire:model="complaint" placeholder="Ceritakan keluhan Anda..."></textarea>
                                        @error('complaint') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <button type="submit" class="thm-btn w-100 mt-4" style="font-size: 1.05rem;" wire:loading.attr="disabled">
                                    <span class="fas fa-paper-plane"></span>
                                    <span wire:loading.remove>Kirim Pengajuan Janji Temu</span>
                                    <span wire:loading>Mengirim...</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Sidebar info --}}
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm" style="border-radius: 16px; padding: 24px; background: #0d6efd; color: #fff;">
                            <h5 style="color: #fff; font-weight: 700;"><i class="fas fa-info-circle"></i> Cara Booking</h5>
                            <ol style="padding-left: 18px; line-height: 2;">
                                <li>Pilih poli & dokter yang dituju</li>
                                <li>Pilih tanggal — sistem validasi jadwal dokter otomatis</li>
                                <li>Isi data pasien dengan benar</li>
                                <li>Catat kode booking yang diterbitkan</li>
                                <li>Datang 15 menit sebelum jadwal</li>
                            </ol>
                        </div>
                        <div class="card border-0 shadow-sm" style="border-radius: 16px; padding: 24px; margin-top: 20px;">
                            <h5 style="font-weight: 700;"><i class="fas fa-phone-alt text-danger"></i> Darurat?</h5>
                            <p class="text-muted mb-1">IGD 24 Jam</p>
                            <a href="tel:{{ preg_replace('/\D/', '', \App\Domains\Content\Models\SiteSetting::get('igd_phone', '085817048266')) }}" class="fs-4" style="color: #dc3545; font-weight: 800; text-decoration: none;">
                                {{ \App\Domains\Content\Models\SiteSetting::get('igd_phone', '0858 1704 8266') }}
                            </a>
                            <hr>
                            <p class="small text-muted mb-0">Jam layanan poliklinik: Senin–Sabtu 08.00–20.00 WIB (sesuai jadwal dokter masing-masing).</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
