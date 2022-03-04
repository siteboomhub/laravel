<?php

namespace App\Services\League\Responses;

use JetBrains\PhpStorm\ArrayShape;
use App\Services\League\Entities\League;
use App\Services\League\Entities\Team;

class LeagueResults
{
    #[ArrayShape(['current_week' => "int", 'teams' => "array", 'last_played_matches' => "array"])]
    public function build(League $league): array
    {
        return [
            'current_week' => $league->getCurrentWeek(),
            'teams' => $this->formatTeams($league->getTeams()),
            'last_played_matches' => $league->getLastPlayedMatches()
        ];
    }

    private function formatTeams(array $teams): array
    {
        return array_map(function (Team $team) {
            return [
                'name' => $team->getName(),
                'pts' => $team->getPTS(),
                'played' => $team->getPlayed(),
                'won' => $team->getWon(),
                'drawn' => $team->getDrawn(),
                'lost' => $team->getLost(),
                'gd' => $team->getGD(),
                'prediction_score' => $team->getPrediction()
            ];
        }, $teams);
    }
}
