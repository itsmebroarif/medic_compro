<?php

namespace App\Domains\Medical\Models;

use App\Core\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['doctor_id', 'day_of_week', 'start_time', 'end_time', 'quota', 'notes'])]
class DoctorSchedule extends Model
{
    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'quota' => 'integer',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function dayName(): string
    {
        return DateHelper::dayName($this->day_of_week);
    }

    public function timeRange(): string
    {
        return substr((string) $this->start_time, 0, 5).' - '.substr((string) $this->end_time, 0, 5).' WIB';
    }
}
