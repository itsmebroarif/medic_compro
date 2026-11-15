<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\Article;
use Illuminate\Support\Carbon;

class PublishArticleAction
{
    public function execute(Article $article, bool $publish): Article
    {
        $article->update([
            'status' => $publish ? 'published' : 'draft',
            'published_at' => $publish ? ($article->published_at ?? Carbon::now()) : $article->published_at,
        ]);

        return $article->refresh();
    }
}
