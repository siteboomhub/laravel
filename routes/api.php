<?php

use App\Http\Controllers\LeagueController;
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

Route::prefix('league')->group(function (){
    Route::post('/', [LeagueController::class, 'create']);
    Route::get('/{leagueUID}', [LeagueController::class, 'show']);
    Route::post('/{leagueUID}/play-week', [LeagueController::class, 'playNextWeek']);
    Route::post('/{leagueUID}/play-all-weeks', [LeagueController::class, 'playAllWeeks']);
});
