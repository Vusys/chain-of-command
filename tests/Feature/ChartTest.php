<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChartTest extends TestCase
{
    use RefreshDatabase;

    // Not $seed = true: that seeds once, outside the per-test transaction,
    // and the history leaks into every later test in the process.
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_the_chart_shows_the_company_on_a_date(): void
    {
        $this->get('/chart?on=2025-08-01')
            ->assertOk()
            ->assertSee('10 people')
            ->assertSeeInOrder(['Product', 'Farah Haddad', 'Engineer', 'reports to Gus Lindqvist']);
    }

    public function test_a_leaver_is_gone_after_their_last_day(): void
    {
        $this->get('/chart?on=2026-03-31')->assertSee('Hana Sato');
        $this->get('/chart?on=2026-04-01')->assertDontSee('Hana Sato')->assertSee('9 people');
    }

    public function test_a_correction_shows_now_but_not_as_we_knew_it_then(): void
    {
        $this->get('/chart?on=2025-03-01')->assertSee('£118,000')->assertDontSee('£115,000');
        $this->get('/chart?on=2025-03-01&known=2025-03-01')->assertSee('£115,000')->assertDontSee('£118,000');
    }

    public function test_the_footer_says_what_the_identity_map_answered(): void
    {
        $this->get('/chart?on=2025-08-01')->assertSee('answered from memory');
    }

    public function test_the_home_page_is_the_chart(): void
    {
        $this->get('/')->assertRedirect('/chart');
    }
}
