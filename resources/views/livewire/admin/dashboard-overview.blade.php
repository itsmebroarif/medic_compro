<div>
    {{-- Stat Cards --}}
    <div class="row g-3">
        @php
            $cards = [
                ['icon' => 'bi-calendar2-check', 'bg' => 'text-bg-primary', 'label' => 'Janji Temu Hari Ini', 'value' => $this->stats['appointments_today']],
                ['icon' => 'bi-hourglass-split', 'bg' => 'text-bg-warning', 'label' => 'Menunggu Konfirmasi', 'value' => $this->stats['appointments_pending']],
                ['icon' => 'bi-person-badge', 'bg' => 'text-bg-info', 'label' => 'Dokter Aktif', 'value' => $this->stats['doctors']],
                ['icon' => 'bi-diagram-3', 'bg' => 'text-bg-success', 'label' => 'Poli Aktif', 'value' => $this->stats['departments']],
                ['icon' => 'bi-person-vcard', 'bg' => 'text-bg-secondary', 'label' => 'Pasien Terdaftar', 'value' => $this->stats['patients']],
                ['icon' => 'bi-hospital', 'bg' => 'text-bg-danger', 'label' => 'Rawat Inap Aktif', 'value' => $this->stats['inpatients_active']],
                ['icon' => 'bi-person-walking', 'bg' => 'text-bg-primary', 'label' => 'Rawat Jalan Hari Ini', 'value' => $this->stats['outpatients_today']],
                ['icon' => 'bi-capsule', 'bg' => 'text-bg-warning', 'label' => 'Obat Menipis', 'value' => $this->stats['medicines_low']],
                ['icon' => 'bi-newspaper', 'bg' => 'text-bg-success', 'label' => 'Artikel Terbit', 'value' => $this->stats['articles_published']],
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="col-6 col-md-4 col-xl-3 col-xxl-2-4">
                <div class="card {{ $card['bg'] }} card-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 small opacity-75">{{ $card['label'] }}</p>
                                <h3 class="mb-0">{{ $card['value'] }}</h3>
                            </div>
                            <i class="bi {{ $card['icon'] }}" style="font-size: 2.4rem; opacity: .75;"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mt-1">
        {{-- Chart --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header"><h3 class="card-title mb-0">Janji Temu 14 Hari Terakhir</h3></div>
                <div class="card-body">
                    <canvas id="appointmentChart" height="220"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent Appointments --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Janji Temu Terbaru</h3>
                    <a href="{{ route('admin.appointments') }}" wire:navigate class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->recentAppointments as $appointment)
                                <tr>
                                    <td><code>{{ $appointment->booking_code }}</code></td>
                                    <td>{{ $appointment->patient_name }}</td>
                                    <td>{{ $appointment->doctor?->name }}</td>
                                    <td>{{ \App\Core\Helpers\DateHelper::formatDate($appointment->appointment_date) }}</td>
                                    <td><span class="badge {{ $appointment->status->badgeClass() }}">{{ $appointment->status->label() }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada janji temu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    const ctx = document.getElementById('appointmentChart');
    if (ctx && window.Chart) {
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($this->chart['labels']),
                datasets: [{
                    label: 'Janji Temu',
                    data: @json($this->chart['values']),
                    backgroundColor: 'rgba(13, 110, 253, .65)',
                    borderColor: '#0d6efd',
                    borderWidth: 1,
                    borderRadius: 6,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });
    }
</script>
@endscript
