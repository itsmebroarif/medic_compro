<?php

namespace App\Livewire\Admin;

use App\Domains\MasterData\Models\Medicine;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Data Master Obat'])]
class MedicineManager extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $code = '';

    public string $name = '';

    public string $category = '';

    public string $unit = 'pcs';

    public int $stock = 0;

    public int $min_stock = 0;

    public string $price = '0';

    public string $description = '';

    public bool $is_active = true;

    public string $flashMessage = '';

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('medicines', 'code')->ignore($this->editingId)],
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'category' => ['nullable', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:30'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'code.required' => 'Kode obat wajib diisi.',
            'code.unique' => 'Kode obat sudah digunakan.',
            'name.required' => 'Nama obat wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
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
        $medicine = Medicine::query()->findOrFail($id);

        $this->editingId = $medicine->id;
        $this->code = $medicine->code;
        $this->name = $medicine->name;
        $this->category = (string) $medicine->category;
        $this->unit = $medicine->unit;
        $this->stock = $medicine->stock;
        $this->min_stock = $medicine->min_stock;
        $this->price = (string) $medicine->price;
        $this->description = (string) $medicine->description;
        $this->is_active = $medicine->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            Medicine::query()->findOrFail($this->editingId)->update($validated);
            $this->flashMessage = 'Obat berhasil diperbarui.';
        } else {
            Medicine::query()->create($validated);
            $this->flashMessage = 'Obat berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Medicine::query()->findOrFail($id)->delete();
        $this->flashMessage = 'Obat berhasil dihapus.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->code = '';
        $this->name = '';
        $this->category = '';
        $this->unit = 'pcs';
        $this->stock = 0;
        $this->min_stock = 0;
        $this->price = '0';
        $this->description = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $medicines = Medicine::query()
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')->orWhere('code', 'like', '%'.$this->search.'%')))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.medicine-manager', ['medicines' => $medicines]);
    }
}
