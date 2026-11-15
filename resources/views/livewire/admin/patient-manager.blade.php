<div>
    @if ($flashMessage)
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <h3 class="card-title mb-0">Data Master Pasien</h3>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari nama / RM / NIK..." wire:model.live.debounce.300ms="search">
                <button type="button" class="btn btn-primary btn-sm text-nowrap" wire:click="create"><i class="bi bi-plus-lg"></i> Tambah Pasien</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>No. RM</th><th>Nama</th><th>Lahir</th><th>JK</th><th>Telepon</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($patients as $patient)
                        <tr>
                            <td><code>{{ $patient->mr_number }}</code></td>
                            <td><strong>{{ $patient->name }}</strong>@if($patient->nik)<br><span class="small text-muted">NIK: {{ $patient->nik }}</span>@endif</td>
                            <td>{{ \App\Core\Helpers\DateHelper::formatDate($patient->dob) }} <span class="small text-muted">({{ $patient->dob->age }} th)</span></td>
                            <td>{{ $patient->gender }}</td>
                            <td>{{ $patient->phone ?: '-' }}</td>
                            <td>@if ($patient->is_active)<span class="badge text-bg-success">Aktif</span>@else<span class="badge text-bg-secondary">Nonaktif</span>@endif</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $patient->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $patient->id }})" wire:confirm="Yakin hapus pasien ini?"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pasien.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $patients->links('pagination::bootstrap-5') }}</div>
    </div>

    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5); overflow-y: auto;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Pasien' : 'Tambah Pasien' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label" for="p-name">Nama Lengkap *</label>
                                    <input id="p-name" type="text" class="form-control" wire:model="name">
                                    @error('name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="p-nik">NIK <span class="text-muted">(16 digit)</span></label>
                                    <input id="p-nik" type="text" class="form-control" wire:model="nik" inputmode="numeric">
                                    @error('nik') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="p-dob">Tanggal Lahir *</label>
                                    <input id="p-dob" type="date" class="form-control" wire:model="dob">
                                    @error('dob') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="p-gender">Jenis Kelamin *</label>
                                    <select id="p-gender" class="form-select" wire:model="gender">
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    @error('gender') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="p-phone">WhatsApp / Telepon</label>
                                    <input id="p-phone" type="text" class="form-control" wire:model="phone">
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="p-address">Alamat</label>
                                    <textarea id="p-address" class="form-control" rows="2" wire:model="address"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="p-allergies">Riwayat Alergi</label>
                                    <input id="p-allergies" type="text" class="form-control" wire:model="allergies" placeholder="Kosongkan jika tidak ada">
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input id="p-active" type="checkbox" class="form-check-input" wire:model="is_active">
                                        <label class="form-check-label" for="p-active">Pasien aktif</label>
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
