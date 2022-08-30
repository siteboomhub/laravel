<?php

namespace App\Services\League\Factories;

use App\Exceptions\League\GameMembersException;
use App\Exceptions\League\NotEnoughTeamsException;
use App\Services\League\Entities\League;
use App\Services\League\Entities\Team;
use App\Services\League\ValueObjects\LeagueConfiguration;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class LeagueFactory
{
    public function __construct(
        private readonly TeamsBuilderFactory $teamsBuilder,
        private readonly GamesPlannerFactory $gamesPlannerFactory
    )
    {
    }

    /**
     * @throws FileNotFoundException
     * @throws NotEnoughTeamsException|GameMembersException
     */
    public function build(LeagueConfiguration $leagueConfiguration): League
    {
        /**
         * @var Team[] $teams
         */
        $teams = $this->teamsBuilder->build($leagueConfiguration->teams_number);

        $games = $this->gamesPlannerFactory->plan($teams, $leagueConfiguration->games_per_week);

        return new League(
            uniqid(),
            $teams,
            $leagueConfiguration->games_per_week,
            $games
        );
    }
}
