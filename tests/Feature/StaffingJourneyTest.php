<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Journeys\StaffingJourney;
use Tests\TestCase;
use Vusys\Runabout\RunsJourneys;

class StaffingJourneyTest extends TestCase
{
    use RefreshDatabase;
    use RunsJourneys;

    public function test_staffing_holds_up_in_any_order(): void
    {
        $this->journey(StaffingJourney::class)->shuffles(100)->run();
    }
}
