<?php

namespace App\Services\League\Strategies\MatchPredictionsCalculatingStrategy;

interface PredictionsCalculatingInterface
{
    public function calculate(array $teams);
}
