<?php

namespace Tests\Feature;

use App\Domains\Appointment\Actions\CreateAppointmentAction;
use App\Domains\Appointment\DTOs\AppointmentBookingData;
use App\Domains\Medical\Models\Department;
use App\Domains\Medical\Models\Doctor;
use App\Domains\Medical\Models\DoctorSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    private Department $department;

    private Doctor $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->department = Department::query()->create(['name' => 'Poli Umum']);
        $this->doctor = Doctor::query()->create(['name' => 'dr. Uji Coba', 'department_id' => $this->department->id]);
    }

    private function bookingData(string $date): AppointmentBookingData
    {
        return new AppointmentBookingData(
            doctorId: $this->doctor->id,
            patientName: 'Pasien Uji Coba',
            patientPhone: '081234567890',
            patientDob: '1990-01-01',
            patientGender: 'L',
            mrNumber: null,
            appointmentDate: $date,
            complaint: 'Batuk dan demam',
        );
    }

    public function test_it_creates_appointment_when_doctor_has_schedule(): void
    {
        $nextMonday = Carbon::now()->next(Carbon::MONDAY)->toDateString();

        DoctorSchedule::query()->create([
            'doctor_id' => $this->doctor->id,
            'day_of_week' => 1,
            'start_time' => '08:00',
            'end_time' => '12:00',
            'quota' => 10,
        ]);

        $appointment = app(CreateAppointmentAction::class)->execute($this->bookingData($nextMonday));

        $this->assertStringStartsWith('APT-', $appointment->booking_code);
        $this->assertDatabaseHas('appointments', [
            'booking_code' => $appointment->booking_code,
            'status' => 'pending',
            'appointment_date' => $nextMonday,
        ]);
    }

    public function test_it_rejects_booking_when_doctor_has_no_schedule_that_day(): void
    {
        $nextMonday = Carbon::now()->next(Carbon::MONDAY)->toDateString();

        // Jadwal hanya hari Selasa, booking hari Senin harus ditolak.
        DoctorSchedule::query()->create([
            'doctor_id' => $this->doctor->id,
            'day_of_week' => 2,
            'start_time' => '08:00',
            'end_time' => '12:00',
        ]);

        $this->expectException(ValidationException::class);

        app(CreateAppointmentAction::class)->execute($this->bookingData($nextMonday));
    }

    public function test_it_rejects_booking_in_the_past(): void
    {
        DoctorSchedule::query()->create([
            'doctor_id' => $this->doctor->id,
            'day_of_week' => Carbon::now()->dayOfWeekIso,
            'start_time' => '00:00',
            'end_time' => '23:59',
        ]);

        $this->expectException(ValidationException::class);

        app(CreateAppointmentAction::class)->execute($this->bookingData(Carbon::now()->subDay()->toDateString()));
    }
}
