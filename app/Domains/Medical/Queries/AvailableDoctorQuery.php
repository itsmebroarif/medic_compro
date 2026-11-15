<?php

namespace App\Domains\Medical\Queries;

use App\Domains\Medical\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;

class AvailableDoctorQuery
{
    /**
     * Dokter aktif, opsional difilter poli, hari praktik, dan kata kunci nama.
     *
     * @return Collection<int, Doctor>
     */
    public function execute(?int $departmentId = null, ?int $dayOfWeek = null, ?string $search = null): Collection
    {
        $query = Doctor::query()
            ->with(['department', 'schedules'])
            ->where('is_active', true);

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($dayOfWeek) {
            $query->whereHas('schedules', fn ($q) => $q->where('day_of_week', (int) $dayOfWeek));
        }

        if (filled($search)) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        return $query->orderBy('name')->get();
    }
}
