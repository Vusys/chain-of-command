<?php

declare(strict_types=1);

namespace Tests\Browser;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Vusys\Tetryon\PHPUnit\Browser;
use Vusys\Tetryon\PHPUnit\ReuseBrowser;

/**
 * The chart, driven in Firefox against the served app. DatabaseTruncation,
 * not RefreshDatabase: the server reads the rows this process seeds, so they
 * must be committed.
 */
#[ReuseBrowser(database: true)]
final class ChartTest extends BrowserTestCase
{
    use DatabaseTruncation;

    // Seeded once for the class; each test starts from this checkpoint.
    protected function authenticate(Browser $browser): void
    {
        $this->seed(DatabaseSeeder::class);
    }

    public function test_stepping_through_the_months_shows_a_transfer(): void
    {
        $this->browser()->recording('Farah moves from Platform to Product')
            ->beat('Open the chart in June 2025', fn (Browser $b): Browser => $b->visit('/chart?on=2025-06-01'))
            ->assertTextNear('Platform', 'Farah Haddad')
            ->beat('Step forward a month', fn (Browser $b): Browser => $b->click('Jul 2025 →'))
            ->assertSee('Kestrel Labs on 1 July 2025')
            ->assertTextNear('Product', 'Farah Haddad');
    }

    public function test_scrubbing_through_time_redraws_the_chart_in_place(): void
    {
        $this->browser()->recording('Scrub from August 2025 back to the founding')
            ->beat('Open the chart in August 2025', fn (Browser $b): Browser => $b->visit('/chart?on=2025-08-01'))
            ->assertSee('10 people')
            ->beat('Scrub back to January 2025', fn (Browser $b): Browser => $b
                ->fill('[data-as-of]', '0')
                ->waitForText('Kestrel Labs on 1 January 2025'))
            ->assertSee('6 people')
            ->assertDontSee('Farah Haddad')
            ->assertUrlIs('/chart?on=2025-01-01');
    }

    public function test_a_correction_is_hidden_as_we_knew_it_then(): void
    {
        $this->browser()->recording('Dana’s pay, now and as we knew it')
            ->beat('Open the chart in March 2025', fn (Browser $b): Browser => $b->visit('/chart?on=2025-03-01'))
            ->assertSee('£118,000')
            ->beat('Ask what we knew on that day', fn (Browser $b): Browser => $b
                ->fill('As we knew it on', '2025-03-01')
                ->press('Show'))
            ->assertSee('as we knew it on 1 March 2025')
            ->assertSee('£115,000')
            ->assertDontSee('£118,000');
    }
}
