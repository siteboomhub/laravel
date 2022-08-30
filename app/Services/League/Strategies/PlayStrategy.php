<?php

namespace App\Services\League\Strategies;

use App\Services\League\Classes\CalculateGoals;
use App\Services\League\Entities\Game;
use App\Services\League\ValueObjects\GameTeamResults;
use JetBrains\PhpStorm\ArrayShape;

abstract class PlayStrategy implements PlayStrategyInterface
{
    protected int $games_per_week;
    protected int $current_week;
    protected array $games;

    public function __construct(
        private readonly CalculateGoals $calculateGoalsService
    )
    {
    }

    #[ArrayShape(['week' => "int", 'last_played_matches' => "array"])] public function play(
        int $games_per_week,
        int $current_week,
        array $games
    ): array
    {
        $this->setParams(
             $games_per_week, $current_week, $games
        );

        return $this->playOneWeek();

    }

    #[ArrayShape(['week' => "int", 'last_played_matches' => "array"])] protected function playOneWeek(): array
    {
        $this->current_week++;

        $played_matches = [];

        for ($i = 0; $i < $this->games_per_week; $i++) {

            $game = $this->getGame($i);

            $teams = $game->teams;

            $goals = $this->calculateGoalsService->calculate();

            $mappedGoalsWithTeams = $this->mapGoalsWithTeams($teams, $goals);

            $game->setMappedGoalsWithTeams($mappedGoalsWithTeams);

            foreach ($teams as $team) {
                $team->addGameResults(
                    new GameTeamResults($mappedGoalsWithTeams, $team->uid->value)
                );
            }

            $played_matches[] = $game;
        }

        return [
            'week' => $this->current_week,
            'last_played_matches' => $played_matches
        ];
    }

    protected function setParams(
        int $games_per_week,
        int $current_week,
        array $games
    )
    {
        $this->games = $games;
        $this->current_week = $current_week;
        $this->games_per_week = $games_per_week;
    }

    private function getGame(int $index): Game
    {
        $actual_matches = array_slice($this->games,
            ($this->current_week * $this->games_per_week) - $this->games_per_week,
            $this->games_per_week);

        return $actual_matches[$index];
    }

    private function mapGoalsWithTeams(array $teams, array $goals): array
    {
        $results = [];

        array_walk($goals, function($goal, $i) use ($teams, &$results){
            $results[$teams[$i]->uid->value] = $goal;
        });

        return $results;
    }
}
