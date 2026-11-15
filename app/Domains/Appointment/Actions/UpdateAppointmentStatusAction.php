<?php

namespace App\Domains\Appointment\Actions;

use App\Domains\Appointment\Enums\AppointmentStatus;
use App\Domains\Appointment\Models\Appointment;

class UpdateAppointmentStatusAction
{
    public function execute(Appointment $appointment, AppointmentStatus $status, ?string $adminNotes = null): Appointment
    {
        $appointment->update([
            'status' => $status,
            'admin_notes' => $adminNotes ?? $appointment->admin_notes,
        ]);

        return $appointment->refresh();
    }
}
