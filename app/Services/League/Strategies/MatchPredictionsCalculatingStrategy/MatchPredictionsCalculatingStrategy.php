<?php

namespace App\Services\League\Strategies\MatchPredictionsCalculatingStrategy;

use App\Services\League\Entities\Team;
use Illuminate\Support\Arr;

abstract class MatchPredictionsCalculatingStrategy implements PredictionsCalculatingInterface
{
    protected const WIN_POINTS_VALUE = 3;

    protected ?int $max_score = null;

    public function __construct(protected array $mapped_goals)
    {
    }

    abstract protected function getTeamsAmount(): int;

    protected function getMaxScore(): int
    {
        if($this->max_score === null){
            $this->max_score = self::WIN_POINTS_VALUE * ($this->getTeamsAmount() * ($this->getTeamsAmount() - 1)) / 2;
        }

        return $this->max_score;
    }

    protected function getTeamsUuids(): array
    {
        return array_keys($this->mapped_goals);
    }

    protected function addPredictionToTeam(float $prediction_value, Team $team)
    {
        $team->setPrediction($team->getPrediction() + $prediction_value);
    }

    /**
     * @param Team[] $teams
     * @param string $uuid
     * @return Team
     */
    protected function getTeamByUuid(array $teams, string $uuid): Team
    {
        return Arr::first($teams, function (Team $team) use ($uuid) {
            return $team->getUuid() === $uuid;
        });
    }
}
