<?php

/**
 * Placeholder dokter generik (silhouette) — public/generated/doctor-placeholder.png
 */
$w = 312;
$h = 350;
$img = imagecreatetruecolor($w, $h);
imagesavealpha($img, true);
$transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $transparent);

$navy1 = [21, 46, 66];
$navy2 = [13, 71, 92];

// gradien vertikal
for ($y = 0; $y < $h; $y++) {
    $t = $y / $h;
    $c = [
        (int) ($navy1[0] + ($navy2[0] - $navy1[0]) * $t),
        (int) ($navy1[1] + ($navy2[1] - $navy1[1]) * $t),
        (int) ($navy1[2] + ($navy2[2] - $navy1[2]) * $t),
    ];
    $line = imagecolorallocate($img, $c[0], $c[1], $c[2]);
    imageline($img, 0, $y, $w, $y, $line);
}

// siluet orang (kepala + bahu) putih transparan
$sil = imagecolorallocatealpha($img, 255, 255, 255, 30);
imagefilledellipse($img, 156, 138, 108, 108, $sil);          // kepala
imagefilledellipse($img, 156, 355, 230, 190, $sil);          // bahu/badan

// tanda plus medis kecil di sudut
$teal = imagecolorallocate($img, 45, 212, 191);
imagefilledrectangle($img, 262, 22, 270, 54, $teal);
imagefilledrectangle($img, 246, 30, 286, 46, $teal);

imagepng($img, 'public/generated/doctor-placeholder.png');

echo "doctor-placeholder.png generated\n";
