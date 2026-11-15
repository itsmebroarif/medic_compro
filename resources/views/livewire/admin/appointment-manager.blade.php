<div>
    @if ($flashMessage)
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header"><h3 class="card-title mb-0">Antrean Janji Temu</h3></div>
        <div class="card-body pb-0">
            <div class="row g-2">
                <div class="col-md-2">
                    <input type="date" class="form-control form-control-sm" wire:model.live="date" aria-label="Filter tanggal">
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" wire:model.live="department" aria-label="Filter poli">
                        <option value="">Semua Poli</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" wire:model.live="doctor" aria-label="Filter dokter">
                        <option value="">Semua Dokter</option>
                        @foreach ($doctors as $doc)
                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" wire:model.live="status" aria-label="Filter status">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari kode / nama / WA..." wire:model.live.debounce.300ms="search" aria-label="Cari">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pasien</th>
                        <th>Dokter & Poli</th>
                        <th>Tgl Kunjungan</th>
                        <th style="width: 210px;">Status</th>
                        <th>Catatan Internal</th>
                        <th class="text-end">WA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr wire:key="appt-{{ $appointment->id }}">
                            <td><code>{{ $appointment->booking_code }}</code>
                                <br><span class="small text-muted">{{ $appointment->mr_number ? 'RM: '.$appointment->mr_number : 'Pasien baru' }}</span>
                            </td>
                            <td>
                                <strong>{{ $appointment->patient_name }}</strong><br>
                                <span class="small text-muted">{{ $appointment->patient_gender === 'L' ? 'L' : 'P' }} &middot; {{ $appointment->patient_dob?->age }} th &middot; {{ $appointment->patient_phone }}</span>
                                @if ($appointment->complaint)
                                    <br><span class="small fst-italic text-muted">&ldquo;{{ $appointment->complaint }}&rdquo;</span>
                                @endif
                            </td>
                            <td>{{ $appointment->doctor?->name }}<br><span class="small text-muted">{{ $appointment->doctor?->department?->name }}</span></td>
                            <td>{{ \App\Core\Helpers\DateHelper::formatDate($appointment->appointment_date) }}</td>
                            <td>
                                <select class="form-select form-select-sm" wire:change="updateStatus({{ $appointment->id }}, $event.target.value)">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected($appointment->status->value === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <span class="badge {{ $appointment->status->badgeClass() }} mt-1">{{ $appointment->status->label() }}</span>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" wire:model="notes.{{ $appointment->id }}" placeholder="Catatan...">
                                    <button type="button" class="btn btn-outline-secondary" wire:click="saveNote({{ $appointment->id }})" aria-label="Simpan catatan"><i class="bi bi-save"></i></button>
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="{{ $this->waLink($appointment) }}" target="_blank" rel="noopener" class="btn btn-sm btn-success" title="Kirim WhatsApp konfirmasi"><i class="fab fa-whatsapp"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada janji temu{{ $date ? ' pada tanggal '.\App\Core\Helpers\DateHelper::formatDate($date) : '' }}.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $appointments->links('pagination::bootstrap-5') }}</div>
    </div>
</div>
