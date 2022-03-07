<?php

namespace App\Services\League\Strategies;

interface PlayStrategyInterface
{
    public function play(
        int $matches_per_week,
        int $current_week,
        array $matches
    ): array;
}
