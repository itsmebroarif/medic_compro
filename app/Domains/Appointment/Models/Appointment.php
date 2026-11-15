<?php

namespace App\Domains\Appointment\Models;

use App\Domains\Appointment\Enums\AppointmentStatus;
use App\Domains\Medical\Models\Doctor;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'booking_code',
    'doctor_id',
    'patient_name',
    'patient_phone',
    'patient_dob',
    'patient_gender',
    'mr_number',
    'appointment_date',
    'complaint',
    'status',
    'admin_notes',
])]
class Appointment extends Model
{
    protected function casts(): array
    {
        return [
            'patient_dob' => 'date',
            'appointment_date' => 'date',
            'status' => AppointmentStatus::class,
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
