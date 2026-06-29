<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SupplyChainApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/countries', [SupplyChainApiController::class, 'getCountries']);
Route::get('/ports', [SupplyChainApiController::class, 'getPorts']);
Route::get('/news', [SupplyChainApiController::class, 'getNews']);
Route::get('/currency', [SupplyChainApiController::class, 'getCurrency']);
Route::get('/risk', [SupplyChainApiController::class, 'getRisk']);