<?php

namespace App\Services\League\Classes;

use App\Exceptions\League\GameMembersException;
use App\Services\League\Factories\CalculateGoalsFactory;
use App\Services\League\Factories\GameTeamResultsFactory;
use JetBrains\PhpStorm\Pure;

class Game
{
    private CalculateGoals $goalsCalculatorService;

    private array $mappedGoals = [];

    /**
     * @param Team[] $teams
     */
    public function __construct(
        private array                  $teams,
        CalculateGoalsFactory          $calculateGoalsFactory,
        private GameTeamResultsFactory $gameTeamResultsFactory
    )
    {
        if (count($this->teams) !== 2) {
            throw new GameMembersException('Game members number needs be only 2');
        }

        $this->goalsCalculatorService = $calculateGoalsFactory->build($this->teams);
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
        $goals = $this->goalsCalculatorService->calculate();

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
}
