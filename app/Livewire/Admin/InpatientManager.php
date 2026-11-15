<?php

namespace App\Livewire\Admin;

use App\Domains\MasterData\Models\Patient;
use App\Domains\Medical\Models\Doctor;
use App\Domains\Visit\Models\InpatientVisit;
use App\Domains\Visit\Services\VisitNumberGenerator;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Rawat Inap'])]
class InpatientManager extends Component
{
    use WithPagination;

    public string $status = 'admitted';

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public ?int $patient_id = null;

    public ?int $doctor_id = null;

    public string $room_class = 'Kelas 1';

    public string $room_number = '';

    public string $admission_date = '';

    public string $discharge_date = '';

    public string $diagnosis = '';

    public string $notes = '';

    public string $statusInput = 'admitted';

    public string $flashMessage = '';

    private const STATUSES = [
        'admitted' => 'Dirawat',
        'discharged' => 'Pulang',
        'cancelled' => 'Dibatalkan',
    ];

    public function updating(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->admission_date = now()->format('Y-m-d\TH:i');
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $admission = InpatientVisit::query()->findOrFail($id);

        $this->editingId = $admission->id;
        $this->patient_id = $admission->patient_id;
        $this->doctor_id = $admission->doctor_id;
        $this->room_class = (string) $admission->room_class;
        $this->room_number = (string) $admission->room_number;
        $this->admission_date = $admission->admission_date->format('Y-m-d\TH:i');
        $this->discharge_date = $admission->discharge_date?->format('Y-m-d\TH:i') ?? '';
        $this->diagnosis = (string) $admission->diagnosis;
        $this->notes = (string) $admission->notes;
        $this->statusInput = $admission->status;
        $this->showModal = true;
    }

    public function save(VisitNumberGenerator $numbers): void
    {
        $validated = $this->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'room_class' => ['nullable', 'string', 'max:50'],
            'room_number' => ['nullable', 'string', 'max:50'],
            'admission_date' => ['required', 'date'],
            'discharge_date' => ['nullable', 'date', 'after_or_equal:admission_date'],
            'diagnosis' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'statusInput' => ['required', 'in:admitted,discharged,cancelled'],
        ], [
            'patient_id.required' => 'Pasien wajib dipilih.',
            'admission_date.required' => 'Tanggal masuk wajib diisi.',
            'discharge_date.after_or_equal' => 'Tanggal keluar tidak boleh sebelum tanggal masuk.',
        ]);

        $data = [
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'] ?: null,
            'room_class' => $validated['room_class'] ?: null,
            'room_number' => $validated['room_number'] ?: null,
            'admission_date' => Carbon::parse($validated['admission_date']),
            'discharge_date' => $validated['discharge_date'] ? Carbon::parse($validated['discharge_date']) : null,
            'diagnosis' => $validated['diagnosis'] ?: null,
            'notes' => $validated['notes'] ?: null,
            'status' => $validated['statusInput'],
        ];

        if ($validated['statusInput'] === 'discharged' && ! $data['discharge_date']) {
            $data['discharge_date'] = now();
        }

        if ($this->editingId) {
            InpatientVisit::query()->findOrFail($this->editingId)->update($data);
            $this->flashMessage = 'Data rawat inap berhasil diperbarui.';
        } else {
            InpatientVisit::query()->create([...$data, 'admission_number' => $numbers->inpatient(Carbon::parse($validated['admission_date']))]);
            $this->flashMessage = 'Pasien rawat inap berhasil diinput.';
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function discharge(int $id): void
    {
        $admission = InpatientVisit::query()->findOrFail($id);

        if ($admission->status !== 'admitted') {
            $this->flashMessage = 'Hanya pasien dengan status "Dirawat" yang bisa dipulangkan.';

            return;
        }

        $admission->update(['status' => 'discharged', 'discharge_date' => now()]);
        $this->flashMessage = $admission->admission_number.' dipulangkan.';
    }

    public function delete(int $id): void
    {
        InpatientVisit::query()->findOrFail($id)->delete();
        $this->flashMessage = 'Data rawat inap berhasil dihapus.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->patient_id = null;
        $this->doctor_id = null;
        $this->room_class = 'Kelas 1';
        $this->room_number = '';
        $this->admission_date = '';
        $this->discharge_date = '';
        $this->diagnosis = '';
        $this->notes = '';
        $this->statusInput = 'admitted';
        $this->resetValidation();
    }

    public function render()
    {
        $admissions = InpatientVisit::query()
            ->with(['patient', 'doctor'])
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q->where('admission_number', 'like', '%'.$this->search.'%')->orWhereHas('patient', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))))
            ->orderByDesc('admission_date')
            ->paginate(10);

        return view('livewire.admin.inpatient-manager', [
            'admissions' => $admissions,
            'statuses' => self::STATUSES,
            'patients' => Patient::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'mr_number']),
            'doctors' => Doctor::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
