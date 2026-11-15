<?php

namespace App\Domains\MasterData\Models;

use App\Domains\Visit\Models\InpatientVisit;
use App\Domains\Visit\Models\OutpatientVisit;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['mr_number', 'nik', 'name', 'dob', 'gender', 'phone', 'address', 'allergies', 'is_active'])]
class Patient extends Model
{
    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function outpatientVisits(): HasMany
    {
        return $this->hasMany(OutpatientVisit::class);
    }

    public function inpatientVisits(): HasMany
    {
        return $this->hasMany(InpatientVisit::class);
    }
}
