<?php

use App\Http\Controllers\PlanningController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/planning/generate', [PlanningController::class, 'generateApi']);
    Route::get('/planning/{planning}', [PlanningController::class, 'showApi']);
    Route::patch('/planning/{suggestion}/validate', [PlanningController::class, 'validateSuggestion']);
});
