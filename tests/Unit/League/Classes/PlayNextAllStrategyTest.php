<?php

namespace Tests\Unit\NewLeague;

use App\Services\League\Strategies\PlayNextAllStrategy;
use Unit\League\Classes\StrategyPlay;

class PlayNextAllStrategyTest extends StrategyPlay
{
    public function providerForFourTeams()
    {
        return [
            [
                '123' => 2,
                '234' => 3,
                '12345' => 2,
                '12346' => 3
            ]
        ];
    }

    /*
     * @dataProvider providerForFourTeams
     */
    public function testThatResultsAreWithCorrectStructure($teams)
    {
        $this->checkThatResultsAreWithCorrectStructure($teams, new PlayNextAllStrategy);
    }

    public function testThatCurrentWeekIsLastAfterPlayWhenStarted($teams)
    {
        $per_week = 1;

        $this->addConsecutiveCalls($teams);

        $playNextAllStrategy = new PlayNextAllStrategy();

        $matches = [$this->match, $this->match];

        $results = $playNextAllStrategy->play(
            $per_week,
            0,
            $matches
        );

        $this->assertEquals((count($matches) / $per_week), $results['week']);
    }

    public function testThatCurrentWeekIsLastAfterPlayWhenAnyAllowed($teams)
    {
        $this->match->method('getTeams')->willReturn([$this->team, $this->team]);

        $per_week = 1;

        $this->addConsecutiveCalls($teams);

        $playNextAllStrategy = new PlayNextAllStrategy();

        $matches = [$this->match, $this->match];

        $results = $playNextAllStrategy->play(
            $per_week,
            1,
            $matches
        );

        $this->assertEquals((count($matches) / $per_week), $results['week']);
    }
}
