<?php

namespace App\Services\League\Strategies;

use App\Services\League\Entities\Game;
use JetBrains\PhpStorm\ArrayShape;

abstract class PlayStrategy implements PlayStrategyInterface
{
    protected int $matches_per_week;

    protected int $current_week;

    protected array $matches;

    #[ArrayShape(['week' => "int", 'matches' => "array"])] public function play(
        int $matches_per_week,
        int $current_week,
        array $matches
    ): array
    {
        $this->setParams(
             $matches_per_week, $current_week, $matches
        );

        return $this->playOneWeek();

    }

    #[ArrayShape(['week' => "int", 'matches' => "array"])] protected function playOneWeek(): array
    {
        $this->current_week++;

        $played_matches = [];

        for ($i = 0; $i < $this->matches_per_week; $i++) {

            $match = $this->getMatch($i);

            $match->play();

            $played_matches[] = $match;
        }

        return [
            'week' => $this->current_week,
            'matches' => $played_matches
        ];
    }

    protected function setParams(
        int $matches_per_week,
        int $current_week,
        array $matches
    )
    {
        $this->matches = $matches;
        $this->current_week = $current_week;
        $this->matches_per_week = $matches_per_week;
    }

    private function getMatch(int $index): Game
    {
        $actual_matches = array_slice($this->matches,
            ($this->current_week * $this->matches_per_week) - $this->matches_per_week,
            $this->matches_per_week);

        return $actual_matches[$index];
    }
}
