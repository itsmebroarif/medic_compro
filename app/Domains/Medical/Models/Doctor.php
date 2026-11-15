<?php

namespace App\Domains\Medical\Models;

use App\Core\Traits\HasSlug;
use App\Domains\Appointment\Models\Appointment;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['department_id', 'name', 'slug', 'photo', 'sip_number', 'bio', 'is_active'])]
class Doctor extends Model
{
    use HasSlug;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Doctor $doctor): void {
            if (empty($doctor->slug)) {
                $doctor->slug = static::generateUniqueSlug($doctor->name);
            }
        });
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class)->orderBy('day_of_week')->orderBy('start_time');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function photoUrl(): ?string
    {
        return $this->photo ? asset('storage/'.$this->photo) : null;
    }
}
