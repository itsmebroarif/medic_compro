<?php

namespace App\Domains\Medical\Actions;

use App\Domains\Medical\Models\Doctor;
use App\Domains\Medical\Models\DoctorSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SyncScheduleAction
{
    /**
     * Validasi bentrok jadwal pada dokter yang sama, lalu ganti seluruh jadwal dengan input baru.
     *
     * @param  array<int, array{day_of_week: int, start_time: string, end_time: string, quota: ?int, notes: ?string}>  $schedules
     */
    public function execute(Doctor $doctor, array $schedules): void
    {
        $this->validateNoOverlap($schedules);

        DB::transaction(function () use ($doctor, $schedules): void {
            $doctor->schedules()->delete();

            foreach ($schedules as $schedule) {
                if (empty($schedule['start_time']) || empty($schedule['end_time'])) {
                    continue;
                }

                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => (int) $schedule['day_of_week'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'quota' => $schedule['quota'] !== '' && $schedule['quota'] !== null ? (int) $schedule['quota'] : null,
                    'notes' => $schedule['notes'] ?: null,
                ]);
            }
        });
    }

    /**
     * @param  array<int, array{day_of_week: int, start_time: string, end_time: string, quota: ?int, notes: ?string}>  $schedules
     */
    private function validateNoOverlap(array $schedules): void
    {
        $byDay = [];
        foreach ($schedules as $schedule) {
            if (empty($schedule['start_time']) || empty($schedule['end_time'])) {
                continue;
            }

            if ($schedule['start_time'] >= $schedule['end_time']) {
                throw ValidationException::withMessages([
                    'schedules' => 'Jam selesai harus lebih besar dari jam mulai pada hari ke-'.$schedule['day_of_week'].'.',
                ]);
            }

            $byDay[(int) $schedule['day_of_week']][] = $schedule;
        }

        foreach ($byDay as $day => $items) {
            usort($items, fn (array $a, array $b) => $a['start_time'] <=> $b['start_time']);

            for ($i = 1; $i < count($items); $i++) {
                if ($items[$i]['start_time'] < $items[$i - 1]['end_time']) {
                    throw ValidationException::withMessages([
                        'schedules' => 'Jadwal bentrok pada hari ke-'.$day.' ('.$items[$i - 1]['start_time'].' - '.$items[$i - 1]['end_time'].' vs '.$items[$i]['start_time'].' - '.$items[$i]['end_time'].').',
                    ]);
                }
            }
        }
    }
}
