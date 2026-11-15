<?php

namespace App\Livewire\Admin;

use App\Domains\MasterData\Models\Patient;
use App\Domains\Medical\Models\Department;
use App\Domains\Medical\Models\Doctor;
use App\Domains\Visit\Models\OutpatientVisit;
use App\Domains\Visit\Services\VisitNumberGenerator;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Rawat Jalan'])]
class OutpatientManager extends Component
{
    use WithPagination;

    public string $date = '';

    public string $department = '';

    public string $status = '';

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public ?int $patient_id = null;

    public ?int $department_id = null;

    public ?int $doctor_id = null;

    public string $visit_date = '';

    public string $complaint = '';

    public string $diagnosis = '';

    public string $notes = '';

    public string $statusInput = 'waiting';

    public string $flashMessage = '';

    private const STATUSES = [
        'waiting' => 'Menunggu',
        'in_progress' => 'Sedang Diperiksa',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public function updating(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->visit_date = now()->format('Y-m-d\TH:i');
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $visit = OutpatientVisit::query()->findOrFail($id);

        $this->editingId = $visit->id;
        $this->patient_id = $visit->patient_id;
        $this->department_id = $visit->department_id;
        $this->doctor_id = $visit->doctor_id;
        $this->visit_date = $visit->visit_date->format('Y-m-d\TH:i');
        $this->complaint = (string) $visit->complaint;
        $this->diagnosis = (string) $visit->diagnosis;
        $this->notes = (string) $visit->notes;
        $this->statusInput = $visit->status;
        $this->showModal = true;
    }

    public function save(VisitNumberGenerator $numbers): void
    {
        $validated = $this->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'visit_date' => ['required', 'date'],
            'complaint' => ['nullable', 'string', 'max:2000'],
            'diagnosis' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'statusInput' => ['required', 'in:waiting,in_progress,completed,cancelled'],
        ], [
            'patient_id.required' => 'Pasien wajib dipilih.',
            'department_id.required' => 'Poli wajib dipilih.',
            'visit_date.required' => 'Tanggal kunjungan wajib diisi.',
        ]);

        $data = [
            'patient_id' => $validated['patient_id'],
            'department_id' => $validated['department_id'],
            'doctor_id' => $validated['doctor_id'] ?: null,
            'visit_date' => Carbon::parse($validated['visit_date']),
            'complaint' => $validated['complaint'] ?: null,
            'diagnosis' => $validated['diagnosis'] ?: null,
            'notes' => $validated['notes'] ?: null,
            'status' => $validated['statusInput'],
        ];

        if ($this->editingId) {
            OutpatientVisit::query()->findOrFail($this->editingId)->update($data);
            $this->flashMessage = 'Kunjungan rawat jalan berhasil diperbarui.';
        } else {
            OutpatientVisit::query()->create([...$data, 'visit_number' => $numbers->outpatient(Carbon::parse($validated['visit_date']))]);
            $this->flashMessage = 'Kunjungan rawat jalan berhasil didaftarkan.';
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function updateStatus(int $id, string $status): void
    {
        if (array_key_exists($status, self::STATUSES)) {
            OutpatientVisit::query()->findOrFail($id)->update(['status' => $status]);
            $this->flashMessage = 'Status kunjungan diperbarui.';
        }
    }

    public function delete(int $id): void
    {
        OutpatientVisit::query()->findOrFail($id)->delete();
        $this->flashMessage = 'Kunjungan berhasil dihapus.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->patient_id = null;
        $this->department_id = null;
        $this->doctor_id = null;
        $this->visit_date = '';
        $this->complaint = '';
        $this->diagnosis = '';
        $this->notes = '';
        $this->statusInput = 'waiting';
        $this->resetValidation();
    }

    public function render()
    {
        $visits = OutpatientVisit::query()
            ->with(['patient', 'department', 'doctor'])
            ->when($this->date !== '', fn ($q) => $q->whereDate('visit_date', $this->date))
            ->when($this->department !== '', fn ($q) => $q->where('department_id', $this->department))
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q->where('visit_number', 'like', '%'.$this->search.'%')->orWhereHas('patient', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))))
            ->orderByDesc('visit_date')
            ->paginate(10);

        return view('livewire.admin.outpatient-manager', [
            'visits' => $visits,
            'statuses' => self::STATUSES,
            'patients' => Patient::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'mr_number']),
            'departments' => Department::query()->where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
