<?php

namespace App\Domains\Content\Models;

use App\Core\Traits\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug'])]
class ArticleCategory extends Model
{
    use HasSlug;

    protected static function booted(): void
    {
        static::creating(function (ArticleCategory $category): void {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            }
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}
