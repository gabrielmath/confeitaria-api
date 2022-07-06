<?php

use App\Http\Controllers\Api\V1\CakeController;
use App\Http\Controllers\Api\V1\ClientController;
use Illuminate\Support\Facades\Route;

Route::apiResource('cakes', CakeController::class);
Route::apiResource('clients', ClientController::class);
