<?php

namespace App\Services\League\ValueObjects;

use App\Exceptions\League\AmountOfTeamsOnlyOddException;
use App\Exceptions\League\MatchesNumberException;

class LeagueConfiguration
{
    public readonly int $games_per_week;
    public readonly int $teams_number;

    /**
     * @throws AmountOfTeamsOnlyOddException|MatchesNumberException
     */
    public function __construct(int $games_per_week, int $teams_number)
    {
        $this->setTeamsNumber($teams_number);
        $this->setGamesPerWeek($games_per_week);

    }

    /**
     * @param int $games_per_week
     * @throws MatchesNumberException
     */
    private function setGamesPerWeek(int $games_per_week): void
    {
        if ($this->teams_number / $games_per_week < 2) {
            throw new MatchesNumberException(
                'Teams number has to be more than amount of matches per week in twice'
            );
        }

        $this->games_per_week = $games_per_week;
    }

    /**
     * @param int $teams_number
     * @throws AmountOfTeamsOnlyOddException
     */
    private function setTeamsNumber(int $teams_number): void
    {
        if($teams_number % 2 !== 0){
            throw new AmountOfTeamsOnlyOddException(
                'Teams number has to be odd only'
            );
        }

        $this->teams_number = $teams_number;
    }
}
