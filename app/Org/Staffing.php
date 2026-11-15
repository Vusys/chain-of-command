<?php

declare(strict_types=1);

namespace App\Org;

use App\Models\Assignment;
use App\Models\Department;
use App\Models\Person;
use Carbon\CarbonInterface;
use DomainException;

/**
 * Every change to who sits where. The bitemporal writer replaces the whole
 * value tuple on each write, so each verb reads the assignment in force and
 * passes all of it back with only its own fields changed.
 */
final class Staffing
{
    public function hire(
        string $name,
        string $email,
        Department $department,
        ?Person $manager,
        string $title,
        int $salary,
        CarbonInterface|string $from,
    ): Person {
        $person = Person::query()->create(['name' => $name, 'email' => $email]);

        $person->assignments()->changeEffectiveFrom(
            attributes: [
                'department_id' => $department->getKey(),
                'manager_id' => $manager?->getKey(),
                'title' => $title,
                'salary' => $salary,
            ],
            validFrom: $from,
        );

        return $person;
    }

    /**
     * Refused when the new manager reports, however indirectly, to the
     * person moving: the chain of command would loop.
     */
    public function transfer(Person $person, Department $department, ?Person $manager, CarbonInterface|string $from): void
    {
        if ($manager instanceof Person && $this->wouldLoop($person, $manager, $from)) {
            throw new DomainException("{$manager->name} reports to {$person->name} on {$from}.");
        }

        $this->change($person, $from, ['department_id' => $department->getKey(), 'manager_id' => $manager?->getKey()]);
    }

    public function wouldLoop(Person $person, Person $manager, CarbonInterface|string $on): bool
    {
        $managerOf = [];
        foreach (Assignment::query()->validAt($on)->currentKnowledge()->excludeRetractions()->get() as $assignment) {
            $managerOf[$assignment->person_id] = $assignment->manager_id;
        }

        $seen = [];
        for ($at = $manager->id; $at !== null && ! isset($seen[$at]); $at = $managerOf[$at] ?? null) {
            if ($at === $person->id) {
                return true;
            }
            $seen[$at] = true;
        }

        return false;
    }

    public function promote(Person $person, string $title, int $salary, CarbonInterface|string $from): void
    {
        $this->change($person, $from, ['title' => $title, 'salary' => $salary]);
    }

    /**
     * Rewrite what was true over [$from, $to) — a backdated fix. The
     * superseded belief stays on record.
     *
     * shortcut: the untouched fields are copied from the segment at $from
     * across the whole window, so a window spanning a transfer flattens it;
     * correct per segment if the UI ever offers multi-segment windows.
     *
     * @param  array<string, mixed>  $values  any of Assignment::VALUES
     */
    public function correct(Person $person, array $values, CarbonInterface|string $from, CarbonInterface|string|null $to = null): void
    {
        $person->assignments()->correct(
            attributes: array_merge($this->current($person, $from)->only(Assignment::VALUES), $values),
            validFrom: $from,
            validTo: $to,
        );
    }

    /**
     * Refused while anyone still reports to them: move the reports first,
     * or they would be left reporting to someone who has gone.
     */
    public function leave(Person $person, CarbonInterface|string $on): void
    {
        if ($this->reports($person, $on) > 0) {
            throw new DomainException("{$person->name} still has reports on {$on}; move them first.");
        }

        $person->assignments()->endAt($on);
    }

    public function reports(Person $person, CarbonInterface|string $on): int
    {
        return Assignment::query()->validAt($on)->currentKnowledge()->excludeRetractions()
            ->where('manager_id', $person->getKey())->count();
    }

    /** @param  array<string, mixed>  $values */
    private function change(Person $person, CarbonInterface|string $from, array $values): void
    {
        $person->assignments()->changeEffectiveFrom(
            attributes: array_merge($this->current($person, $from)->only(Assignment::VALUES), $values),
            validFrom: $from,
        );
    }

    private function current(Person $person, CarbonInterface|string $at): Assignment
    {
        return Assignment::query()->whereTemporalEntity($person)->validAt($at)->currentKnowledge()->excludeRetractions()->first()
            ?? throw new DomainException("{$person->name} has no assignment on {$at}.");
    }
}
