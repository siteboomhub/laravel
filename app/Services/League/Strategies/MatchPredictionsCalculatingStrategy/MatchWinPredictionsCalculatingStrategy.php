<?php

namespace App\Services\League\Strategies\MatchPredictionsCalculatingStrategy;

class MatchWinPredictionsCalculatingStrategy extends MatchPredictionsCalculatingStrategy
{
    private int $teams_amount;

    public function calculate(array $teams)
    {
        $this->teams_amount = count($teams);

        $prediction_value = self::WIN_POINTS_VALUE / $this->getMaxScore() * 100;

        $team_uuids = $this->getTeamsUuids();

        if($this->mapped_goals[$team_uuids[0]] > $this->mapped_goals[$team_uuids[1]]){
            $team_winner = $this->getTeamByUuid($teams, $team_uuids[0]);
            $team_loser = $this->getTeamByUuid($teams, $team_uuids[1]);
        }else{
            $team_winner = $this->getTeamByUuid($teams, $team_uuids[1]);
            $team_loser = $this->getTeamByUuid($teams, $team_uuids[0]);
        }

        $this->addPredictionToTeam($prediction_value, $team_winner);

        $this->addPredictionToTeam(-$prediction_value, $team_loser);

    }

    protected function getTeamsAmount(): int
    {
        return $this->teams_amount;
    }
}
