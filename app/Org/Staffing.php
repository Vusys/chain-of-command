<?php

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

    public function transfer(Person $person, Department $department, ?Person $manager, CarbonInterface|string $from): void
    {
        $this->change($person, $from, ['department_id' => $department->getKey(), 'manager_id' => $manager?->getKey()]);
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

    public function leave(Person $person, CarbonInterface|string $on): void
    {
        $person->assignments()->endAt($on);
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
        return $person->assignments()->validAt($at)->currentKnowledge()->excludeRetractions()->first()
            ?? throw new DomainException("{$person->name} has no assignment on {$at}.");
    }
}
