<?php

namespace App\Services\League\Entities;

use App\Events\League\LeaguePlayedEvent;
use App\Exceptions\League\LeagueAlreadyFinishedException;
use App\Services\League\ValueObjects\LeagueCreating;
use JetBrains\PhpStorm\Pure;

class League
{
    public function __construct(
        private LeagueCreating        $leagueCreating,
        private int                   $current_week = 0,
        private array                 $last_played_matches = []
    )
    {
    }

    #[Pure] public function getUuid(): string
    {
        return $this->leagueCreating->getUuid();
    }

    #[Pure] public function getTeams(): array
    {
        return $this->leagueCreating->getTeams();
    }

    public function getCurrentWeek(): int
    {
        return $this->current_week;
    }

    #[Pure] public function getMatches(): array
    {
        return $this->leagueCreating->getMatches();
    }

    #[Pure] public function getMatchesPerWeek(): int
    {
        return $this->leagueCreating->getMatchesPerWeek();
    }

    public function getLastPlayedMatches(): array
    {
        return $this->last_played_matches;
    }

    public function play(string $type = 'week')
    {
        if ($this->current_week === count($this->getMatches()) / $this->getMatchesPerWeek()) {
            throw new LeagueAlreadyFinishedException('This League already finished');
        }

        $playStrategy = $this->leagueCreating->getPlayStrategyResolver()->resolve($type);

        [
            'matches' => $this->last_played_matches,
            'week' => $this->current_week
        ] = $playStrategy->play($this->getMatchesPerWeek(), $this->current_week, $this->getMatches());

        $this->leagueCreating->getDispatcher()->dispatch(LeaguePlayedEvent::class, $this);
    }
}
