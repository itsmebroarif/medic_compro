<?php

namespace App\Livewire\Admin;

use App\Domains\MasterData\Models\Employee;
use App\Domains\Medical\Models\Department;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Data Master Karyawan'])]
class EmployeeManager extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $nip = '';

    public string $name = '';

    public string $gender = '';

    public string $position = '';

    public ?int $department_id = null;

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $join_date = '';

    public bool $is_active = true;

    public string $flashMessage = '';

    protected function rules(): array
    {
        return [
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('employees', 'nip')->ignore($this->editingId)],
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'gender' => ['nullable', 'in:L,P'],
            'position' => ['nullable', 'string', 'max:150'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:1000'],
            'join_date' => ['nullable', 'date'],
            'is_active' => ['boolean'],
        ];
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
        $employee = Employee::query()->findOrFail($id);

        $this->editingId = $employee->id;
        $this->nip = (string) $employee->nip;
        $this->name = $employee->name;
        $this->gender = (string) $employee->gender;
        $this->position = (string) $employee->position;
        $this->department_id = $employee->department_id;
        $this->phone = (string) $employee->phone;
        $this->email = (string) $employee->email;
        $this->address = (string) $employee->address;
        $this->join_date = $employee->join_date?->format('Y-m-d') ?? '';
        $this->is_active = $employee->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'nip' => $validated['nip'] ?: null,
            'name' => $validated['name'],
            'gender' => $validated['gender'] ?: null,
            'position' => $validated['position'] ?: null,
            'department_id' => $validated['department_id'] ?: null,
            'phone' => $validated['phone'] ?: null,
            'email' => $validated['email'] ?: null,
            'address' => $validated['address'] ?: null,
            'join_date' => $validated['join_date'] ?: null,
            'is_active' => (bool) $validated['is_active'],
        ];

        if ($this->editingId) {
            Employee::query()->findOrFail($this->editingId)->update($data);
            $this->flashMessage = 'Karyawan berhasil diperbarui.';
        } else {
            Employee::query()->create($data);
            $this->flashMessage = 'Karyawan berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Employee::query()->findOrFail($id)->delete();
        $this->flashMessage = 'Karyawan berhasil dihapus.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->nip = '';
        $this->name = '';
        $this->gender = '';
        $this->position = '';
        $this->department_id = null;
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->join_date = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $employees = Employee::query()
            ->with('department')
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')->orWhere('nip', 'like', '%'.$this->search.'%')))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.employee-manager', [
            'employees' => $employees,
            'departments' => Department::query()->orderBy('name')->get(),
        ]);
    }
}
