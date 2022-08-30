<?php

namespace App\Services\League\Entities;

use App\Services\League\ValueObjects\Uid;

class League
{
    public readonly Uid $uid;
    private array $last_played_matches = [];

    public function __construct(
        string                $uid,
        public readonly array $teams,
        public readonly int   $games_per_week,
        public readonly array $games,

        private int           $current_week = 0
    )
    {
        $this->uid = new Uid($uid);
    }

    public function isLeagueFinished(): bool
    {
        return $this->current_week === count($this->games) / $this->games_per_week;
    }

    public function currentWeek(): int
    {
        return $this->current_week;
    }

    public function setCurrentWeek(int $current_week): void
    {
        $this->current_week = $current_week;
    }

    /**
     * @return Game[]
     */
    public function lastPlayedMatches(): array
    {
        return $this->last_played_matches;
    }

    public function setLastPlayedMatches(array $last_played_matches): void
    {
        $this->last_played_matches = $last_played_matches;
    }
}
