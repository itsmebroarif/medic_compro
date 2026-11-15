<?php

namespace App\Domains\MasterData\Models;

use App\Domains\Medical\Models\Department;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['nip', 'name', 'gender', 'position', 'department_id', 'phone', 'email', 'address', 'join_date', 'is_active'])]
class Employee extends Model
{
    protected function casts(): array
    {
        return [
            'join_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
