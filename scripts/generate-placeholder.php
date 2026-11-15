<?php

/**
 * Placeholder dokter generik (silhouette) rasio 4:5 — public/generated/doctor-placeholder.png
 */
$w = 384;
$h = 480;
$img = imagecreatetruecolor($w, $h);
imagesavealpha($img, true);
$transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $transparent);

$navy1 = [21, 46, 66];
$navy2 = [13, 71, 92];

for ($y = 0; $y < $h; $y++) {
    $t = $y / $h;
    $c = [
        (int) ($navy1[0] + ($navy2[0] - $navy1[0]) * $t),
        (int) ($navy1[1] + ($navy2[1] - $navy1[1]) * $t),
        (int) ($navy1[2] + ($navy2[2] - $navy1[2]) * $t),
    ];
    imageline($img, 0, $y, $w, $y, imagecolorallocate($img, $c[0], $c[1], $c[2]));
}

$sil = imagecolorallocatealpha($img, 255, 255, 255, 30);
imagefilledellipse($img, (int) ($w / 2), 180, 132, 132, $sil);   // kepala
imagefilledellipse($img, (int) ($w / 2), 500, 290, 240, $sil);   // bahu/badan

$teal = imagecolorallocate($img, 45, 212, 191);
imagefilledrectangle($img, (int) ($w / 2) + 55, 26, (int) ($w / 2) + 67, 70, $teal);
imagefilledrectangle($img, (int) ($w / 2) + 33, 37, (int) ($w / 2) + 89, 59, $teal);

imagepng($img, 'public/generated/doctor-placeholder.png');

echo "doctor-placeholder.png regenerated (4:5)\n";
