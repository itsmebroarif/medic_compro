<?php

namespace App\Domains\Appointment\Services;

use App\Domains\Appointment\Models\Appointment;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class BookingCodeGenerator
{
    /**
     * Menghasilkan kode booking unik harian dengan format APT-YYYYMMDD-XXX.
     */
    public function generate(?CarbonInterface $date = null): string
    {
        $date = $date ? Carbon::instance($date) : now();
        $prefix = 'APT-'.$date->format('Ymd').'-';

        $last = Appointment::query()
            ->where('booking_code', 'like', $prefix.'%')
            ->orderByDesc('booking_code')
            ->value('booking_code');

        $sequence = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $matches)) {
            $sequence = ((int) $matches[1]) + 1;
        }

        return $prefix.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}
