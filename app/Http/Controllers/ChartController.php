<?php

declare(strict_types=1);

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

        $query = Assignment::query()->validAt($on)->excludeRetractions();
        $assignments = ($known instanceof CarbonImmutable ? $query->knownAt($known) : $query->currentKnowledge())->get();

        // Plain lazy loads, not with(): every person and manager is resolved
        // through slipstream's identity map, so each is fetched once.
        $byDepartment = $assignments
            ->sortBy(fn (Assignment $assignment): string => $assignment->person->name ?? '')
            ->groupBy('department_id');

        $data = [
            'on' => $on,
            'known' => $known,
            'departments' => Department::query()->defaultOrder()->get(),
            'byDepartment' => $byDepartment,
            'headcount' => $assignments->count(),
        ];

        // The slider asks for just the part of the page that changes.
        if ($request->boolean('partial')) {
            return view('partials.chart-body', $data);
        }

        $months = $this->months();
        $index = array_search($on->startOfMonth()->toDateString(), $months, true);

        return view('chart', $data + [
            'previous' => $on->subMonthNoOverflow()->startOfMonth(),
            'next' => $on->addMonthNoOverflow()->startOfMonth(),
            'months' => $months,
            'monthList' => implode(',', $months),
            'monthIndex' => $index === false ? count($months) - 1 : $index,
        ]);
    }

    /**
     * The first of every month from the company's founding to this one —
     * the slider's stops.
     *
     * @return list<string>
     */
    private function months(): array
    {
        $months = [];
        for ($month = CarbonImmutable::parse(self::FOUNDED); $month->lessThanOrEqualTo(CarbonImmutable::today()); $month = $month->addMonthNoOverflow()) {
            $months[] = $month->toDateString();
        }

        return $months;
    }

    private const string FOUNDED = '2025-01-01';
}
