<?php

declare(strict_types=1);

namespace Tests\Browser;

use App\Models\Person;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Vusys\Tetryon\Laravel\BrowserTestCase;
use Vusys\Tetryon\PHPUnit\Browser;
use Vusys\Tetryon\PHPUnit\Record;

#[Record]
final class PersonTest extends BrowserTestCase
{
    use DatabaseTruncation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    #[Record('Correct Farah’s starting salary')]
    public function test_correcting_a_period_keeps_the_old_belief(): void
    {
        $farah = Person::query()->where('email', 'farah@kestrel.test')->firstOrFail();

        $this->browser()
            ->beat('Open the chart in April 2025', fn (Browser $b): Browser => $b->visit('/chart?on=2025-04-01'))
            ->beat('Open Farah’s page', fn (Browser $b): Browser => $b->click('Farah Haddad'))
            ->assertPathIs("/people/{$farah->id}")
            ->beat('Correct her first period', fn (Browser $b): Browser => $b
                ->click('[data-period="2025-03-01"] summary')
                ->fill('[data-period="2025-03-01"] [name=salary]', '84000')
                ->press('[data-period="2025-03-01"] button'))
            ->assertSee('is corrected')
            ->assertSee('£84,000')
            ->assertVisible('.superseded');
    }
}
