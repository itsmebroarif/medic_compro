<?php

namespace App\Livewire\Web;

use App\Core\Helpers\DateHelper;
use App\Domains\Medical\Models\Department;
use App\Domains\Medical\Queries\AvailableDoctorQuery;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.medical', ['title' => 'Direktori Dokter'])]
class DoctorDirectory extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'poli')]
    public string $department = '';

    #[Url(as: 'hari')]
    public string $day = '';

    public function resetFilters(): void
    {
        $this->reset(['search', 'department', 'day']);
    }

    public function render()
    {
        $doctors = app(AvailableDoctorQuery::class)->execute(
            $this->department !== '' ? (int) $this->department : null,
            $this->day !== '' ? (int) $this->day : null,
            $this->search !== '' ? $this->search : null,
        );

        return view('livewire.web.doctor-directory', [
            'doctors' => $doctors,
            'departments' => Department::query()->where('is_active', true)->orderBy('name')->get(),
            'days' => collect([1, 2, 3, 4, 5, 6, 7])->mapWithKeys(fn (int $d) => [$d => DateHelper::dayName($d)]),
        ]);
    }
}
