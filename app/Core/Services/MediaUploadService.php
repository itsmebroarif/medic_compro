<?php

namespace App\Core\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use RuntimeException;

class MediaUploadService
{
    /**
     * Resize dan konversi unggahan gambar ke format WebP, lalu simpan ke disk public.
     *
     * @return string Path relatif terhadap disk public (mis. "doctors/abc123.webp")
     */
    public function storeAsWebp(UploadedFile $file, string $directory, int $maxWidth = 1200, int $quality = 80): string
    {
        $manager = new ImageManager(new Driver);

        $image = $manager->decodePath($file->getRealPath());
        $image->scaleDown(width: $maxWidth);

        $filename = Str::random(40).'.webp';
        $path = trim($directory, '/').'/'.$filename;

        $encoded = $image->encode(new WebpEncoder($quality));

        if (! Storage::disk('public')->put($path, (string) $encoded)) {
            throw new RuntimeException('Gagal menyimpan gambar WebP ke storage.');
        }

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
