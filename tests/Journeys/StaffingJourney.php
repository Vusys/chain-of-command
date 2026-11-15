<?php

declare(strict_types=1);

namespace Tests\Journeys;

use App\Models\Assignment;
use App\Models\Department;
use App\Models\Person;
use App\Org\Staffing;
use Carbon\CarbonImmutable;
use DomainException;
use Illuminate\Support\Collection;
use LogicException;
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
                    foreach (['Engineering' => ['Platform'], 'Operations' => ['Support']] as $name => $teams) {
                        $department = new Department(['name' => $name]);
                        $department->appendToNode($company->refresh())->save();
                        foreach ($teams as $team) {
                            (new Department(['name' => $team]))->appendToNode($department->refresh())->save();
                        }
                    }
                    $staffing->hire('Founder', 'founder@example.test', $company, null, 'Chief Executive', 150000, $this->today());
                })
                ->assert(fn () => Assert::assertSame(1, $this->employed()->count())),

            Step::make('a month passes')
                ->after('found the company')
                ->repeatable(max: 6)
                ->act(fn (Context $ctx) => $ctx->travel('+1 month')),

            Step::make('reorganise')
                ->after('found the company')
                ->repeatable(max: 3)
                ->act(function (Context $ctx): void {
                    $moving = $this->pickFrom($ctx, Department::query()->whereNotNull('parent_id')->get());
                    $target = $this->pickFrom($ctx, Department::query()->get());
                    $ctx->remember('move should be refused', $target->is($moving) || $target->isDescendantOf($moving));
                    $ctx->remember('moved', $moving->id);
                    $ctx->remember('under', $target->id);
                    try {
                        $moving->moveUnder($target);
                        $ctx->remember('move refused', false);
                    } catch (DomainException) {
                        $ctx->remember('move refused', true);
                    }
                })
                ->assert(function (Context $ctx): void {
                    Assert::assertSame($ctx->get('move should be refused'), $ctx->get('move refused'), 'Only a move into its own subtree may be refused.');
                    if ($ctx->get('move refused') === false) {
                        Assert::assertSame($ctx->integer('under'), Department::query()->findOrFail($ctx->integer('moved'))->parent_id);
                    }
                }),

            Step::make('hire someone')
                ->after('found the company')
                ->repeatable(max: 4)
                ->act(function (Context $ctx) use ($staffing): void {
                    $n = Person::query()->count() + 1;
                    $staffing->hire(
                        "Person {$n}",
                        "person{$n}@example.test",
                        $this->pickFrom($ctx, Department::query()->get()),
                        $this->pickFrom($ctx, $this->employed()),
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
                    $person = $this->pickFrom($ctx, $this->staff());
                    $manager = $this->pickFrom($ctx, $this->employed()->reject(fn (Person $p): bool => $p->is($person)));
                    $ctx->remember('would loop', $staffing->wouldLoop($person, $manager, $this->today()));
                    try {
                        $staffing->transfer($person, $this->pickFrom($ctx, Department::query()->get()), $manager, $this->today());
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
                    $staffing->promote($this->pickFrom($ctx, $this->staff()), 'Senior Engineer', $ctx->pick([80000, 90000]), $this->today());
                }),

            Step::make('correct a past salary')
                ->after('hire someone')
                ->repeatable(max: 2)
                ->when(fn () => $this->staff()->isNotEmpty())
                ->act(function (Context $ctx) use ($staffing): void {
                    $person = $this->pickFrom($ctx, $this->staff());
                    $segment = $this->pickFrom($ctx, Assignment::query()->whereTemporalEntity($person)->currentKnowledge()->excludeRetractions()->get());
                    $ctx->remember('corrected', $segment->id);
                    $staffing->correct($person, ['salary' => ($segment->salary ?? 0) + 1000], $segment->valid_from, $segment->valid_to);
                })
                ->assert(function (Context $ctx): void {
                    $before = Assignment::query()->findOrFail($ctx->integer('corrected'));
                    $now = Assignment::query()->where('person_id', $before->person_id)->validAt($before->valid_from)->currentKnowledge()->sole();
                    Assert::assertSame(($before->salary ?? 0) + 1000, $now->salary);
                }),

            Step::make('someone leaves')
                ->after('hire someone')
                ->repeatable(max: 2)
                ->when(fn () => $this->staff()->isNotEmpty())
                ->act(function (Context $ctx) use ($staffing): void {
                    $person = $this->pickFrom($ctx, $this->staff());
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
                $employed = $this->employed()->pluck('id')->all();
                foreach ($this->current() as $assignment) {
                    if ($assignment->manager_id !== null) {
                        Assert::assertContains($assignment->manager_id, $employed, "Person {$assignment->person_id} reports to someone who has left.");
                    }
                }
            }),

            Invariant::make('every chain of command ends at the top', function (): void {
                $managerOf = [];
                foreach ($this->current() as $assignment) {
                    $managerOf[$assignment->person_id] = $assignment->manager_id;
                }
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

    /**
     * A seeded draw from a collection, which pick() wants as a non-empty list.
     *
     * @template T
     *
     * @param  Collection<array-key, T>  $items
     * @return T
     */
    private function pickFrom(Context $ctx, Collection $items): mixed
    {
        $list = array_values($items->all());
        if ($list === []) {
            throw new LogicException('Nothing to pick from; the step should not have been eligible.');
        }

        return $ctx->pick($list);
    }

    private function today(): CarbonImmutable
    {
        return CarbonImmutable::now()->startOfDay();
    }

    /** @return Collection<int, Assignment> */
    private function current(): Collection
    {
        return Assignment::query()->validAt($this->today())->currentKnowledge()->excludeRetractions()->get()->toBase();
    }

    /** @return Collection<int, Person> */
    private function employed(): Collection
    {
        return $this->current()->map(fn (Assignment $assignment): ?Person => $assignment->person)->filter()->values();
    }

    /**
     * Everyone employed except the founder.
     *
     * @return Collection<int, Person>
     */
    private function staff(): Collection
    {
        return $this->employed()->reject(fn (Person $person): bool => $person->email === 'founder@example.test')->values();
    }
}
