<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin', ['title' => 'Pengguna & Role'])]
class UserManager extends Component
{
    use WithPagination;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'admin-pendaftaran';

    public string $flashMessage = '';

    public string $flashType = 'success';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($this->editingId)],
            'password' => [$this->editingId ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', Rule::exists('roles', 'name')],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi untuk pengguna baru.',
            'password.min' => 'Password minimal 8 karakter.',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $user = User::query()->findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->getRoleNames()->first() ?? 'admin-pendaftaran';
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = ['name' => $validated['name'], 'email' => $validated['email']];

        if ($validated['password'] !== '') {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($this->editingId) {
            $user = User::query()->findOrFail($this->editingId);
            $user->update($data);
            $user->syncRoles([$validated['role']]);
            $this->flashMessage = 'Pengguna berhasil diperbarui.';
        } else {
            $user = User::query()->create($data);
            $user->syncRoles([$validated['role']]);
            $this->flashMessage = 'Pengguna berhasil ditambahkan.';
        }

        $this->flashType = 'success';
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        if ($id === auth()->id()) {
            $this->flashType = 'danger';
            $this->flashMessage = 'Anda tidak dapat menghapus akun sendiri.';

            return;
        }

        $user = User::query()->findOrFail($id);
        $user->delete();

        $this->flashType = 'success';
        $this->flashMessage = 'Pengguna berhasil dihapus.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'admin-pendaftaran';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.user-manager', [
            'users' => User::query()->with('roles')->latest()->paginate(10),
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }
}
