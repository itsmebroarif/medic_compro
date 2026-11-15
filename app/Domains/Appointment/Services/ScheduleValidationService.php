<?php

namespace App\Domains\Appointment\Services;

use App\Domains\Appointment\Enums\AppointmentStatus;
use App\Domains\Appointment\Models\Appointment;
use App\Domains\Medical\Models\Doctor;
use App\Domains\Medical\Models\DoctorSchedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ScheduleValidationService
{
    /**
     * Validasi bahwa dokter praktik pada tanggal tersebut dan kuota belum penuh.
     *
     * @return array{schedules: Collection<int, DoctorSchedule>}
     */
    public function validate(Doctor $doctor, string $date): array
    {
        $visitDate = Carbon::parse($date)->startOfDay();

        if ($visitDate->isPast()) {
            throw ValidationException::withMessages([
                'appointmentDate' => 'Tanggal kunjungan tidak boleh di masa lalu.',
            ]);
        }

        $dayOfWeek = $visitDate->dayOfWeekIso;

        $schedules = $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        if ($schedules->isEmpty()) {
            throw ValidationException::withMessages([
                'appointmentDate' => 'Dokter tidak memiliki jadwal praktik pada tanggal tersebut.',
            ]);
        }

        $quotaTotal = $schedules->sum(fn (DoctorSchedule $schedule) => $schedule->quota ?? PHP_INT_MAX);

        if ($quotaTotal < PHP_INT_MAX) {
            $booked = Appointment::query()
                ->where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $visitDate->toDateString())
                ->whereIn('status', [AppointmentStatus::Pending->value, AppointmentStatus::Confirmed->value, AppointmentStatus::Rescheduled->value])
                ->count();

            if ($booked >= $quotaTotal) {
                throw ValidationException::withMessages([
                    'appointmentDate' => 'Kuota praktik dokter pada tanggal tersebut sudah penuh.',
                ]);
            }
        }

        return ['schedules' => $schedules];
    }

    /**
     * Daftar slot/jadwal yang tersedia untuk ditampilkan ke pasien.
     *
     * @return Collection<int, DoctorSchedule>
     */
    public function availableSlots(?Doctor $doctor, string $date): Collection
    {
        if (! $doctor || blank($date)) {
            return new Collection;
        }

        $dayOfWeek = Carbon::parse($date)->dayOfWeekIso;

        return $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();
    }
}
