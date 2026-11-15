<div>
    @if ($flashMessage)
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <h3 class="card-title mb-0">Rawat Jalan (Poliklinik)</h3>
            <button type="button" class="btn btn-primary btn-sm" wire:click="create"><i class="bi bi-plus-lg"></i> Input Rawat Jalan</button>
        </div>
        <div class="card-body pb-0">
            <div class="row g-2">
                <div class="col-md-2">
                    <input type="date" class="form-control form-control-sm" wire:model.live="date" aria-label="Filter tanggal">
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" wire:model.live="department" aria-label="Filter poli">
                        <option value="">Semua Poli</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" wire:model.live="status" aria-label="Filter status">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari no. kunjungan / nama pasien..." wire:model.live.debounce.300ms="search" aria-label="Cari">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>No. Kunjungan</th><th>Pasien</th><th>Poli / Dokter</th><th>Tanggal</th><th>Keluhan</th><th style="width: 170px;">Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($visits as $visit)
                        <tr wire:key="visit-{{ $visit->id }}">
                            <td><code>{{ $visit->visit_number }}</code></td>
                            <td><strong>{{ $visit->patient?->name }}</strong><br><span class="small text-muted">{{ $visit->patient?->mr_number }}</span></td>
                            <td>{{ $visit->department?->name }}<br><span class="small text-muted">{{ $visit->doctor?->name ?? '-' }}</span></td>
                            <td>{{ $visit->visit_date->translatedFormat('d M Y H:i') }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($visit->complaint, 60) }}</td>
                            <td>
                                <select class="form-select form-select-sm" wire:change="updateStatus({{ $visit->id }}, $event.target.value)">
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected($visit->status === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $visit->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $visit->id }})" wire:confirm="Yakin hapus kunjungan ini?"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada kunjungan rawat jalan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $visits->links('pagination::bootstrap-5') }}</div>
    </div>

    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5); overflow-y: auto;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Rawat Jalan' : 'Input Rawat Jalan' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="rj-patient">Pasien *</label>
                                    <select id="rj-patient" class="form-select" wire:model="patient_id">
                                        <option value="">-- Pilih Pasien --</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->mr_number }} — {{ $patient->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="form-text small">Belum ada terdaftar? <a href="{{ route('admin.patients') }}" wire:navigate>Tambah pasien dulu</a></p>
                                    @error('patient_id') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="rj-date">Tanggal & Jam *</label>
                                    <input id="rj-date" type="datetime-local" class="form-control" wire:model="visit_date">
                                    @error('visit_date') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="rj-dept">Poli Tujuan *</label>
                                    <select id="rj-dept" class="form-select" wire:model="department_id">
                                        <option value="">-- Pilih Poli --</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="rj-doctor">Dokter</label>
                                    <select id="rj-doctor" class="form-select" wire:model="doctor_id">
                                        <option value="">-- Pilih Dokter --</option>
                                        @foreach ($doctors as $doc)
                                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('doctor_id') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="rj-complaint">Keluhan Utama</label>
                                    <textarea id="rj-complaint" class="form-control" rows="2" wire:model="complaint"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="rj-diagnosis">Diagnosis (setelah pemeriksaan)</label>
                                    <textarea id="rj-diagnosis" class="form-control" rows="2" wire:model="diagnosis"></textarea>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label" for="rj-notes">Catatan Petugas</label>
                                    <input id="rj-notes" type="text" class="form-control" wire:model="notes">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="rj-status">Status *</label>
                                    <select id="rj-status" class="form-select" wire:model="statusInput">
                                        @foreach ($statuses as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('showModal', false)">Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
