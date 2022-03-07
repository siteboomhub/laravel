<?php

namespace App\Listeners\League;

use App\Services\League\Entities\League;
use App\Services\League\Strategies\MatchPredictionsCalculatingStrategy\PredictionsCalculatingStrategyResolver;

class CalculatePredictions
{
    public function __construct(
        private PredictionsCalculatingStrategyResolver $predictionsCalculatingStrategyResolver
    )
    {
    }

    public function handle(League $league)
    {
        $last_played_matches = $league->getLastPlayedMatches();

        foreach ($last_played_matches as $last_played_match) {
            $goals_with_team_uuid = $last_played_match->getMappedGoals();

            $predictionsCalculatingStrategy = $this->predictionsCalculatingStrategyResolver->resolve(
                $goals_with_team_uuid, $league
            );

            $predictionsCalculatingStrategy->calculate($league->getTeams());
        }
    }
}
