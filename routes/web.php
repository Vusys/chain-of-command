<?php

declare(strict_types=1);

use App\Http\Controllers\ChartController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/chart');
Route::get('/chart', [ChartController::class, 'show'])->name('chart');
Route::get('/people/{person}', [PersonController::class, 'show'])->name('people.show');
Route::post('/people/{person}/changes', [PersonController::class, 'change'])->name('people.change');
Route::post('/people/{person}/corrections', [PersonController::class, 'correct'])->name('people.correct');
Route::post('/departments/{department}/move', [DepartmentController::class, 'move'])->name('departments.move');
