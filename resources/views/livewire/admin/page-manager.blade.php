<div>
    @if ($flashMessage)
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Halaman Web (Konten Statis)</h3>
            <button type="button" class="btn btn-primary btn-sm" wire:click="create"><i class="bi bi-plus-lg"></i> Tambah Halaman</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>#</th><th>Judul</th><th>Slug / URL</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($pages as $page)
                        <tr>
                            <td>{{ $pages->firstItem() + $loop->index }}</td>
                            <td><strong>{{ $page->title }}</strong></td>
                            <td><a href="{{ route('pages.show', $page->slug) }}" target="_blank"><code>/halaman/{{ $page->slug }}</code></a></td>
                            <td>
                                @if ($page->is_published)
                                    <span class="badge text-bg-success">Terbit</span>
                                @else
                                    <span class="badge text-bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-warning" wire:click="togglePublish({{ $page->id }})" title="{{ $page->is_published ? 'Jadikan draft' : 'Terbitkan' }}"><i class="bi {{ $page->is_published ? 'bi-eye-slash' : 'bi-send' }}"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $page->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $page->id }})" wire:confirm="Yakin hapus halaman ini?"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada halaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $pages->links('pagination::bootstrap-5') }}</div>
    </div>

    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5); overflow-y: auto;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Halaman' : 'Tambah Halaman' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label" for="p-title">Judul *</label>
                                    <input id="p-title" type="text" class="form-control" wire:model="title">
                                    @error('title') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="p-slug">Slug <span class="text-muted">(otomatis jika kosong)</span></label>
                                    <input id="p-slug" type="text" class="form-control" wire:model="slug" placeholder="kebijakan-privasi">
                                    @error('slug') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="p-content">Isi Halaman *</label>
                                    <textarea id="p-content" class="form-control summernote" wire:model="content">{{ $content }}</textarea>
                                    @error('content') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="p-meta">Meta Description</label>
                                    <input id="p-meta" type="text" class="form-control" wire:model="meta_description" maxlength="255">
                                    @error('meta_description') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input id="p-pub" type="checkbox" class="form-check-input" wire:model="is_published">
                                        <label class="form-check-label" for="p-pub">Tampilkan di web</label>
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
