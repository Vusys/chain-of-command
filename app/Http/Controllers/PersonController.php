<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Person;
use App\Org\Staffing;
use Carbon\CarbonImmutable;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Vusys\Bitemporal\Exceptions\TemporalException;

class PersonController extends Controller
{
    /**
     * One person's career: what we believe now, period by period, and every
     * belief we have held, including the ones a correction replaced.
     */
    public function show(Person $person): View
    {
        $history = Assignment::query()->whereTemporalEntity($person)->excludeRetractions()
            ->orderBy('recorded_from')->orderBy('valid_from')->get();

        return view('person', [
            'person' => $person,
            'timeline' => $history->whereNull('recorded_to')->sortBy('valid_from')->values(),
            'superseded' => $history->whereNotNull('recorded_to')->values(),
            'today' => CarbonImmutable::today(),
        ]);
    }

    /** A new title and salary from a date on — today or later. */
    public function change(Request $request, Person $person, Staffing $staffing): RedirectResponse
    {
        $data = $request->validate([
            'from' => ['required', 'date', 'after_or_equal:today'],
            'title' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'integer', 'min:1'],
        ]);

        $this->attempt(fn () => $staffing->promote($person, $data['title'], (int) $data['salary'], $data['from']));

        return to_route('people.show', $person)->with('status', "{$person->name}'s change from {$data['from']} is recorded.");
    }

    /** Rewrite one period's title and salary — a fix, with the old belief kept. */
    public function correct(Request $request, Person $person, Staffing $staffing): RedirectResponse
    {
        $data = $request->validate([
            'from' => ['required', 'date'],
            'to' => ['nullable', 'date', 'after:from'],
            'title' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'integer', 'min:1'],
        ]);

        $this->attempt(fn () => $staffing->correct(
            $person,
            ['title' => $data['title'], 'salary' => (int) $data['salary']],
            $data['from'],
            $data['to'] ?? null,
        ));

        return to_route('people.show', $person)->with('status', "{$person->name}'s period from {$data['from']} is corrected.");
    }

    /** A rule the staffing or temporal layer refused comes back as a form error. */
    private function attempt(callable $write): void
    {
        try {
            $write();
        } catch (DomainException|TemporalException $e) {
            throw ValidationException::withMessages(['from' => $e->getMessage()]);
        }
    }
}
