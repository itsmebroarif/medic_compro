<?php

namespace App\Livewire\Web;

use App\Domains\Content\Models\Page;
use Illuminate\Support\Facades\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.medical')]
class PageDetail extends Component
{
    public Page $page;

    public function mount(string $slug): void
    {
        $this->page = Page::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        View::share('title', $this->page->title);
        View::share('metaDescription', $this->page->meta_description);
    }

    public function render()
    {
        return view('livewire.web.page-detail');
    }
}
