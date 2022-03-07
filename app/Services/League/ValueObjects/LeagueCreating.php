<?php

namespace App\Services\League\ValueObjects;

use App\Services\League\Classes\PlayStrategyResolver;
use App\Services\League\Entities\Team;
use Illuminate\Contracts\Events\Dispatcher;

class LeagueCreating
{
    /**
     * @param Team[] $teams
     */
    public function __construct(
        private string                $uuid,
        private array                 $teams,
        private PlayStrategyResolver  $playStrategyResolver,
        private Dispatcher            $dispatcher,
        private int                   $matches_per_week,
        private array                 $matches = [],
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

    public function getDispatcher(): Dispatcher
    {
        return $this->dispatcher;
    }

    public function getMatchesPerWeek(): int
    {
        return $this->matches_per_week;
    }

    public function getMatches(): array
    {
        return $this->matches;
    }

}
