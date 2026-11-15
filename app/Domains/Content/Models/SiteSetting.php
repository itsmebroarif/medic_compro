<?php

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value', 'group'])]
class SiteSetting extends Model
{
    protected $table = 'settings';

    private const CACHE_PREFIX = 'site_setting:';

    /**
     * Ambil nilai pengaturan (di-cache secara permanen sampai di-invalidate).
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever(self::CACHE_PREFIX.$key, function () use ($key, $default) {
            $value = static::query()->where('key', $key)->value('value');

            return $value ?? $default;
        });
    }

    /**
     * Simpan / perbarui sebuah pengaturan dan buang cache-nya.
     */
    public static function set(string $key, ?string $value, string $group = 'general'): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group],
        );

        Cache::forget(self::CACHE_PREFIX.$key);
    }

    /**
     * Buang seluruh cache pengaturan (dipanggil setelah bulk update dari admin).
     */
    public static function flushCache(): void
    {
        $keys = static::query()->pluck('key');

        foreach ($keys as $key) {
            Cache::forget(self::CACHE_PREFIX.$key);
        }
    }

    /**
     * @return array<string, string>
     */
    public static function allGrouped(): array
    {
        return static::query()->orderBy('group')->orderBy('key')->get()
            ->groupBy('group')
            ->map(fn ($items) => $items->pluck('value', 'key')->all())
            ->all();
    }
}
