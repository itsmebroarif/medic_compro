<div>
    @if ($flashMessage)
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <h3 class="card-title mb-0">Data Master Obat</h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari kode / nama obat..." wire:model.live.debounce.300ms="search">
                <button type="button" class="btn btn-primary btn-sm text-nowrap" wire:click="create"><i class="bi bi-plus-lg"></i> Tambah Obat</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Stok</th><th>Harga</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($medicines as $medicine)
                        <tr class="{{ $medicine->isLowStock() ? 'table-warning' : '' }}">
                            <td><code>{{ $medicine->code }}</code></td>
                            <td><strong>{{ $medicine->name }}</strong>@if ($medicine->description)<br><span class="small text-muted">{{ $medicine->description }}</span>@endif</td>
                            <td>{{ $medicine->category ?: '-' }}</td>
                            <td>
                                {{ $medicine->stock }} {{ $medicine->unit }}
                                @if ($medicine->isLowStock())
                                    <span class="badge text-bg-warning ms-1">Menipis</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format((float) $medicine->price, 0, ',', '.') }}</td>
                            <td>@if ($medicine->is_active)<span class="badge text-bg-success">Aktif</span>@else<span class="badge text-bg-secondary">Nonaktif</span>@endif</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $medicine->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $medicine->id }})" wire:confirm="Yakin hapus obat ini?"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data obat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $medicines->links('pagination::bootstrap-5') }}</div>
    </div>

    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5); overflow-y: auto;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Obat' : 'Tambah Obat' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="m-code">Kode Obat *</label>
                                    <input id="m-code" type="text" class="form-control" wire:model="code" placeholder="OBT-0001">
                                    @error('code') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label" for="m-name">Nama Obat *</label>
                                    <input id="m-name" type="text" class="form-control" wire:model="name" placeholder="Paracetamol 500 mg">
                                    @error('name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="m-cat">Kategori</label>
                                    <input id="m-cat" type="text" class="form-control" wire:model="category" placeholder="Analgesik / Antibiotik / Vitamin">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="m-unit">Satuan *</label>
                                    <input id="m-unit" type="text" class="form-control" wire:model="unit" placeholder="pcs / strip / botol / ampul">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="m-stock">Stok *</label>
                                    <input id="m-stock" type="number" min="0" class="form-control" wire:model="stock">
                                    @error('stock') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="m-min">Stok Minimum</label>
                                    <input id="m-min" type="number" min="0" class="form-control" wire:model="min_stock">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="m-price">Harga (Rp) *</label>
                                    <input id="m-price" type="number" min="0" step="1" class="form-control" wire:model="price">
                                    @error('price') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="m-desc">Keterangan</label>
                                    <textarea id="m-desc" class="form-control" rows="2" wire:model="description"></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input id="m-active" type="checkbox" class="form-check-input" wire:model="is_active">
                                        <label class="form-check-label" for="m-active">Obat aktif / tersedia</label>
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
