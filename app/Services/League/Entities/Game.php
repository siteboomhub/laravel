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

        $current_teams = [];

        foreach ($this->teams as $team) {
            $current_teams[] = $team->uid;
        }

        foreach ($game->teams as $team) {
            if (in_array($team->uid, $current_teams)) {
                $result = false;
                break;
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
