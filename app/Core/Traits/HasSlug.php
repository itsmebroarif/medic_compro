<?php

namespace App\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Generate slug unik dari nilai sumber (default kolom "name"/"title").
     *
     * Otomatis menambahkan akhiran -2, -3, ... jika slug sudah dipakai.
     */
    public static function generateUniqueSlug(string $source, string $column = 'slug', ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        if ($base === '') {
            $base = 'item';
        }

        $slug = $base;
        $suffix = 2;

        while (static::slugExists($slug, $column, $ignoreId)) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    protected static function slugExists(string $slug, string $column, ?int $ignoreId): bool
    {
        /** @var Model $model */
        $model = new static;

        $query = $model->newQuery()->where($column, $slug);
        if ($ignoreId) {
            $query->whereKeyNot($ignoreId);
        }

        return $query->exists();
    }
}
