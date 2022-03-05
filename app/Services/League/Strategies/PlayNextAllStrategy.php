<?php

namespace App\Services\League\Strategies;

use JetBrains\PhpStorm\ArrayShape;

class PlayNextAllStrategy extends PlayStrategy
{
    #[ArrayShape(['week' => "int", 'matches' => "array"])] public function play(
        int   $matches_per_week,
        int   $current_week,
        array $matches
    ): array
    {
        $this->setParams($matches_per_week, $current_week, $matches);

        $results = [];

        $weeks_number = count($this->matches) / $this->matches_per_week;

        for ($i = $this->current_week; $i < $weeks_number; $i++) {
            $results = parent::playOneWeek();
        }

        return $results;
    }
}
