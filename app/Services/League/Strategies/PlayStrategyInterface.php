<?php

namespace App\Services\League\Strategies;

interface PlayStrategyInterface
{
    public function play(
        int $games_per_week,
        int $current_week,
        array $games
    ): array;
}
