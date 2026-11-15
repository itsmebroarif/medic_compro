<div>
    {{-- Page Header --}}
    <section class="page-header" style="background-image: linear-gradient(rgba(9,39,58,.8), rgba(9,39,58,.8)), url('{{ asset('generated/hero-hospital.jpg') }}'); background-size: cover; background-position: center; padding: 80px 0; text-align: center;">
        <div class="container">
            <h1 style="color: #fff; font-size: 2.6rem; margin-bottom: 10px;">Direktori Dokter</h1>
            <p style="color: rgba(255,255,255,.8);"><a href="{{ route('home') }}" wire:navigate style="color: #ffc107; text-decoration: none;">Beranda</a> &nbsp;/&nbsp; Dokter</p>
        </div>
    </section>

    <section style="padding: 50px 0 70px;">
        <div class="container">
            {{-- Filter Bar --}}
            <div class="row g-3" style="margin-bottom: 30px;">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label small text-muted mb-1" for="search">Cari Nama Dokter</label>
                    <input id="search" type="text" class="form-control" placeholder="Ketik nama dokter..." wire:model.live.debounce.300ms="search">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small text-muted mb-1" for="poli">Poli / Spesialisasi</label>
                    <select id="poli" class="form-select" wire:model.live="department">
                        <option value="">Semua Poli</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small text-muted mb-1" for="hari">Hari Praktik</label>
                    <select id="hari" class="form-select" wire:model.live="day">
                        <option value="">Semua Hari</option>
                        @foreach ($days as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>

            {{-- Results --}}
            <p class="text-muted">Menampilkan <strong>{{ $doctors->count() }}</strong> dokter</p>

            <div class="row filter-layout">
                @forelse ($doctors as $doctor)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="team-two__single">
                            <div class="team-two__img-box">
                                <div class="team-two__img">
                                    <img src="{{ $doctor->photoUrl() ?? asset('generated/doctor-placeholder.png') }}" alt="{{ $doctor->name }}" loading="lazy">
                                </div>
                            </div>
                            <div class="team-two__content">
                                <p class="team-two__designation">{{ $doctor->department->name }}</p>
                                <h3 class="team-two__name"><a href="{{ route('doctors.show', $doctor->slug) }}" wire:navigate>{{ $doctor->name }}</a></h3>
                                <p class="small text-muted mb-2">
                                    {{ $doctor->schedules->take(2)->map(fn ($s) => $s->dayName().' '.substr((string) $s->start_time, 0, 5))->implode(' · ') }}
                                </p>
                                <a href="{{ route('appointment') }}?dokter={{ $doctor->id }}" wire:navigate class="thm-btn" style="font-size: .8rem; padding: 10px 16px;">
                                    <span class="fas fa-calendar-check"></span> Buat Janji
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">Tidak ada dokter yang cocok dengan filter Anda.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
