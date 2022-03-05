<?php

namespace App\Services\League\Strategies;

use App\Services\League\Entities\Game;
use JetBrains\PhpStorm\ArrayShape;

abstract class PlayStrategy implements PlayStrategyInterface
{
    protected int $matches_per_week;

    protected int $current_week;

    protected array $matches;

    #[ArrayShape(['week' => "int", 'matches' => "array"])] public function play(
        int $matches_per_week,
        int $current_week,
        array $matches
    ): array
    {
        $this->setParams(
             $matches_per_week, $current_week, $matches
        );

        return $this->playOneWeek();

    }

    #[ArrayShape(['week' => "int", 'matches' => "array"])] protected function playOneWeek(): array
    {
        $this->current_week++;

        $results = [];

        for ($i = 1; $i <= $this->matches_per_week; $i++) {

            $match = $this->getMatch($i);

            $match->play();

            $teams = $match->getTeams();
            $mappedGoals = $match->getMappedGoals();

            $results[] = [
                'first_team_name' => $teams[0]->getName(),
                'score' => [
                    $mappedGoals[$teams[0]->getUuid()],
                    $mappedGoals[$teams[1]->getUuid()]
                ],
                'last_team_name' => $teams[1]->getName()
            ];
        }

        return [
            'week' => $this->current_week,
            'matches' => $results
        ];
    }

    protected function setParams(
        int $matches_per_week,
        int $current_week,
        array $matches
    )
    {
        $this->matches = $matches;
        $this->current_week = $current_week;
        $this->matches_per_week = $matches_per_week;
    }

    private function getMatch(int $index): Game
    {
        return $this->matches[$this->current_week * $this->matches_per_week - $index];
    }
}
