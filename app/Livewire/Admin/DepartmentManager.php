<?php

namespace App\Livewire\Admin;

use App\Domains\Medical\Models\Department;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Data Master Poli'])]
class DepartmentManager extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public string $icon = '';

    public bool $is_active = true;

    public string $flashMessage = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'icon' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return ['name.required' => 'Nama poli wajib diisi.', 'name.min' => 'Nama minimal 3 karakter.'];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $department = Department::query()->findOrFail($id);

        $this->editingId = $department->id;
        $this->name = $department->name;
        $this->description = (string) $department->description;
        $this->icon = (string) $department->icon;
        $this->is_active = $department->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            Department::query()->findOrFail($this->editingId)->update($validated);
            $this->flashMessage = 'Poli berhasil diperbarui.';
        } else {
            Department::query()->create($validated);
            $this->flashMessage = 'Poli berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $department = Department::query()->withCount('doctors')->findOrFail($id);

        if ($department->doctors_count > 0) {
            $this->flashMessage = 'Poli tidak dapat dihapus karena masih memiliki '.$department->doctors_count.' dokter.';

            return;
        }

        $department->delete();
        $this->flashMessage = 'Poli berhasil dihapus.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->icon = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $departments = Department::query()
            ->withCount('doctors')
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.department-manager', compact('departments'));
    }
}
