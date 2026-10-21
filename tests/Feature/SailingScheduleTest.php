<?php

namespace Tests\Feature;

use App\Models\Sailing;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SailingScheduleTest extends TestCase
{
    private function sailing(): Sailing
    {
        return new Sailing(['cutoff_date' => '2026-10-05', 'sailing_date' => '2026-10-08', 'in_hand_date' => '2026-10-16']);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_the_cutoff_is_still_open_for_the_whole_cutoff_day_in_cayman_time(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-05 20:00', 'America/Cayman'));

        $this->assertFalse($this->sailing()->cutoffPassed());
    }

    public function test_the_cutoff_has_passed_from_the_next_cayman_day(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-06 00:30', 'America/Cayman'));

        $this->assertTrue($this->sailing()->cutoffPassed());
    }

    public function test_a_sailing_has_not_sailed_until_its_day_is_over(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-08 12:00', 'America/Cayman'));

        $this->assertFalse($this->sailing()->hasSailed());

        Carbon::setTestNow(Carbon::parse('2026-10-09 00:30', 'America/Cayman'));

        $this->assertTrue($this->sailing()->hasSailed());
    }
}
