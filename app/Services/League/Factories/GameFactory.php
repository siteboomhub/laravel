<?php

namespace App\Services\League\Factories;

use App\Services\League\Entities\Game;

class GameFactory
{
    public function __construct(
        private CalculateGoalsFactory $calculateGoalsFactory,
        private GameTeamResultsFactory $gameTeamResultsFactory
    )
    {
    }

    /**
     * @throws \App\Services\League\Exceptions\GameMembersException
     */
    public function build(array $teams): Game
    {
        return new Game(
            $teams,
            $this->calculateGoalsFactory,
            $this->gameTeamResultsFactory
        );
    }
}
