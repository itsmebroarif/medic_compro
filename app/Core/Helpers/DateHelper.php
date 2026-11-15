<?php

namespace App\Core\Helpers;

use DateTimeInterface;

class DateHelper
{
    private const DAYS = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];

    private const MONTHS = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public static function dayName(int $dayOfWeek): string
    {
        return self::DAYS[$dayOfWeek] ?? '-';
    }

    public static function formatDate(DateTimeInterface|string|null $date, string $fallback = '-'): string
    {
        if (! $date) {
            return $fallback;
        }

        $dt = is_string($date) ? new \DateTimeImmutable($date) : $date;

        return $dt->format('j').' '.self::MONTHS[(int) $dt->format('n')].' '.$dt->format('Y');
    }

    public static function formatDateTime(DateTimeInterface|string|null $date, string $fallback = '-'): string
    {
        if (! $date) {
            return $fallback;
        }

        $dt = is_string($date) ? new \DateTimeImmutable($date) : $date;

        return static::formatDate($dt).', '.$dt->format('H:i').' WIB';
    }
}
