<?php

namespace App\Livewire\Web;

use App\Domains\Content\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.medical')]
class ArticleDetail extends Component
{
    public Article $article;

    /** @var Collection<int, Article> */
    public $related = [];

    public function mount(string $slug): void
    {
        $this->article = Article::query()
            ->published()
            ->with(['category', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();

        View::share('title', $this->article->title);
        View::share('metaDescription', $this->article->meta_description ?: str(strip_tags($this->article->content))->limit(155)->toString());

        $this->related = Article::query()
            ->published()
            ->with('category')
            ->where('id', '!=', $this->article->id)
            ->when($this->article->category_id, fn ($q) => $q->where('category_id', $this->article->category_id))
            ->latest('published_at')
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.web.article-detail');
    }
}
