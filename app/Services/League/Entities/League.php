<?php

namespace App\Services\League\Entities;

use App\Services\League\Events\LeaguePlayedEvent;
use App\Services\League\Exceptions\LeagueAlreadyFinishedException;
use App\Services\League\Exceptions\MatchesNumberException;
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
        $this->checkCanCreateLeague();
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

    /**
     * @throws MatchesNumberException
     */
    private function checkCanCreateLeague()
    {
        $matches_number = $this->calculateMatchesNumber();

        if ($this->getMatchesPerWeek() > $matches_number) {
            throw new MatchesNumberException("Max value for matches per week is {$matches_number}");
        }
    }

    #[Pure] private function calculateMatchesNumber(): int
    {
        return (count($this->getTeams()) - 1) * count($this->getTeams());
    }
}
