<?php

namespace App\Services\League\Entities;

use App\Exceptions\League\GameMembersException;
use App\Services\League\Classes\CalculateGoals;
use App\Services\League\Factories\GameTeamResultsFactory;
use JetBrains\PhpStorm\Pure;

class Game
{


    private array $mappedGoals = [];

    /**
     * @param Team[] $teams
     * @throws GameMembersException
     */
    public function __construct(
        private array                  $teams,
        private CalculateGoals         $goalsCalculatorService,
        private GameTeamResultsFactory $gameTeamResultsFactory
    )
    {
        if (count($this->teams) !== 2) {
            throw new GameMembersException('Game members number needs be only 2');
        }
    }

    public function getTeams(): array
    {
        return $this->teams;
    }

    public function getMappedGoals(): array
    {
        return $this->mappedGoals;
    }

    public function play()
    {
        $goals = $this->goalsCalculatorService->calculate(
            $this->getTeams()
        );

        $this->mappedGoals = $this->getMappedGoalsWithTeams($goals);

        foreach ($this->teams as $team) {

            $team->addGameResults(
                $this->gameTeamResultsFactory->build(
                    $this->mappedGoals, $team->getUuid()
                )
            );

        }
    }

    #[Pure] private function getMappedGoalsWithTeams(array $goals): array
    {
        $results = [];

        foreach ($goals as $i => $goal) {

            $team_uuid = $this->teams[$i]->getUuid();

            $results[$team_uuid] = $goal;
        }

        return $results;
    }

    #[Pure] public function areTeamsDifferent(Game $game): bool
    {
        $result = true;

        $current_teams = [];

        foreach ($this->getTeams() as $team) {
            $current_teams[] = $team->getUuid();
        }

        foreach ($game->getTeams() as $team) {
            if (in_array($team->getUuid(), $current_teams)) {
                $result = false;
                break;
            }
        }

        return $result;
    }
}
