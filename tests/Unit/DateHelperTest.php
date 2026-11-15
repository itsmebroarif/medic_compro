<?php

namespace Tests\Unit;

use App\Core\Helpers\DateHelper;
use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{
    public function test_day_name_returns_indonesian_day(): void
    {
        $this->assertSame('Senin', DateHelper::dayName(1));
        $this->assertSame('Minggu', DateHelper::dayName(7));
        $this->assertSame('-', DateHelper::dayName(9));
    }

    public function test_format_date_uses_indonesian_month(): void
    {
        $this->assertSame('16 November 2026', DateHelper::formatDate('2026-11-16'));
        $this->assertSame('-', DateHelper::formatDate(null));
    }
}
