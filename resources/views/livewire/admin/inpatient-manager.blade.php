<div>
    @if ($flashMessage)
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <h3 class="card-title mb-0">Rawat Inap</h3>
            <button type="button" class="btn btn-primary btn-sm" wire:click="create"><i class="bi bi-plus-lg"></i> Input Rawat Inap</button>
        </div>
        <div class="card-body pb-0">
            <div class="row g-2">
                <div class="col-md-4">
                    <select class="form-select form-select-sm" wire:model.live="status" aria-label="Filter status">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari no. admit / nama pasien..." wire:model.live.debounce.300ms="search" aria-label="Cari">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>No. Admit</th><th>Pasien</th><th>DPJP</th><th>Kamar</th><th>Masuk</th><th>Keluar</th><th>Diagnosis</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($admissions as $admission)
                        <tr wire:key="admission-{{ $admission->id }}">
                            <td><code>{{ $admission->admission_number }}</code></td>
                            <td><strong>{{ $admission->patient?->name }}</strong><br><span class="small text-muted">{{ $admission->patient?->mr_number }}</span></td>
                            <td>{{ $admission->doctor?->name ?? '-' }}</td>
                            <td>{{ $admission->room_class ?: '-' }}<br><span class="small text-muted">{{ $admission->room_number ?: '-' }}</span></td>
                            <td>{{ $admission->admission_date->translatedFormat('d M Y H:i') }}</td>
                            <td>{{ $admission->discharge_date?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($admission->diagnosis, 50) }}</td>
                            <td>
                                @if ($admission->status === 'admitted')
                                    <span class="badge text-bg-danger">Dirawat</span>
                                @elseif ($admission->status === 'discharged')
                                    <span class="badge text-bg-success">Pulang</span>
                                @else
                                    <span class="badge text-bg-secondary">Batal</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($admission->status === 'admitted')
                                    <button type="button" class="btn btn-sm btn-success" wire:click="discharge({{ $admission->id }})" wire:confirm="Pulangkan pasien {{ $admission->patient?->name }}?" title="Pulangkan"><i class="bi bi-box-arrow-right"></i></button>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $admission->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $admission->id }})" wire:confirm="Yakin hapus?"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data rawat inap.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $admissions->links('pagination::bootstrap-5') }}</div>
    </div>

    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5); overflow-y: auto;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Rawat Inap' : 'Input Rawat Inap' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="ri-patient">Pasien *</label>
                                    <select id="ri-patient" class="form-select" wire:model="patient_id">
                                        <option value="">-- Pilih Pasien --</option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->mr_number }} — {{ $patient->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('patient_id') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="ri-doctor">Dokter DPJP</label>
                                    <select id="ri-doctor" class="form-select" wire:model="doctor_id">
                                        <option value="">-- Pilih Dokter --</option>
                                        @foreach ($doctors as $doc)
                                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="ri-class">Kelas Kamar</label>
                                    <select id="ri-class" class="form-select" wire:model="room_class">
                                        @foreach (['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'ICU', 'NICU', 'Isolasi'] as $class)
                                            <option value="{{ $class }}">{{ $class }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="ri-room">No. Kamar</label>
                                    <input id="ri-room" type="text" class="form-control" wire:model="room_number" placeholder="A-12">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="ri-status">Status *</label>
                                    <select id="ri-status" class="form-select" wire:model="statusInput">
                                        @foreach ($statuses as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="ri-admit">Tanggal & Jam Masuk *</label>
                                    <input id="ri-admit" type="datetime-local" class="form-control" wire:model="admission_date">
                                    @error('admission_date') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="ri-discharge">Tanggal & Jam Keluar</label>
                                    <input id="ri-discharge" type="datetime-local" class="form-control" wire:model="discharge_date">
                                    @error('discharge_date') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="ri-diagnosis">Diagnosis</label>
                                    <textarea id="ri-diagnosis" class="form-control" rows="2" wire:model="diagnosis"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="ri-notes">Catatan</label>
                                    <textarea id="ri-notes" class="form-control" rows="2" wire:model="notes"></textarea>
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
