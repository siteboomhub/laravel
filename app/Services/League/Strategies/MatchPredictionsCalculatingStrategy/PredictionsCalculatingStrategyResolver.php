<?php

namespace App\Services\League\Strategies\MatchPredictionsCalculatingStrategy;

use App\Services\League\Entities\League;

class PredictionsCalculatingStrategyResolver
{
    public function resolve(array $mapped_goals, League $league): PredictionsCalculatingInterface
    {

        if($league->isLeagueFinished()){
            return new LeagueFinishedPredictionsCalculatingStrategy();
        }

        if($this->hasDifferentGoals(array_values($mapped_goals)) ){
            return new MatchWinPredictionsCalculatingStrategy($mapped_goals);
        }else{
            return new MatchDrawnPredictionsCalculatingStrategy($mapped_goals);
        }
    }

    private function hasDifferentGoals(array $goals): bool
    {
        return $goals === array_unique($goals);
    }
}
