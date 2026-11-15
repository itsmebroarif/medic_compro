<div>
    @if ($flashMessage)
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <h3 class="card-title mb-0">Artikel & Berita</h3>
            <div class="d-flex gap-2 flex-wrap">
                <input type="text" class="form-control form-control-sm" placeholder="Cari judul..." wire:model.live.debounce.300ms="search">
                <select class="form-select form-select-sm" wire:model.live="status" style="width: auto;">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
                <button type="button" class="btn btn-primary btn-sm" wire:click="create"><i class="bi bi-plus-lg"></i> Tulis Artikel</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>Thumb</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Terbit</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr>
                            <td><img src="{{ $article->thumbnailUrl() ?? asset('generated/articles/article-' . (($article->id % 6) + 1) . '.jpg') }}" alt="" style="width: 72px; height: 48px; object-fit: cover; border-radius: 6px;"></td>
                            <td>
                                <strong>{{ $article->title }}</strong><br>
                                <span class="small text-muted">Oleh {{ $article->author?->name ?? '-' }}</span>
                            </td>
                            <td>{{ $article->category?->name ?? '-' }}</td>
                            <td>
                                @if ($article->status === 'published')
                                    <span class="badge text-bg-success">Published</span>
                                @else
                                    <span class="badge text-bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $article->published_at ? \App\Core\Helpers\DateHelper::formatDate($article->published_at) : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat"><i class="bi bi-eye"></i></a>
                                <button type="button" class="btn btn-sm {{ $article->status === 'published' ? 'btn-outline-warning' : 'btn-outline-success' }}" wire:click="togglePublish({{ $article->id }})" title="{{ $article->status === 'published' ? 'Jadikan draft' : 'Terbitkan' }}"><i class="bi {{ $article->status === 'published' ? 'bi-arrow-counterclockwise' : 'bi-send' }}"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $article->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $article->id }})" wire:confirm="Yakin hapus artikel ini?"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada artikel.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $articles->links('pagination::bootstrap-5') }}</div>
    </div>

    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5); overflow-y: auto;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label" for="a-title">Judul *</label>
                                    <input id="a-title" type="text" class="form-control" wire:model="title" placeholder="Judul artikel">
                                    @error('title') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label" for="a-cat">Kategori</label>
                                    <div class="input-group">
                                        <select id="a-cat" class="form-select" wire:model="category_id">
                                            <option value="">-- Tanpa Kategori --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-outline-secondary" type="button" wire:click="$toggle('showNewCategory')" title="Tambah kategori baru"><i class="bi bi-plus-lg"></i></button>
                                    </div>
                                    @if ($showNewCategory)
                                        <div class="input-group mt-2">
                                            <input type="text" class="form-control" placeholder="Nama kategori baru" wire:model="newCategoryName" wire:keydown.enter.prevent="addCategory">
                                            <button class="btn btn-success" type="button" wire:click="addCategory">Tambah</button>
                                        </div>
                                        @error('newCategoryName') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="a-status">Status *</label>
                                    <select id="a-status" class="form-select" wire:model="statusInput">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="a-date">Jadwal Terbit</label>
                                    <input id="a-date" type="datetime-local" class="form-control" wire:model="published_at">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="a-thumb">Thumbnail <span class="text-muted">(WebP otomatis, maks 2 MB)</span></label>
                                    <input id="a-thumb" type="file" class="form-control" wire:model="thumbnail" accept="image/png,image/jpeg,image/webp">
                                    @error('thumbnail') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                    @if ($thumbnail)
                                        <img src="{{ $thumbnail->temporaryUrl() }}" alt="Preview" style="height: 80px; border-radius: 8px; margin-top: 8px;">
                                    @elseif ($existingThumbnail)
                                        <img src="{{ asset('storage/' . $existingThumbnail) }}" alt="Thumbnail saat ini" style="height: 80px; border-radius: 8px; margin-top: 8px;">
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="a-content">Isi Artikel *</label>
                                    <textarea id="a-content" class="form-control summernote" wire:model="content">{{ $content }}</textarea>
                                    @error('content') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="a-meta">Meta Description <span class="text-muted">(SEO)</span></label>
                                    <input id="a-meta" type="text" class="form-control" wire:model="meta_description" maxlength="255" placeholder="Ringkasan singkat untuk mesin pencari">
                                    @error('meta_description') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
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
