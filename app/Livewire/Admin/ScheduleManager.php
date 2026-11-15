<?php

namespace App\Livewire\Admin;

use App\Core\Helpers\DateHelper;
use App\Domains\Medical\Actions\SyncScheduleAction;
use App\Domains\Medical\Models\Doctor;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin', ['title' => 'Jadwal Praktik'])]
class ScheduleManager extends Component
{
    public ?int $doctor_id = null;

    /** @var array<int, array{day_of_week: int|string, start_time: string, end_time: string, quota: mixed, notes: string}> */
    public array $rows = [];

    public string $flashMessage = '';

    public string $flashType = 'info';

    public function mount(): void
    {
        $this->addRow();
    }

    public function updatedDoctorId(): void
    {
        $doctor = $this->doctor_id ? Doctor::query()->with('schedules')->find($this->doctor_id) : null;

        $this->rows = $doctor && $doctor->schedules->isNotEmpty()
            ? $doctor->schedules->map(fn ($schedule) => [
                'day_of_week' => $schedule->day_of_week,
                'start_time' => substr((string) $schedule->start_time, 0, 5),
                'end_time' => substr((string) $schedule->end_time, 0, 5),
                'quota' => $schedule->quota,
                'notes' => (string) $schedule->notes,
            ])->values()->all()
            : [];

        if (empty($this->rows)) {
            $this->addRow();
        }
    }

    public function addRow(): void
    {
        $this->rows[] = ['day_of_week' => 1, 'start_time' => '08:00', 'end_time' => '12:00', 'quota' => '', 'notes' => ''];
    }

    public function removeRow(int $index): void
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    public function save(SyncScheduleAction $action): void
    {
        $doctor = $this->doctor_id ? Doctor::query()->find($this->doctor_id) : null;

        if (! $doctor) {
            $this->flashType = 'danger';
            $this->flashMessage = 'Silakan pilih dokter terlebih dahulu.';

            return;
        }

        try {
            $action->execute($doctor, $this->rows);
            $this->flashType = 'success';
            $this->flashMessage = 'Jadwal praktik berhasil disimpan.';
            $this->updatedDoctorId();
        } catch (ValidationException $e) {
            $this->flashType = 'danger';
            $this->flashMessage = collect($e->errors())->flatten()->first() ?? 'Jadwal tidak valid.';
        }
    }

    public function render()
    {
        return view('livewire.admin.schedule-manager', [
            'doctors' => Doctor::query()->where('is_active', true)->with('department')->orderBy('name')->get(),
            'days' => collect([1, 2, 3, 4, 5, 6, 7])->mapWithKeys(fn (int $d) => [$d => DateHelper::dayName($d)]),
        ]);
    }
}
