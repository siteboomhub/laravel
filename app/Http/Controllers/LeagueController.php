<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeagueStartRequest;
use App\Services\League\LeagueFacade;

class LeagueController extends Controller
{
    //
    public function create(LeagueStartRequest $request)
    {
        $uuid = LeagueFacade::createAndSave(
            $request->input('games_number_per_week', 2),
            $request->input('teams_number', 4)
        );

        return route('league.show', [
            'leagueUUID' => $uuid
        ]);
    }

    public function show(string $leagueUUID)
    {
        return LeagueFacade::getLeagueResults($leagueUUID);
    }

    public function playNextWeek(string $leagueUUID)
    {
        LeagueFacade::playWeek($leagueUUID);
    }

    public function playAllWeeks(string $leagueUUID)
    {
        LeagueFacade::playAllWeeks($leagueUUID);
    }
}
