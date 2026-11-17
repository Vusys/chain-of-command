<?php

declare(strict_types=1);

namespace App\Smarty\Plugins;

use App\Models\Department;

/**
 * {department_path department=$department}: where a department sits, from
 * the top of the company down, e.g. "Kestrel Labs › Engineering › Platform".
 * Ancestors come from the nested set in one query; slipstream's identity map
 * serves the repeats when a page names the same departments again.
 */
class DepartmentPathFunction
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function __invoke(array $params): string
    {
        $department = $params['department'] ?? null;
        if (! $department instanceof Department) {
            return '';
        }

        $names = Department::query()->ancestorsOf($department->getBounds())->defaultOrder()->pluck('name')->push($department->name);

        return e($names->implode(' › '));
    }
}
