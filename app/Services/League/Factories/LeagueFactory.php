<?php

namespace App\Services\League\Factories;

use App\Services\League\Classes\League;
use App\Services\League\Classes\PlayStrategyResolver;
use App\Services\League\Classes\TeamsBuilder;
use App\Services\League\ValueObjects\LeagueCreating;
use Illuminate\Contracts\Events\Dispatcher;

class LeagueFactory
{
    public function __construct(
        private Dispatcher            $dispatcher,
        private PlayStrategyResolver  $playStrategyResolver,
        private MatchesPlannerFactory $matchesPlannerFactory,
        private TeamsBuilder          $teamsBuilder
    )
    {
    }

    public function build(int $matches_per_week, int $teams_number): League
    {
        return new League(
            new LeagueCreating(
                uniqid(),
                $this->buildTeams($teams_number),
                $this->playStrategyResolver,
                $this->matchesPlannerFactory,
                $this->dispatcher,
                $matches_per_week
            ),
        );
    }

    private function buildTeams(int $teams_number): array
    {
        return $this->teamsBuilder->build($teams_number);
    }
}
