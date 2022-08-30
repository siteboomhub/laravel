<?php

namespace Tests\Unit\League\Classes;

use App\Exceptions\League\AmountOfTeamsOnlyOddException;
use App\Exceptions\League\MatchesNumberException;
use App\Services\League\ValueObjects\LeagueConfiguration;
use PHPUnit\Framework\TestCase;

class LeagueConfigurationTest extends TestCase
{
    /**
     * @throws AmountOfTeamsOnlyOddException
     */
    public function testThatMatchesNotEnoughForLeaguePlay()
    {
        $this->expectException(MatchesNumberException::class);

        new LeagueConfiguration(2, 2);
    }

    /**
     * @throws MatchesNumberException
     */
    public function testThatMatchesPerWeekIsLessThanMaxAllowed()
    {
        $this->expectException(AmountOfTeamsOnlyOddException::class);

        new LeagueConfiguration(2, 5);
    }
}
