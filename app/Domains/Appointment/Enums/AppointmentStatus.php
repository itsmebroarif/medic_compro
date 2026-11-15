<?php

namespace App\Domains\Appointment\Enums;

enum AppointmentStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Rescheduled = 'rescheduled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Konfirmasi',
            self::Confirmed => 'Dikonfirmasi',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
            self::Rescheduled => 'Dijadwal Ulang',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'text-bg-warning',
            self::Confirmed => 'text-bg-info',
            self::Completed => 'text-bg-success',
            self::Cancelled => 'text-bg-danger',
            self::Rescheduled => 'text-bg-secondary',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->all();
    }
}
