<?php

namespace App\Services\League\Factories;

use App\Services\League\Classes\CalculateGoals;
use App\Services\League\Classes\Team;
use JetBrains\PhpStorm\Pure;

class CalculateGoalsFactory
{
    /**
     * @param Team[] $teams
     */
    #[Pure] public function build(array $teams): CalculateGoals
    {
        return new CalculateGoals($teams);
    }
}
