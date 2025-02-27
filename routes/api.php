<?php

use Illuminate\Support\Facades\Route;

Route::crudResource('demos', \App\Http\Controllers\Api\Core\DemoCrudController::class);
