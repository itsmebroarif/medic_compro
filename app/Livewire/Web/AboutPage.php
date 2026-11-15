<?php

namespace App\Livewire\Web;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.medical', ['title' => 'Tentang Kami'])]
class AboutPage extends Component
{
    public function render()
    {
        return view('livewire.web.about-page');
    }
}
