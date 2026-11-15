<?php

namespace App\Livewire\Admin;

use App\Core\Helpers\DateHelper;
use App\Domains\Appointment\Actions\UpdateAppointmentStatusAction;
use App\Domains\Appointment\Enums\AppointmentStatus;
use App\Domains\Appointment\Models\Appointment;
use App\Domains\Medical\Models\Department;
use App\Domains\Medical\Models\Doctor;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Janji Temu'])]
class AppointmentManager extends Component
{
    use WithPagination;

    public string $date = '';

    public string $department = '';

    public string $doctor = '';

    public string $status = '';

    public string $search = '';

    public string $flashMessage = '';

    /** @var array<int, string> Catatan internal per id appointment */
    public array $notes = [];

    public function updating(): void
    {
        $this->resetPage();
    }

    public function updateStatus(int $id, string $status, UpdateAppointmentStatusAction $action): void
    {
        $appointment = Appointment::query()->findOrFail($id);

        $action->execute(
            $appointment,
            AppointmentStatus::from($status),
            $this->notes[$id] ?? null,
        );

        $this->flashMessage = 'Status '.$appointment->booking_code.' diperbarui menjadi "'.AppointmentStatus::from($status)->label().'".';
    }

    public function saveNote(int $id): void
    {
        Appointment::query()->findOrFail($id)->update(['admin_notes' => $this->notes[$id] ?? null]);
        $this->flashMessage = 'Catatan internal disimpan.';
    }

    public function waLink(Appointment $appointment): string
    {
        $phone = preg_replace('/\D/', '', $appointment->patient_phone);
        $phone = preg_replace('/^0/', '62', (string) $phone);

        $message = sprintf(
            'Halo %s, kami dari pendaftaran RS. Janji temu Anda dengan kode %s pada %s telah kami konfirmasi.',
            $appointment->patient_name,
            $appointment->booking_code,
            DateHelper::formatDate($appointment->appointment_date),
        );

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }

    public function render()
    {
        $appointments = Appointment::query()
            ->with('doctor.department')
            ->when($this->date !== '', fn ($q) => $q->whereDate('appointment_date', $this->date))
            ->when($this->department !== '', fn ($q) => $q->whereHas('doctor', fn ($q) => $q->where('department_id', $this->department)))
            ->when($this->doctor !== '', fn ($q) => $q->where('doctor_id', $this->doctor))
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q->where('booking_code', 'like', '%'.$this->search.'%')->orWhere('patient_name', 'like', '%'.$this->search.'%')->orWhere('patient_phone', 'like', '%'.$this->search.'%')))
            ->orderByDesc('appointment_date')
            ->orderByDesc('id')
            ->paginate(10);

        foreach ($appointments as $appointment) {
            if (! isset($this->notes[$appointment->id])) {
                $this->notes[$appointment->id] = (string) $appointment->admin_notes;
            }
        }

        return view('livewire.admin.appointment-manager', [
            'appointments' => $appointments,
            'departments' => Department::query()->where('is_active', true)->orderBy('name')->get(),
            'doctors' => Doctor::query()->where('is_active', true)->orderBy('name')->get(),
            'statuses' => AppointmentStatus::options(),
        ]);
    }
}
