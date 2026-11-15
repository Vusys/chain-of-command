<?php

namespace Tests\Journeys;

use App\Models\Assignment;
use App\Models\Department;
use App\Models\Person;
use App\Org\Staffing;
use Carbon\CarbonImmutable;
use DomainException;
use PHPUnit\Framework\Assert;
use Vusys\Runabout\Context;
use Vusys\Runabout\Invariant;
use Vusys\Runabout\Journey;
use Vusys\Runabout\Step;

/**
 * A company's life in any order: hires under whoever is already there,
 * transfers, promotions, backdated pay fixes, leavers and the months between
 * them. Every write is dated "today", and today moves.
 */
final class StaffingJourney extends Journey
{
    public function steps(): array
    {
        $staffing = new Staffing;

        return [
            Step::make('found the company')
                ->act(function (Context $ctx) use ($staffing): void {
                    $ctx->travelTo('2025-01-01');
                    $company = new Department(['name' => 'Company']);
                    $company->saveAsRoot();
                    foreach (['Engineering', 'Operations'] as $name) {
                        (new Department(['name' => $name]))->appendToNode($company->refresh())->save();
                    }
                    $staffing->hire('Founder', 'founder@example.test', $company, null, 'Chief Executive', 150000, $this->today());
                })
                ->assert(fn () => Assert::assertSame(1, $this->employed()->count())),

            Step::make('a month passes')
                ->after('found the company')
                ->repeatable(max: 6)
                ->act(fn (Context $ctx) => $ctx->travel('+1 month')),

            Step::make('hire someone')
                ->after('found the company')
                ->repeatable(max: 4)
                ->act(function (Context $ctx) use ($staffing): void {
                    $n = Person::query()->count() + 1;
                    $staffing->hire(
                        "Person {$n}",
                        "person{$n}@example.test",
                        $ctx->pick(Department::query()->get()->all()),
                        $ctx->pick($this->employed()->all()),
                        'Engineer',
                        $ctx->pick([50000, 60000, 70000]),
                        $this->today(),
                    );
                }),

            Step::make('transfer someone')
                ->after('hire someone')
                ->repeatable(max: 3)
                ->when(fn () => $this->staff()->isNotEmpty())
                ->act(function (Context $ctx) use ($staffing): void {
                    $person = $ctx->pick($this->staff()->all());
                    $managers = $this->employed()->reject(fn (Person $p) => $p->is($person))->values()->all();
                    $manager = $ctx->pick($managers);
                    $ctx->remember('would loop', $staffing->wouldLoop($person, $manager, $this->today()));
                    try {
                        $staffing->transfer($person, $ctx->pick(Department::query()->get()->all()), $manager, $this->today());
                        $ctx->remember('transfer refused', false);
                    } catch (DomainException) {
                        $ctx->remember('transfer refused', true);
                    }
                })
                ->assert(fn (Context $ctx) => Assert::assertSame($ctx->get('would loop'), $ctx->get('transfer refused'), 'Only a looping transfer may be refused.')),

            Step::make('promote someone')
                ->after('hire someone')
                ->repeatable(max: 3)
                ->when(fn () => $this->staff()->isNotEmpty())
                ->act(function (Context $ctx) use ($staffing): void {
                    $staffing->promote($ctx->pick($this->staff()->all()), 'Senior Engineer', $ctx->pick([80000, 90000]), $this->today());
                }),

            Step::make('correct a past salary')
                ->after('hire someone')
                ->repeatable(max: 2)
                ->when(fn () => $this->staff()->isNotEmpty())
                ->act(function (Context $ctx) use ($staffing): void {
                    $person = $ctx->pick($this->staff()->all());
                    $segment = $ctx->pick($person->assignments()->currentKnowledge()->excludeRetractions()->get()->all());
                    $ctx->remember('corrected', [$person->id, $segment->valid_from, $salary = $segment->salary + 1000]);
                    $staffing->correct($person, ['salary' => $salary], $segment->valid_from, $segment->valid_to);
                })
                ->assert(function (Context $ctx): void {
                    [$id, $from, $salary] = $ctx->get('corrected');
                    Assert::assertSame($salary, Person::query()->findOrFail($id)->assignments()->validAt($from)->currentKnowledge()->sole()->salary);
                }),

            Step::make('someone leaves')
                ->after('hire someone')
                ->repeatable(max: 2)
                ->when(fn () => $this->staff()->isNotEmpty())
                ->act(function (Context $ctx) use ($staffing): void {
                    $person = $ctx->pick($this->staff()->all());
                    $ctx->remember('had reports', $staffing->reports($person, $this->today()) > 0);
                    $ctx->remember('leaver', $person->id);
                    try {
                        $staffing->leave($person, $this->today());
                        $ctx->remember('leave refused', false);
                    } catch (DomainException) {
                        $ctx->remember('leave refused', true);
                    }
                })
                ->assert(function (Context $ctx): void {
                    Assert::assertSame($ctx->get('had reports'), $ctx->get('leave refused'), 'Only someone with reports may be refused leave.');
                    Assert::assertSame($ctx->get('leave refused'), $this->employed()->contains('id', $ctx->get('leaver')));
                }),
        ];
    }

    public function invariants(): array
    {
        return [
            Invariant::make('nobody holds two assignments at once', function (): void {
                foreach (Person::query()->get() as $person) {
                    Assert::assertLessThanOrEqual(1, $person->assignments()->validAt($this->today())->currentKnowledge()->excludeRetractions()->count(), "{$person->name} has overlapping assignments.");
                }
            }),

            Invariant::make('every manager is employed while their reports are', function (): void {
                $employed = $this->employed()->modelKeys();
                foreach ($this->current() as $assignment) {
                    if ($assignment->manager_id !== null) {
                        Assert::assertContains($assignment->manager_id, $employed, "{$assignment->person->name} reports to someone who has left.");
                    }
                }
            }),

            Invariant::make('every chain of command ends at the top', function (): void {
                $managerOf = $this->current()->pluck('manager_id', 'person_id')->all();
                foreach (array_keys($managerOf) as $start) {
                    $seen = [];
                    for ($at = $start; $at !== null; $at = $managerOf[$at] ?? null) {
                        Assert::assertArrayNotHasKey($at, $seen, "Person {$start}'s chain of command loops.");
                        $seen[$at] = true;
                    }
                }
            }),

            Invariant::make('the department tree is intact', function (): void {
                Assert::assertFalse(Department::isBroken(), 'The nested set is broken.');
            }),

            Invariant::make('history is never destroyed', function (Context $ctx): void {
                $rows = Assignment::query()->count();
                Assert::assertGreaterThanOrEqual($ctx->get('assignment rows', 0), $rows, 'An assignment row disappeared.');
                $ctx->remember('assignment rows', $rows);
            }),
        ];
    }

    private function today(): CarbonImmutable
    {
        return CarbonImmutable::now()->startOfDay();
    }

    /** @return \Illuminate\Support\Collection<int, Assignment> */
    private function current()
    {
        return Assignment::query()->validAt($this->today())->currentKnowledge()->excludeRetractions()->get();
    }

    /** @return \Illuminate\Support\Collection<int, Person> */
    private function employed()
    {
        return $this->current()->map(fn (Assignment $assignment) => $assignment->person)->values();
    }

    /** Everyone employed except the founder. */
    private function staff()
    {
        return $this->employed()->reject(fn (Person $person) => $person->email === 'founder@example.test')->values();
    }
}
