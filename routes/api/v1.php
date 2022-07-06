<?php

use App\Http\Controllers\Api\V1\CakeController;
use App\Http\Controllers\Api\V1\ClientController;
use Illuminate\Support\Facades\Route;

Route::apiResource('cakes', CakeController::class);
Route::apiResource('clients', ClientController::class);


Route::get('cake-available-send-notification', function () {
    $cake = \App\Models\Cake::find(1);

    \App\Jobs\CakeAvailableAllJob::dispatch($cake);
});
