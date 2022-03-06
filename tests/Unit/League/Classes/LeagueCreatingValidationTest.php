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

        $leagueValidation = new LeagueCreatingValidation();

        $leagueValidation->validate(2, 2);
    }

    public function testThatMatchesPerWeekIsLessThanMaxAllowed()
    {
        $this->expectException(AmountOfTeamsOnlyOddException::class);

        $leagueValidation = new LeagueCreatingValidation();

        $leagueValidation->validate(2, 5);
    }
}
