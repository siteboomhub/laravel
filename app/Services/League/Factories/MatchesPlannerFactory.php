<?php

namespace App\Services\League\Factories;

use App\Exceptions\League\GameMembersException;
use App\Services\League\Entities\Game;
use App\Services\League\Entities\Team;
use Illuminate\Support\Arr;

class MatchesPlannerFactory
{
    public function __construct(private GameFactory $gameFactory)
    {
    }

    /**
     * @param Team[] $teams
     * @param int $games_per_week
     * @return array
     * @throws GameMembersException
     */
    public function plan(array $teams, int $games_per_week): array
    {
        $shuffled_teams = Arr::shuffle($teams);

        $matches = [];

        foreach ($shuffled_teams as $team_1) {
            foreach ($shuffled_teams as $team_2) {
                if ($team_1 === $team_2) {
                    continue;
                } else {
                    $matches[] = $this->gameFactory->build([$team_1, $team_2]);
                }
            }
        }

        return $this->orderMatches($matches, $games_per_week);
    }

    /**
     * @param Game[] $matches
     * @param int $games_per_week
     * @return array
     */
    private function orderMatches(array $matches, int $games_per_week): array
    {
        $matches_amount = count($matches);

        $i = 0;

        while ($i < $matches_amount - 1) {

            if ( ($i === 0 || $i % $games_per_week - 1 !== 0) && !$matches[$i + 1]->areTeamsDifferent($matches[$i])) {

                $j = $i + 2;

                while ($j < $matches_amount) {

                    if ($matches[$i]->areTeamsDifferent($matches[$j])) {

                        $c = $matches[$i + 1];

                        $matches[$i + 1] = $matches[$j];

                        $matches[$j] = $c;

                        break;

                    }

                    $j++;
                }

            }

            $i++;
        }

        return $matches;
    }
}
