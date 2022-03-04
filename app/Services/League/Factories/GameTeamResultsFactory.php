<?php

namespace App\Services\League\Factories;

use App\Services\League\Entities\GameTeamResults;

class GameTeamResultsFactory
{
    public function build(array $mappedGoalsWithTeams, string $uuid): GameTeamResults
    {
        return new GameTeamResults($mappedGoalsWithTeams, $uuid);
    }
}
