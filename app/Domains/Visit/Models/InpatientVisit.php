<?php

namespace App\Domains\Visit\Models;

use App\Domains\MasterData\Models\Patient;
use App\Domains\Medical\Models\Doctor;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['admission_number', 'patient_id', 'doctor_id', 'room_class', 'room_number', 'admission_date', 'discharge_date', 'diagnosis', 'status', 'notes'])]
class InpatientVisit extends Model
{
    protected function casts(): array
    {
        return [
            'admission_date' => 'datetime',
            'discharge_date' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
