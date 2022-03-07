<?php

namespace App\Services\League\Entities;

class GameTeamResults
{
    const COEFFICIENT_WON_PTS = 3;

    const COEFFICIENT_DRAWN_PTS = 1;

    private int $gd;

    private int $pts;

    public function __construct(private array $goals, private string $team_uuid)
    {
        $this->gd = $this->calculateTeamGoalDifference();
        $this->pts = $this->calculateTeamPTS();
    }

    public function getGd(): int
    {
        return $this->gd;
    }

    public function getPts(): int
    {
        return $this->pts;
    }

    private function calculateTeamGoalDifference(): int
    {
        $gd = max($this->goals) - min($this->goals);

        $abs_gd = abs($gd);

        if($this->isTeamLoser()){
            return -$abs_gd;
        }else{
            return $abs_gd;
        }
    }

    private function calculateTeamPTS(): int
    {
        if($this->isTeamWinner()){
            return self::COEFFICIENT_WON_PTS;
        }elseif(!$this->isTeamLoser()){
            return self::COEFFICIENT_DRAWN_PTS;
        }

        return 0;
    }

    private function isTeamWinner(): bool
    {
        $max_goals = max($this->goals);

        return min($this->goals) !== $max_goals && $max_goals === $this->goals[$this->team_uuid];
    }

    private function isTeamLoser(): bool
    {
        $min_goals = min($this->goals);

        return max($this->goals) !== $min_goals && $min_goals === $this->goals[$this->team_uuid];
    }
}
