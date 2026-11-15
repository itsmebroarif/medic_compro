<?php

namespace App\Domains\Appointment\Actions;

use App\Domains\Appointment\DTOs\AppointmentBookingData;
use App\Domains\Appointment\Models\Appointment;
use App\Domains\Appointment\Services\BookingCodeGenerator;
use App\Domains\Appointment\Services\ScheduleValidationService;
use App\Domains\Medical\Models\Doctor;
use Illuminate\Support\Facades\DB;

class CreateAppointmentAction
{
    public function __construct(
        private readonly BookingCodeGenerator $bookingCodes,
        private readonly ScheduleValidationService $scheduleValidation,
    ) {}

    public function execute(AppointmentBookingData $data): Appointment
    {
        $doctor = Doctor::query()->findOrFail($data->doctorId);

        return DB::transaction(function () use ($data, $doctor): Appointment {
            $this->scheduleValidation->validate($doctor, $data->appointmentDate);

            return Appointment::create([
                ...$data->toArray(),
                'booking_code' => $this->bookingCodes->generate(),
                'status' => 'pending',
            ]);
        });
    }
}
