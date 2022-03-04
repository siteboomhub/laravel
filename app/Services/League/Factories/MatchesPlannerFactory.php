<?php

namespace App\Services\League\Factories;

use App\Services\League\Factories\GameFactory;
use Illuminate\Support\Arr;

class MatchesPlannerFactory
{
    public function __construct(private array $teams, private GameFactory $gameFactory)
    {
    }

    /**
     * @throws \App\Services\League\Exceptions\GameMembersException
     */
    public function plan(): array
    {
        $matches = [];

        foreach ($this->teams as $i => $team_1) {
            foreach ($this->teams as $k => $team_2) {
                if ($i === $k) {
                    continue;
                } else {
                    $matches[] = $this->gameFactory->build([$team_1, $team_2]);
                }
            }
        }

        return Arr::shuffle($matches);
    }
}
