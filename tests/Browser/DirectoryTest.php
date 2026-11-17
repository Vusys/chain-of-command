<?php

declare(strict_types=1);

namespace Tests\Browser;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Vusys\Tetryon\PHPUnit\Browser;
use Vusys\Tetryon\PHPUnit\Record;
use Vusys\Tetryon\PHPUnit\ReuseBrowser;

/**
 * The people directory in Firefox: searching, narrowing to a department,
 * and paging with the filters kept. One browser for the class.
 */
#[Record]
#[ReuseBrowser]
final class DirectoryTest extends BrowserTestCase
{
    use DatabaseTruncation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    #[Record('Find the support team by searching')]
    public function test_searching_marks_the_matches(): void
    {
        $this->browser()
            ->beat('Open the directory in February 2026', fn (Browser $b): Browser => $b->visit('/people?on=2026-02-01'))
            ->assertSee('10 in post')
            ->beat('Search for support', fn (Browser $b): Browser => $b->fill('Search', 'support')->press('Find'))
            ->assertSee('2 in post')
            ->assertExpressionEquals("[...document.querySelectorAll('.directory mark')].map((m) => m.textContent).join(',')", 'Support,Support', label: 'both titles have the match marked')
            ->assertDontSee('Chen Wei');
    }

    #[Record('Narrow to Engineering, then page through it')]
    public function test_a_department_filter_survives_paging(): void
    {
        $this->browser()
            ->beat('Open the directory in August 2025', fn (Browser $b): Browser => $b->visit('/people?on=2025-08-01'))
            ->beat('Pick Engineering', fn (Browser $b): Browser => $b->select('Department', '— Engineering')->press('Find'))
            ->assertSee('5 in post')
            ->assertSee('Kestrel Labs › Engineering')
            ->assertDontSee('Chen Wei');
    }
}
