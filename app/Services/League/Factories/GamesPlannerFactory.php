<?php

namespace App\Services\League\Factories;

use App\Exceptions\League\GameMembersException;
use App\Services\League\Entities\Game;
use App\Services\League\Entities\Team;
use Illuminate\Support\Arr;

class GamesPlannerFactory
{
    /**
     * @param Team[] $teams
     * @param int $games_per_week
     * @return array
     * @throws GameMembersException
     */
    public function plan(array $teams, int $games_per_week): array
    {
        $shuffled_teams = Arr::shuffle($teams);

        $games = [];

        foreach ($shuffled_teams as $team_1) {
            foreach ($shuffled_teams as $team_2) {
                if ($team_1 === $team_2) {
                    continue;
                } else {
                    $games[] = new Game([$team_1, $team_2]);
                }
            }
        }

        return $this->orderMatches($games, $games_per_week);
    }

    /**
     * @param Game[] $games
     * @param int $games_per_week
     * @return array
     */
    private function orderMatches(array $games, int $games_per_week): array
    {
        $games_amount = count($games);

        $i = 0;

        while ($i < $games_amount - 1) {

            if (($i === 0 || $i % $games_per_week - 1 !== 0) && !$games[$i + 1]->areTeamsDifferent($games[$i])) {

                $j = $i + 2;

                while ($j < $games_amount) {

                    if ($games[$i]->areTeamsDifferent($games[$j])) {

                        $c = $games[$i + 1];

                        $games[$i + 1] = $games[$j];

                        $games[$j] = $c;

                        break;

                    }

                    $j++;
                }

            }

            $i++;
        }

        return $games;
    }
}
