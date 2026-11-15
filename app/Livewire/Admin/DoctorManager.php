<?php

namespace App\Livewire\Admin;

use App\Core\Services\MediaUploadService;
use App\Domains\Medical\Actions\UpsertDoctorAction;
use App\Domains\Medical\DTOs\DoctorData;
use App\Domains\Medical\Models\Department;
use App\Domains\Medical\Models\Doctor;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Data Dokter'])]
class DoctorManager extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public ?int $department_id = null;

    public string $sip_number = '';

    public string $bio = '';

    public bool $is_active = true;

    public $photo;

    public string $flashMessage = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'department_id' => ['required', 'exists:departments,id'],
            'sip_number' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['boolean'],
            'photo' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama dokter wajib diisi.',
            'department_id.required' => 'Silakan pilih poli.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
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
        $doctor = Doctor::query()->findOrFail($id);

        $this->editingId = $doctor->id;
        $this->name = $doctor->name;
        $this->department_id = $doctor->department_id;
        $this->sip_number = (string) $doctor->sip_number;
        $this->bio = (string) $doctor->bio;
        $this->is_active = $doctor->is_active;
        $this->showModal = true;
    }

    public function save(UpsertDoctorAction $action): void
    {
        $validated = $this->validate();

        $doctor = $this->editingId ? Doctor::query()->findOrFail($this->editingId) : null;

        $action->execute(
            new DoctorData(
                name: $validated['name'],
                departmentId: (int) $validated['department_id'],
                sipNumber: $validated['sip_number'] ?: null,
                bio: $validated['bio'] ?: null,
                isActive: (bool) $validated['is_active'],
                slug: $doctor?->slug,
            ),
            $doctor,
            $this->photo instanceof UploadedFile ? $this->photo : null,
        );

        $this->flashMessage = $doctor ? 'Data dokter berhasil diperbarui.' : 'Dokter berhasil ditambahkan.';
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id, MediaUploadService $media): void
    {
        $doctor = Doctor::query()->findOrFail($id);
        $media->delete($doctor->photo);
        $doctor->delete();

        $this->flashMessage = 'Dokter berhasil dihapus.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->department_id = null;
        $this->sip_number = '';
        $this->bio = '';
        $this->is_active = true;
        $this->photo = null;
        $this->resetValidation();
    }

    public function render()
    {
        $doctors = Doctor::query()
            ->with('department')
            ->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.doctor-manager', [
            'doctors' => $doctors,
            'departments' => Department::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
