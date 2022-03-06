<?php

namespace Tests\Unit\League\Classes;

use App\Exceptions\League\AmountOfTeamsOnlyOddException;
use App\Exceptions\League\MatchesNumberException;
use App\Services\League\Classes\LeagueCreatingValidation;
use PHPUnit\Framework\TestCase;

class LeagueCreatingValidationTest extends TestCase
{
    public function testThatMatchesNotEnoughForLeaguePlay()
    {
        $this->expectException(MatchesNumberException::class);

        $leagueValidation = new LeagueCreatingValidation(2, 2);

        $leagueValidation->validate();
    }

    public function testThatMatchesPerWeekIsLessThanMaxAllowed()
    {
        $this->expectException(AmountOfTeamsOnlyOddException::class);

        $leagueValidation = new LeagueCreatingValidation(2, 5);

        $leagueValidation->validate();
    }
}
