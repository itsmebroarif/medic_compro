<div>
    @if ($flashMessage)
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Kategori Artikel</h3>
            <button type="button" class="btn btn-primary btn-sm" wire:click="create"><i class="bi bi-plus-lg"></i> Tambah Kategori</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>#</th><th>Nama</th><th>Slug</th><th>Jumlah Artikel</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $loop->index }}</td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>{{ $category->articles_count }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $category->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $category->id }})" wire:confirm="Yakin hapus kategori {{ $category->name }}?"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $categories->links('pagination::bootstrap-5') }}</div>
    </div>

    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Kategori' : 'Tambah Kategori' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label" for="cat-name">Nama Kategori *</label>
                            <input id="cat-name" type="text" class="form-control" wire:model="name" placeholder="Misal: Edukasi Kesehatan" autofocus>
                            @error('name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
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
