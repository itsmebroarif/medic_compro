<?php

namespace App\Livewire\Web;

use App\Domains\Medical\Models\Doctor;
use Illuminate\Support\Facades\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.medical')]
class DoctorDetail extends Component
{
    public Doctor $doctor;

    public function mount(string $slug): void
    {
        $this->doctor = Doctor::query()
            ->with(['department', 'schedules'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        View::share('title', $this->doctor->name);
        View::share('metaDescription', 'Jadwal praktik dan profil '.$this->doctor->name.' - '.$this->doctor->department->name);
    }

    public function render()
    {
        return view('livewire.web.doctor-detail');
    }
}
