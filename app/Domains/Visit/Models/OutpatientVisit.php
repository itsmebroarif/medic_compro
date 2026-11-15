<?php

namespace App\Domains\Visit\Models;

use App\Domains\MasterData\Models\Patient;
use App\Domains\Medical\Models\Department;
use App\Domains\Medical\Models\Doctor;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['visit_number', 'patient_id', 'department_id', 'doctor_id', 'visit_date', 'complaint', 'diagnosis', 'notes', 'status'])]
class OutpatientVisit extends Model
{
    protected function casts(): array
    {
        return [
            'visit_date' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
