<div>
    <section class="page-header" style="background-image: linear-gradient(rgba(9,39,58,.8), rgba(9,39,58,.8)), url('{{ asset('generated/hero-hospital.jpg') }}'); background-size: cover; background-position: center; padding: 80px 0; text-align: center;">
        <div class="container">
            <h1 style="color: #fff; font-size: 2.4rem;">{{ $doctor->name }}</h1>
            <p style="color: rgba(255,255,255,.8);"><a href="{{ route('home') }}" wire:navigate style="color: #ffc107; text-decoration: none;">Beranda</a> &nbsp;/&nbsp; <a href="{{ route('doctors.index') }}" wire:navigate style="color: #ffc107; text-decoration: none;">Dokter</a> &nbsp;/&nbsp; {{ $doctor->name }}</p>
        </div>
    </section>

    <section style="padding: 60px 0 80px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm text-center" style="border-radius: 16px; padding: 24px;">
                        <div style="border-radius: 16px; overflow: hidden; margin-bottom: 16px;">
                            <img src="{{ $doctor->photoUrl() ?? asset('generated/doctor-placeholder.png') }}" alt="{{ $doctor->name }}" style="width: 100%; aspect-ratio: 4/5; object-fit: cover; object-position: center top; border-radius: 14px;">
                        </div>
                        <h4 style="font-weight: 700;">{{ $doctor->name }}</h4>
                        <p class="text-primary mb-1" style="font-weight: 600;">{{ $doctor->department->name }}</p>
                        @if ($doctor->sip_number)
                            <p class="small text-muted">SIP: {{ $doctor->sip_number }}</p>
                        @endif
                        <a href="{{ route('appointment') }}?dokter={{ $doctor->id }}" wire:navigate class="thm-btn w-100 mt-2">
                            <span class="fas fa-calendar-check"></span> Buat Janji Temu
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; padding: 28px;">
                        <h5 style="font-weight: 700; border-bottom: 2px solid #e9ecef; padding-bottom: 12px;">Profil Singkat</h5>
                        <p style="white-space: pre-line; color: #495057;">{{ $doctor->bio ?: 'Profil dokter belum dilengkapi.' }}</p>

                        <h5 style="font-weight: 700; border-bottom: 2px solid #e9ecef; padding-bottom: 12px; margin-top: 24px;">Jadwal Praktik</h5>
                        @if ($doctor->schedules->isEmpty())
                            <div class="alert alert-warning">Jadwal praktik belum tersedia.</div>
                        @else
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr><th>Hari</th><th>Jam Praktik</th><th>Kuota</th><th>Catatan</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($doctor->schedules as $schedule)
                                        <tr>
                                            <td><strong>{{ $schedule->dayName() }}</strong></td>
                                            <td>{{ $schedule->timeRange() }}</td>
                                            <td>{{ $schedule->quota ? $schedule->quota.' pasien' : 'Tidak dibatasi' }}</td>
                                            <td>{{ $schedule->notes ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
