<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(\App\Http\Controllers\LeagueController::class)->prefix('league')->group(function (){
    Route::post('/', 'create');
    Route::get('/{leagueUUID}', 'show');
    Route::post('/{leagueUUID}/play-week', 'playNextWeek');
    Route::post('/{leagueUUID}/play-all-weeks', 'playAllWeeks');
});
