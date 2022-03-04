<?php

namespace App\Services\League\Factories;

use App\Services\League\Classes\League;
use App\Services\League\Classes\PlayStrategyResolver;
use App\Services\League\ValueObjects\LeagueCreating;
use Illuminate\Contracts\Events\Dispatcher;

class LeagueFactoryRestore
{
    public function __construct(
        private Dispatcher            $dispatcher,
        private PlayStrategyResolver  $playStrategyResolver,
        private MatchesPlannerFactory $matchesPlannerFactory)
    {
    }

    public function restore(
        string $uuid,
        int    $matches_per_week,
        array  $teams,
        array  $matches,
        int    $current_week,
        array  $last_played_matches
    ): League
    {
        return new League(
            new LeagueCreating(
                $uuid,
                $teams,
                $this->playStrategyResolver,
                $this->matchesPlannerFactory,
                $this->dispatcher,
                $matches_per_week
            ),
            $matches,
            $current_week,
            $last_played_matches
        );
    }
}
