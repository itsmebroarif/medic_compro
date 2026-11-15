<?php

namespace App\Domains\Medical\Models;

use App\Core\Traits\HasSlug;
use App\Domains\MasterData\Models\Employee;
use App\Domains\Visit\Models\OutpatientVisit;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'icon', 'is_active'])]
class Department extends Model
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
        static::creating(function (Department $department): void {
            if (empty($department->slug)) {
                $department->slug = static::generateUniqueSlug($department->name);
            }
        });

        static::updating(function (Department $department): void {
            if ($department->isDirty('name') && blank($department->slug)) {
                $department->slug = static::generateUniqueSlug($department->name, 'slug', $department->id);
            }
        });
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function outpatientVisits(): HasMany
    {
        return $this->hasMany(OutpatientVisit::class);
    }
}
