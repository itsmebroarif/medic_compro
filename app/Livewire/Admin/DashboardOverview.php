<?php

namespace App\Livewire\Admin;

use App\Domains\Appointment\Enums\AppointmentStatus;
use App\Domains\Appointment\Models\Appointment;
use App\Domains\Content\Models\Article;
use App\Domains\MasterData\Models\Medicine;
use App\Domains\MasterData\Models\Patient;
use App\Domains\Medical\Models\Department;
use App\Domains\Medical\Models\Doctor;
use App\Domains\Visit\Models\InpatientVisit;
use App\Domains\Visit\Models\OutpatientVisit;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin', ['title' => 'Dashboard'])]
class DashboardOverview extends Component
{
    #[Computed]
    public function stats(): array
    {
        return [
            'appointments_today' => Appointment::query()->whereDate('appointment_date', today())->count(),
            'appointments_pending' => Appointment::query()->where('status', AppointmentStatus::Pending->value)->count(),
            'doctors' => Doctor::query()->where('is_active', true)->count(),
            'departments' => Department::query()->where('is_active', true)->count(),
            'patients' => Patient::query()->count(),
            'inpatients_active' => InpatientVisit::query()->where('status', 'admitted')->count(),
            'outpatients_today' => OutpatientVisit::query()->whereDate('visit_date', today())->count(),
            'medicines_low' => Medicine::query()->where('is_active', true)->whereColumn('stock', '<=', 'min_stock')->count(),
            'articles_published' => Article::query()->where('status', 'published')->count(),
        ];
    }

    #[Computed]
    public function recentAppointments()
    {
        return Appointment::query()
            ->with('doctor.department')
            ->latest()
            ->take(8)
            ->get();
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    #[Computed]
    public function chart(): array
    {
        $labels = [];
        $values = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->translatedFormat('d M');
            $values[] = Appointment::query()->whereDate('appointment_date', $date)->count();
        }

        return ['labels' => $labels, 'values' => $values];
    }

    public function render()
    {
        return view('livewire.admin.dashboard-overview');
    }
}
