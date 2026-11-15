<?php

namespace App\Livewire\Web;

use App\Domains\Content\Models\Article;
use App\Domains\Content\Models\SiteSetting;
use App\Domains\MasterData\Models\Patient;
use App\Domains\Medical\Models\Department;
use App\Domains\Medical\Models\Doctor;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.medical', ['title' => 'Beranda'])]
class HomePage extends Component
{
    #[Computed]
    public function departments()
    {
        return Department::query()->where('is_active', true)->withCount('doctors')->take(8)->get();
    }

    #[Computed]
    public function featuredDoctors()
    {
        return Doctor::query()->with(['department', 'schedules'])
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();
    }

    #[Computed]
    public function latestArticles()
    {
        return Article::query()->published()->with('category')->latest('published_at')->take(3)->get();
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'doctors' => Doctor::query()->where('is_active', true)->count(),
            'departments' => Department::query()->where('is_active', true)->count(),
            'patients' => Patient::query()->count(),
            'igd' => SiteSetting::get('igd_phone', '021 5550 1234'),
        ];
    }

    public function render()
    {
        return view('livewire.web.home-page');
    }
}
