<?php

use App\Http\Controllers\ChartController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/chart');
Route::get('/chart', [ChartController::class, 'show'])->name('chart');
