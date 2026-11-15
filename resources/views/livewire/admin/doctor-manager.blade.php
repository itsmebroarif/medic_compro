<div>
    @if ($flashMessage)
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <h3 class="card-title mb-0">Data Dokter</h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari dokter..." wire:model.live.debounce.300ms="search">
                <button type="button" class="btn btn-primary btn-sm text-nowrap" wire:click="create"><i class="bi bi-plus-lg"></i> Tambah Dokter</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>Foto</th><th>Nama</th><th>Poli</th><th>SIP</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($doctors as $doctor)
                        <tr>
                            <td><img src="{{ $doctor->photoUrl() ?? asset('generated/doctor-placeholder.png') }}" alt="{{ $doctor->name }}" style="width: 56px; height: 56px; object-fit: cover; border-radius: 50%;"></td>
                            <td><strong>{{ $doctor->name }}</strong></td>
                            <td>{{ $doctor->department?->name }}</td>
                            <td>{{ $doctor->sip_number ?: '-' }}</td>
                            <td>
                                @if ($doctor->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $doctor->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $doctor->id }})" wire:confirm="Yakin hapus dokter {{ $doctor->name }}?"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data dokter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $doctors->links('pagination::bootstrap-5') }}</div>
    </div>

    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5); overflow-y: auto;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Dokter' : 'Tambah Dokter' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label" for="dc-name">Nama Dokter (beserta gelar) *</label>
                                    <input id="dc-name" type="text" class="form-control" wire:model="name" placeholder="dr. Andika Pratama, Sp.PD">
                                    @error('name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="dc-poli">Poli *</label>
                                    <select id="dc-poli" class="form-select" wire:model="department_id">
                                        <option value="">-- Pilih --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="dc-sip">No. SIP</label>
                                    <input id="dc-sip" type="text" class="form-control" wire:model="sip_number" placeholder="SIP/2024/001234">
                                    @error('sip_number') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="dc-photo">Foto Dokter <span class="text-muted">(otomatis WebP, maks 2 MB)</span></label>
                                    <input id="dc-photo" type="file" class="form-control" wire:model="photo" accept="image/png,image/jpeg,image/webp">
                                    @error('photo') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    @if ($photo)
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" style="height: 90px; margin-top: 8px; border-radius: 8px;">
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="dc-bio">Bio / Riwayat Pendidikan</label>
                                    <textarea id="dc-bio" class="form-control" rows="4" wire:model="bio"></textarea>
                                    @error('bio') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input id="dc-active" type="checkbox" class="form-check-input" wire:model="is_active">
                                        <label class="form-check-label" for="dc-active">Dokter aktif</label>
                                    </div>
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
