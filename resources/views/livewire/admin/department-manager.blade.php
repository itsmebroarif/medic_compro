<div>
    @if ($flashMessage)
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <h3 class="card-title mb-0">Daftar Poli / Spesialisasi</h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari poli..." wire:model.live.debounce.300ms="search">
                <button type="button" class="btn btn-primary btn-sm text-nowrap" wire:click="create"><i class="bi bi-plus-lg"></i> Tambah Poli</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>#</th><th>Nama Poli</th><th>Slug</th><th>Jumlah Dokter</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($departments as $department)
                        <tr>
                            <td>{{ $departments->firstItem() + $loop->index }}</td>
                            <td><strong>{{ $department->name }}</strong></td>
                            <td><code>{{ $department->slug }}</code></td>
                            <td>{{ $department->doctors_count }}</td>
                            <td>
                                @if ($department->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $department->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $department->id }})" wire:confirm="Yakin hapus poli {{ $department->name }}?"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data poli.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $departments->links('pagination::bootstrap-5') }}</div>
    </div>

    {{-- Modal Form --}}
    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Poli' : 'Tambah Poli' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="d-name">Nama Poli *</label>
                                <input id="d-name" type="text" class="form-control" wire:model="name" placeholder="Misal: Poli Anak">
                                @error('name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="d-desc">Deskripsi</label>
                                <textarea id="d-desc" class="form-control" rows="3" wire:model="description"></textarea>
                                @error('description') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="d-icon">Icon <span class="text-muted">(opsional, mis. icon-cardiogram)</span></label>
                                <input id="d-icon" type="text" class="form-control" wire:model="icon">
                                @error('icon') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-check form-switch">
                                <input id="d-active" type="checkbox" class="form-check-input" wire:model="is_active">
                                <label class="form-check-label" for="d-active">Poli aktif</label>
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
