<?php

namespace App\Services\League\Factories;

use App\Services\League\Entities\League;

class LeagueFactoryRestore
{
    public function restore(
        string $uid,
        int    $games_per_week,
        array  $teams,
        array  $games,
        int    $current_week,
        array  $last_played_matches
    ): League
    {
        $league = new League(
            $uid,
            $teams,
            $games_per_week,
            $games,
            $current_week
        );

        $league->setLastPlayedMatches($last_played_matches);

        return $league;
    }
}
