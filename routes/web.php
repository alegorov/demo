<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('fetch/{count?}', [\App\Http\Controllers\Api\Core\DemoCrudController::class, 'getGoogleSheetComments']);
