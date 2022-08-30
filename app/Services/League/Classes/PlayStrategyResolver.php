<?php

namespace App\Services\League\Classes;

use App\Services\League\Strategies\PlayStrategyInterface;
use App\Services\League\Strategies\PlayNextAllStrategy;
use App\Services\League\Strategies\PlayWeekStrategy;

class PlayStrategyResolver
{
    public function __construct(
        private readonly CalculateGoals $calculateGoalsService
    )
    {
    }

    public function resolve(string $type): PlayStrategyInterface
    {
        return match ($type){
            'week' => new PlayWeekStrategy($this->calculateGoalsService),
            'all' => new PlayNextAllStrategy($this->calculateGoalsService)
        };
    }
}
