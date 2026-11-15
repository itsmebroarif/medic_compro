<div>
    @if ($flashMessage)
        <div class="alert alert-{{ $flashType }} alert-dismissible" role="alert">
            {{ $flashMessage }}
            <button type="button" class="btn-close" wire:click="$set('flashMessage', '')" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Pengguna & Role</h3>
            <button type="button" class="btn btn-primary btn-sm" wire:click="create"><i class="bi bi-plus-lg"></i> Tambah Pengguna</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $users->firstItem() + $loop->index }}</td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span class="badge text-bg-primary">{{ str_replace('-', ' ', strtoupper($role->name)) }}</span>
                                @endforeach
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $user->id }})"><i class="bi bi-pencil"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="delete({{ $user->id }})" wire:confirm="Yakin hapus pengguna ini?" @disabled($user->id === auth()->id())><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $users->links('pagination::bootstrap-5') }}</div>
    </div>

    @if ($showModal)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label" for="u-name">Nama *</label>
                                <input id="u-name" type="text" class="form-control" wire:model="name">
                                @error('name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="u-email">Email *</label>
                                <input id="u-email" type="email" class="form-control" wire:model="email">
                                @error('email') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="u-pass">Password {{ $editingId ? '(kosongkan jika tidak diubah)' : '*' }}</label>
                                <input id="u-pass" type="password" class="form-control" wire:model="password" autocomplete="new-password">
                                @error('password') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label" for="u-role">Role *</label>
                                <select id="u-role" class="form-select" wire:model="role">
                                    @foreach ($roles as $roleOption)
                                        <option value="{{ $roleOption->name }}">{{ str_replace('-', ' ', strtoupper($roleOption->name)) }}</option>
                                    @endforeach
                                </select>
                                @error('role') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
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
