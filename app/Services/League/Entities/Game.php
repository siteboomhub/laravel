<?php

namespace App\Services\League\Entities;

use App\Exceptions\League\GameMembersException;

class Game
{
    private array $mappedGoalsWithTeams = [];

    /**
     * @param Team[] $teams
     * @throws GameMembersException
     */
    public function __construct(
        public readonly array $teams
    )
    {
        if (count($this->teams) !== 2) {
            throw new GameMembersException('Game members number needs be only 2');
        }
    }

    public function areTeamsDifferent(Game $game): bool
    {
        $result = true;

        foreach ($game->teams as $game_team) {
            foreach ($this->teams as $current_team) {
                if ($current_team->equals($game_team)) {
                    $result = false;
                    break 2;
                }
            }
        }

        return $result;
    }

    public function getMappedGoals(): array
    {
        return $this->mappedGoalsWithTeams;
    }

    public function setMappedGoalsWithTeams(array $mappedGoalsWithTeams): void
    {
        $this->mappedGoalsWithTeams = $mappedGoalsWithTeams;
    }
}
