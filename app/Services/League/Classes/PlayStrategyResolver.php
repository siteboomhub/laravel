<?php

namespace App\Services\League\Classes;

use App\Services\League\Strategies\PlayStrategyInterface;
use App\Services\League\Strategies\PlayNextAllStrategy;
use App\Services\League\Strategies\PlayWeekStrategy;

class PlayStrategyResolver
{
    public function resolve(string $type): PlayStrategyInterface
    {
        return match ($type){
            'week' => new PlayWeekStrategy(),
            'all' => new PlayNextAllStrategy()
        };
    }
}
