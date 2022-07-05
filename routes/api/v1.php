<?php

use App\Http\Controllers\Api\V1\CakeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('cakes', CakeController::class);
