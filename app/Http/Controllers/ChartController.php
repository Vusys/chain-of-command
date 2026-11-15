<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Department;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChartController extends Controller
{
    /**
     * The company on one date — as we understand it now, or, given
     * ?known=, as we understood it on that day.
     */
    public function show(Request $request): View
    {
        $on = CarbonImmutable::parse($request->query('on') ?: 'today');
        $known = $request->query('known') ? CarbonImmutable::parse($request->query('known'))->endOfDay() : null;

        $assignments = Assignment::query()
            ->validAt($on)
            ->when($known, fn ($query) => $query->knownAt($known), fn ($query) => $query->currentKnowledge())
            ->excludeRetractions()
            ->get();

        // Plain lazy loads, not with(): every person and manager is resolved
        // through slipstream's identity map, so each is fetched once.
        $byDepartment = $assignments
            ->sortBy(fn (Assignment $assignment) => $assignment->person->name)
            ->groupBy('department_id');

        return view('chart', [
            'on' => $on,
            'known' => $known,
            'departments' => Department::query()->defaultOrder()->get(),
            'byDepartment' => $byDepartment,
            'headcount' => $assignments->count(),
            'previous' => $on->subMonthNoOverflow()->startOfMonth(),
            'next' => $on->addMonthNoOverflow()->startOfMonth(),
        ]);
    }
}
