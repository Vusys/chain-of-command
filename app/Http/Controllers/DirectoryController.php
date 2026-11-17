<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Department;
use App\Models\Person;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirectoryController extends Controller
{
    /**
     * Everyone in post on one date, as we know it now: searchable by name or
     * title, and narrowed to a department and every team under it.
     */
    public function index(Request $request): View
    {
        $on = CarbonImmutable::parse($request->query('on') ?: 'today');
        $q = trim((string) $request->query('q', ''));
        $found = $request->filled('department') ? Department::query()->find($request->integer('department')) : null;
        $department = $found instanceof Department ? $found : null;

        $results = Assignment::query()->validAt($on)->currentKnowledge()->excludeRetractions()
            ->when($department, fn (Builder $query, Department $department) => $query->whereIn(
                'department_id',
                Department::query()->whereDescendantOrSelf($department)->select('id'),
            ))
            ->when($q !== '', fn (Builder $query) => $query->where(fn (Builder $match) => $match
                ->where('title', 'like', "%{$q}%")
                ->orWhereIn('person_id', Person::query()->where('name', 'like', "%{$q}%")->select('id'))))
            ->orderBy(Person::query()->select('name')->whereColumn('people.id', 'assignments.person_id'))
            ->paginate(5)
            ->withQueryString();

        return view('people', [
            'on' => $on,
            'q' => $q,
            'department' => $department,
            'departments' => Department::query()->defaultOrder()->get(),
            'results' => $results,
        ]);
    }
}
