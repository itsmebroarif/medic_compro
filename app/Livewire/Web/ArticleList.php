<?php

namespace App\Livewire\Web;

use App\Domains\Content\Models\Article;
use App\Domains\Content\Models\ArticleCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.medical', ['title' => 'Artikel & Berita'])]
class ArticleList extends Component
{
    use WithPagination;

    #[Url(as: 'cari')]
    public string $search = '';

    #[Url(as: 'kategori')]
    public string $category = '';

    public function updating($property): void
    {
        if (in_array($property, ['search', 'category'], true)) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $articles = Article::query()
            ->published()
            ->with('category')
            ->when($this->search !== '', fn ($q) => $q->where(fn ($q) => $q->where('title', 'like', '%'.$this->search.'%')->orWhere('content', 'like', '%'.$this->search.'%')))
            ->when($this->category !== '', fn ($q) => $q->whereHas('category', fn ($q) => $q->where('slug', $this->category)))
            ->latest('published_at')
            ->paginate(6);

        return view('livewire.web.article-list', [
            'articles' => $articles,
            'categories' => ArticleCategory::query()->orderBy('name')->get(),
        ]);
    }
}
