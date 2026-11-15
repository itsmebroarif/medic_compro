<div>
    @if ($flashMessage)
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <h3 class="card-title mb-0">Data Master Karyawan</h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari nama / NIP..." wire:model.live.debounce.300ms="search">
                <button type="button" class="btn btn-primary btn-sm text-nowrap" wire:click="create"><i class="bi bi-plus-lg"></i> Tambah Karyawan</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>NIP</th><th>Nama</th><th>Jabatan</th><th>Unit/Poli</th><th>Telepon</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr>
                            <td><code>{{ $employee->nip ?: '-' }}</code></td>
                            <td><strong>{{ $employee->name }}</strong><br><span class="small text-muted">{{ $employee->gender === 'L' ? 'Laki-laki' : ($employee->gender === 'P' ? 'Perempuan' : '') }}</span></td>
                            <td>{{ $employee->position ?: '-' }}</td>
                            <td>{{ $employee->department?->name ?? '-' }}</td>
                            <td>{{ $employee->phone ?: '-' }}</td>
                            <td>@if ($employee->is_active)<span class="badge text-bg-success">Aktif</span>@else<span class="badge text-bg-secondary">Nonaktif</span>@endif</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $employee->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $employee->id }})" wire:confirm="Yakin hapus karyawan ini?"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data karyawan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $employees->links('pagination::bootstrap-5') }}</div>
    </div>

    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5); overflow-y: auto;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Karyawan' : 'Tambah Karyawan' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="e-nip">NIP</label>
                                    <input id="e-nip" type="text" class="form-control" wire:model="nip">
                                    @error('nip') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label" for="e-name">Nama Lengkap *</label>
                                    <input id="e-name" type="text" class="form-control" wire:model="name">
                                    @error('name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="e-gender">Jenis Kelamin</label>
                                    <select id="e-gender" class="form-select" wire:model="gender">
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="e-position">Jabatan</label>
                                    <input id="e-position" type="text" class="form-control" wire:model="position" placeholder="Perawat / Nakes / Administrasi">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="e-dept">Unit / Poli</label>
                                    <select id="e-dept" class="form-select" wire:model="department_id">
                                        <option value="">-- Tidak ada --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="e-phone">Telepon</label>
                                    <input id="e-phone" type="text" class="form-control" wire:model="phone">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="e-email">Email</label>
                                    <input id="e-email" type="email" class="form-control" wire:model="email">
                                    @error('email') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="e-join">Tanggal Bergabung</label>
                                    <input id="e-join" type="date" class="form-control" wire:model="join_date">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="e-address">Alamat</label>
                                    <textarea id="e-address" class="form-control" rows="2" wire:model="address"></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input id="e-active" type="checkbox" class="form-check-input" wire:model="is_active">
                                        <label class="form-check-label" for="e-active">Karyawan aktif</label>
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
