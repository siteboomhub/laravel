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
            'current_week' => $league->currentWeek(),
            'teams' => $this->formatTeams($league->teams),
            'last_played_matches' => $this->getLastPlayedMatches($league)
        ];
    }

    private function formatTeams(array $teams): array
    {
        return array_values(array_map(function (Team $team) {
            return [
                'name' => $team->name(),
                'pts' => $team->pts(),
                'played' => $team->played(),
                'won' => $team->won(),
                'drawn' => $team->drawn(),
                'lost' => $team->lost(),
                'gd' => $team->gd()
            ];
        }, $this->sortTeams($teams)));
    }

    private function getLastPlayedMatches(League $league): array
    {
        $results = [];

        foreach ($league->lastPlayedMatches() as $match){
            $teams = $match->teams;
            $mappedGoals = $match->getMappedGoals();

            $results[] = [
                'first_team_name' => $teams[0]->name(),
                'score' => [
                    $mappedGoals[$teams[0]->uid->value],
                    $mappedGoals[$teams[1]->uid->value]
                ],
                'last_team_name' => $teams[1]->name()
            ];
        }

        return $results;
    }

    private function sortTeams(array $teams): array
    {
        usort($teams, function (Team $team1, Team $team2){
            return $team1->pts() < $team2->pts();
        });

        return $teams;
    }
}
