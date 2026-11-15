<?php

declare(strict_types=1);

namespace Tests\Browser;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Vusys\Tetryon\Laravel\BrowserTestCase;
use Vusys\Tetryon\PHPUnit\Browser;
use Vusys\Tetryon\PHPUnit\Record;

#[Record]
final class ReorganiseTest extends BrowserTestCase
{
    use DatabaseTruncation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    #[Record('Move Operations under Engineering by dragging')]
    public function test_dragging_a_department_onto_another_moves_it_there(): void
    {
        $this->browser()
            ->beat('Open the chart', fn (Browser $b): Browser => $b->visit('/chart?on=2025-08-01'))
            ->beat('Drag Operations onto Engineering', fn (Browser $b): Browser => $b
                ->drag('[data-department="Operations"] > h2', '[data-department="Engineering"] > h2')
                ->waitForExpression("document.querySelector('[data-department=\"Operations\"]').style.marginLeft === '56px'"))
            ->assertExpressionEquals(
                "[...document.querySelectorAll('[data-department]')].map((d) => d.dataset.department).join(', ')",
                'Kestrel Labs, Engineering, Platform, Product, Operations, Support, Finance',
                label: 'Operations and Support now sit under Engineering',
            )
            ->assertTextNear('Support', 'Ivo Petrov');
    }

    #[Record('A department cannot move into its own team')]
    public function test_a_move_into_its_own_subtree_is_refused(): void
    {
        $this->browser()
            ->beat('Open the chart', fn (Browser $b): Browser => $b->visit('/chart'))
            ->beat('Drag Engineering onto Platform', fn (Browser $b): Browser => $b
                ->drag('[data-department="Engineering"] > h2', '[data-department="Platform"] > h2'))
            ->assertSee('Engineering cannot move under Platform, which is inside it.');
    }
}
