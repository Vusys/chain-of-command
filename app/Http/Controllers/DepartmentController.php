<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Department;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /** Reorganise: move a department, and everything in it, under another. */
    public function move(Request $request, Department $department): JsonResponse
    {
        $data = $request->validate(['parent' => ['required', 'integer', 'exists:departments,id']]);

        try {
            $department->moveUnder(Department::query()->findOrFail((int) $data['parent']));
        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => "{$department->name} moved."]);
    }
}
