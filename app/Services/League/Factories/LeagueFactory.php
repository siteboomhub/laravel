<?php

namespace App\Services\League\Factories;

use App\Services\League\Entities\League;
use App\Services\League\Classes\PlayStrategyResolver;
use App\Services\League\ValueObjects\LeagueCreating;
use Illuminate\Contracts\Events\Dispatcher;

class LeagueFactory
{
    public function __construct(
        private Dispatcher            $dispatcher,
        private PlayStrategyResolver  $playStrategyResolver,
        private MatchesPlannerFactory $matchesPlannerFactory,
        private TeamsBuilderFactory   $teamsBuilder
    )
    {
    }

    public function build(int $matches_per_week, int $teams_number): League
    {
        $teams = $this->teamsBuilder->build($teams_number);

        return new League(
            new LeagueCreating(
                uniqid(),
                $teams,
                $this->playStrategyResolver,
                $this->dispatcher,
                $matches_per_week,
                $this->matchesPlannerFactory->plan($teams, $matches_per_week)
            ),
        );
    }
}
