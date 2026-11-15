<?php

declare(strict_types=1);

namespace Tests\Browser;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Vusys\Tetryon\Laravel\BrowserTestCase;
use Vusys\Tetryon\PHPUnit\Browser;

/**
 * The chart, driven in Firefox against the served app. DatabaseTruncation,
 * not RefreshDatabase: the server reads the rows this process seeds, so they
 * must be committed.
 */
final class ChartTest extends BrowserTestCase
{
    use DatabaseTruncation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_stepping_through_the_months_shows_a_transfer(): void
    {
        $this->browser()->recording('Farah moves from Platform to Product')
            ->beat('Open the chart in June 2025', fn (Browser $b) => $b->visit('/chart?on=2025-06-01'))
            ->assertTextNear('Platform', 'Farah Haddad')
            ->beat('Step forward a month', fn (Browser $b) => $b->click('Jul 2025 →'))
            ->assertSee('Kestrel Labs on 1 July 2025')
            ->assertTextNear('Product', 'Farah Haddad');
    }

    public function test_a_correction_is_hidden_as_we_knew_it_then(): void
    {
        $this->browser()->recording('Dana’s pay, now and as we knew it')
            ->beat('Open the chart in March 2025', fn (Browser $b) => $b->visit('/chart?on=2025-03-01'))
            ->assertSee('£118,000')
            ->beat('Ask what we knew on that day', fn (Browser $b) => $b
                ->fill('As we knew it on', '2025-03-01')
                ->press('Show'))
            ->assertSee('as we knew it on 1 March 2025')
            ->assertSee('£115,000')
            ->assertDontSee('£118,000');
    }
}
