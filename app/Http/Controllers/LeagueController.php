<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeagueStartRequest;
use App\Services\League\LeagueFacade;

class LeagueController extends Controller
{
    //
    public function create(LeagueStartRequest $request): string
    {
        $uuid = LeagueFacade::createAndSave(
            $request->input('games_number_per_week', 2),
            $request->input('teams_number', 4)
        );

        return route('league.show', [
            'leagueUUID' => $uuid
        ]);
    }

    public function show(string $leagueUID): array
    {
        return LeagueFacade::getLeagueResults($leagueUID);
    }

    public function playNextWeek(string $leagueUID)
    {
        LeagueFacade::play($leagueUID);
    }

    public function playAllWeeks(string $leagueUID)
    {
        LeagueFacade::play($leagueUID, 'all');
    }
}
