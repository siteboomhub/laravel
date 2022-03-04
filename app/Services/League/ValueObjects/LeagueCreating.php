<?php

namespace App\Services\League\ValueObjects;

use App\Services\League\Classes\PlayStrategyResolver;
use App\Services\League\Entities\Team;
use App\Services\League\Factories\MatchesPlannerFactory;
use Illuminate\Contracts\Events\Dispatcher;

final class LeagueCreating
{
    /**
     * @param Team[] $teams
     */
    public function __construct(
        private string                $uuid,
        private array                 $teams,
        private PlayStrategyResolver  $playStrategyResolver,
        private MatchesPlannerFactory $matchesPlannerFactory,
        private Dispatcher            $dispatcher,
        private int                   $matches_per_week
    )
    {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return Team[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    public function getPlayStrategyResolver(): PlayStrategyResolver
    {
        return $this->playStrategyResolver;
    }

    public function getMatchesPlannerFactory(): MatchesPlannerFactory
    {
        return $this->matchesPlannerFactory;
    }

    public function getDispatcher(): Dispatcher
    {
        return $this->dispatcher;
    }

    public function getMatchesPerWeek(): int
    {
        return $this->matches_per_week;
    }
}
