<?php

namespace App\Livewire\Admin;

use App\Domains\MasterData\Models\Patient;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Data Master Pasien'])]
class PatientManager extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $nik = '';

    public string $name = '';

    public string $dob = '';

    public string $gender = '';

    public string $phone = '';

    public string $address = '';

    public string $allergies = '';

    public bool $is_active = true;

    public string $flashMessage = '';

    protected function rules(): array
    {
        return [
            'nik' => ['nullable', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'dob' => ['required', 'date', 'before_or_equal:today'],
            'gender' => ['required', 'in:L,P'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:1000'],
            'allergies' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama pasien wajib diisi.',
            'dob.required' => 'Tanggal lahir wajib diisi.',
            'dob.before_or_equal' => 'Tanggal lahir tidak valid.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.regex' => 'NIK hanya boleh berisi angka.',
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
        $patient = Patient::query()->findOrFail($id);

        $this->editingId = $patient->id;
        $this->nik = (string) $patient->nik;
        $this->name = $patient->name;
        $this->dob = $patient->dob->format('Y-m-d');
        $this->gender = $patient->gender;
        $this->phone = (string) $patient->phone;
        $this->address = (string) $patient->address;
        $this->allergies = (string) $patient->allergies;
        $this->is_active = $patient->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'nik' => $validated['nik'] ?: null,
            'name' => $validated['name'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'phone' => $validated['phone'] ?: null,
            'address' => $validated['address'] ?: null,
            'allergies' => $validated['allergies'] ?: null,
            'is_active' => (bool) $validated['is_active'],
        ];

        if ($this->editingId) {
            Patient::query()->findOrFail($this->editingId)->update($data);
            $this->flashMessage = 'Data pasien berhasil diperbarui.';
        } else {
            DB::transaction(function () use ($data): void {
                $patient = Patient::query()->create([...$data, 'mr_number' => 'TMP-'.uniqid()]);
                $patient->update(['mr_number' => 'RM'.str_pad((string) $patient->id, 6, '0', STR_PAD_LEFT)]);
            });
            $this->flashMessage = 'Pasien berhasil didaftarkan.';
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $patient = Patient::query()->findOrFail($id);
        if ($patient->outpatientVisits()->exists() || $patient->inpatientVisits()->exists()) {
            $this->flashMessage = 'Pasien tidak dapat dihapus karena memiliki riwayat kunjungan.';

            return;
        }

        $patient->delete();
        $this->flashMessage = 'Pasien berhasil dihapus.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->nik = '';
        $this->name = '';
        $this->dob = '';
        $this->gender = '';
        $this->phone = '';
        $this->address = '';
        $this->allergies = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $patients = Patient::query()
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')->orWhere('mr_number', 'like', '%'.$this->search.'%')->orWhere('nik', 'like', '%'.$this->search.'%')))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.patient-manager', ['patients' => $patients]);
    }
}
