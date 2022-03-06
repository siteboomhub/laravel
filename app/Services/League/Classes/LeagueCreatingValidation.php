<?php

namespace App\Services\League\Classes;

use App\Exceptions\League\AmountOfTeamsOnlyOddException;
use App\Exceptions\League\MatchesNumberException;

class LeagueCreatingValidation
{
    public function validate(int $matches_per_week, int $teams_number)
    {
        if($teams_number % 2 !== 0){
            throw new AmountOfTeamsOnlyOddException(
                'Teams number has to be oddd only'
            );
        }

        if ($teams_number / $matches_per_week < 2) {
            throw new MatchesNumberException(
                'Teams number has to be more than amount of matches per week on 2 times'
            );
        }
    }
}
