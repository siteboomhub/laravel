<?php

namespace App\Services\League\Classes;

use App\Exceptions\League\AmountOfTeamsOnlyOddException;
use App\Exceptions\League\MatchesNumberException;

class LeagueCreatingValidation
{
    public function __construct(private int $matches_per_week, private int $teams_number)
    {
    }

    public function validate()
    {
        if($this->teams_number % 2 !== 0){
            throw new AmountOfTeamsOnlyOddException(
                'Teams number has to be oddd only'
            );
        }

        if ($this->teams_number / $this->matches_per_week < 2) {
            throw new MatchesNumberException(
                'Teams number has to be more than amount of matches per week on 2 times'
            );
        }
    }
}
