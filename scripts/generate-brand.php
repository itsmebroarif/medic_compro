<?php

/**
 * Generator logo & favicon RS Medika Service (sekali jalan).
 * Menghasilkan:
 *  - public/generated/logo-dark.png   (untuk latar terang)
 *  - public/generated/logo-light.png  (untuk latar gelap)
 *  - public/generated/favicon.png     (ikon aplikasi)
 */
$teal = [13, 148, 136];      // #0d9488
$tealDark = [17, 94, 89];    // #115e59
$slate = [30, 41, 59];       // #1e293b
$white = [255, 255, 255];

function hex(array $c): int
{
    return ($c[0] << 16) | ($c[1] << 8) | $c[2];
}

function roundedRect($img, int $x1, int $y1, int $x2, int $y2, int $r, int $color): void
{
    imagefilledrectangle($img, $x1 + $r, $y1, $x2 - $r, $y2, $color);
    imagefilledrectangle($img, $x1, $y1 + $r, $x2, $y2 - $r, $color);
    imagefilledellipse($img, $x1 + $r, $y1 + $r, $r * 2, $r * 2, $color);
    imagefilledellipse($img, $x2 - $r, $y1 + $r, $r * 2, $r * 2, $color);
    imagefilledellipse($img, $x1 + $r, $y2 - $r, $r * 2, $r * 2, $color);
    imagefilledellipse($img, $x2 - $r, $y2 - $r, $r * 2, $r * 2, $color);
}

function drawIcon($img, int $ox, int $oy, int $size): void
{
    global $teal, $tealDark, $white;
    $r = (int) ($size * 0.22);

    // gradien ikon (dua setengah)
    $half = intdiv($size, 2);
    roundedRect($img, $ox, $oy, $ox + $half, $oy + $size, $r, imagecolorallocate($img, ...$tealDark));
    roundedRect($img, $ox + $half - 1, $oy, $ox + $size, $oy + $size, $r, imagecolorallocate($img, ...$teal));

    // cross putih
    $bar = (int) ($size * 0.26);
    $arm = (int) ($size * 0.62);
    $cx = $ox + intdiv($size, 2);
    $cy = $oy + intdiv($size, 2);
    $whiteC = imagecolorallocate($img, ...$white);
    imagefilledrectangle($img, $cx - intdiv($arm, 2), $cy - intdiv($bar, 2), $cx + intdiv($arm, 2), $cy + intdiv($bar, 2), $whiteC);
    imagefilledrectangle($img, $cx - intdiv($bar, 2), $cy - intdiv($arm, 2), $cx + intdiv($bar, 2), $cy + intdiv($arm, 2), $whiteC);
}

function makeLogo(string $path, bool $onDark): void
{
    global $slate, $white;

    $fontBold = 'C:/Windows/Fonts/ariblk.ttf';
    $fontReg = 'C:/Windows/Fonts/arialbd.ttf';

    $w = 760;
    $h = 150;
    $img = imagecreatetruecolor($w, $h);
    imagesavealpha($img, true);
    imagealphablending($img, true);
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagefill($img, 0, 0, $transparent);

    drawIcon($img, 8, 8, 134);

    $textColor = $onDark ? imagecolorallocate($img, 255, 255, 255) : imagecolorallocate($img, ...$slate);
    $subColor = $onDark ? imagecolorallocate($img, 178, 234, 228) : imagecolorallocate($img, 13, 148, 136);

    imagettftext($img, 52, 0, 165, 92, $textColor, $fontBold, 'Medika Service');
    imagettftext($img, 17, 0, 168, 125, $subColor, $fontReg, 'R U M A H   S A K I T');

    imagepng($img, $path);
    imagedestroy($img);
}

function makeFavicon(string $path): void
{
    $img = imagecreatetruecolor(128, 128);
    imagesavealpha($img, true);
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagefill($img, 0, 0, $transparent);
    drawIcon($img, 4, 4, 120);
    imagepng($img, $path);
    imagedestroy($img);
}

$base = 'public/generated';
makeLogo($base.'/logo-dark.png', false);
makeLogo($base.'/logo-light.png', true);
makeFavicon($base.'/favicon.png');

echo "logo & favicon generated\n";
