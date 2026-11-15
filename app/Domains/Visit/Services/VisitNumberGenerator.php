<?php

namespace App\Domains\Visit\Services;

use App\Domains\Visit\Models\InpatientVisit;
use App\Domains\Visit\Models\OutpatientVisit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class VisitNumberGenerator
{
    public function outpatient(?Carbon $date = null): string
    {
        return $this->generate(OutpatientVisit::class, 'visit_number', 'RJ', $date);
    }

    public function inpatient(?Carbon $date = null): string
    {
        return $this->generate(InpatientVisit::class, 'admission_number', 'RI', $date);
    }

    /**
     * @param  class-string<Model>  $modelClass
     */
    private function generate(string $modelClass, string $column, string $prefix, ?Carbon $date): string
    {
        $date ??= now();
        $fullPrefix = $prefix.'-'.$date->format('Ymd').'-';

        $last = $modelClass::query()
            ->where($column, 'like', $fullPrefix.'%')
            ->orderByDesc($column)
            ->value($column);

        $sequence = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $matches)) {
            $sequence = ((int) $matches[1]) + 1;
        }

        return $fullPrefix.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}
