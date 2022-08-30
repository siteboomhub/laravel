<?php

namespace App\Services\League\Strategies;

use JetBrains\PhpStorm\ArrayShape;

class PlayNextAllStrategy extends PlayStrategy
{
    #[ArrayShape(['week' => "int", 'games' => "array"])] public function play(
        int   $games_per_week,
        int   $current_week,
        array $games
    ): array
    {
        $this->setParams($games_per_week, $current_week, $games);

        $results = [];

        $weeks_number = count($this->games) / $this->games_per_week;

        for ($i = $this->current_week; $i < $weeks_number; $i++) {
            $results = parent::playOneWeek();
        }

        return $results;
    }
}
