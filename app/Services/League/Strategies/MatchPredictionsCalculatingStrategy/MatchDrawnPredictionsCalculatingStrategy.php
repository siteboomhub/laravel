<?php

namespace App\Services\League\Strategies\MatchPredictionsCalculatingStrategy;

use App\Services\League\Entities\Team;
use Illuminate\Support\Arr;

class MatchDrawnPredictionsCalculatingStrategy extends MatchPredictionsCalculatingStrategy
{
    private const DRAWN_POINTS_VALUE = 1;

    private int $teams_amount;

    public function calculate(array $teams)
    {
        $this->teams_amount = count($teams);

        $prediction_value = self::DRAWN_POINTS_VALUE / $this->getMaxScore() * 100;

        $team_uuids = $this->getTeamsUuids();

        $team1 = $this->getTeamByUuid($teams, $team_uuids[0]);

        $team2 = $this->getTeamByUuid($teams, $team_uuids[1]);

        $this->addPredictionToTeam($prediction_value, $team1);

        $this->addPredictionToTeam($prediction_value, $team2);

        $other_teams = $this->getOtherTeams($teams);

        $amount_other_teams = count($other_teams);

        foreach ($other_teams as $other_team) {
            $this->addPredictionToTeam((-$prediction_value * 2) / $amount_other_teams, $other_team);
        }
    }

    /**
     * @param Team[] $teams
     */
    private function getOtherTeams(array $teams): array
    {
        $excluded_uuids = $this->getTeamsUuids();

        return Arr::where($teams, function (Team $team) use ($excluded_uuids) {
            return !in_array($team->getUuid(), $excluded_uuids);
        });
    }

    protected function getTeamsAmount(): int
    {
        return $this->teams_amount;
    }
}
