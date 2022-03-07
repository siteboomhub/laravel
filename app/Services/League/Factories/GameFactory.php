<?php

namespace App\Services\League\Factories;

use App\Services\League\Classes\CalculateGoals;
use App\Services\League\Entities\Game;

class GameFactory
{
    public function __construct(
        private GameTeamResultsFactory $gameTeamResultsFactory,
        private CalculateGoals $calculateGoals
    )
    {
    }

    /**
     * @throws \App\Exceptions\League\GameMembersException
     */
    public function build(array $teams): Game
    {
        return new Game(
            $teams,
            $this->calculateGoals,
            $this->gameTeamResultsFactory
        );
    }
}
