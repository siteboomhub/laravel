<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Strategies\PlayNextAllStrategy;

class PlayNextAllStrategyTest extends StrategyPlay
{
    public function providerForFourTeams()
    {
        return [
            [
                [
                    'a123' => 2,
                    'a234' => 3,
                    'a12345' => 2,
                    'a12346' => 3
                ]
            ]
        ];
    }

    /**
     * @dataProvider providerForFourTeams
     */
    public function testThatResultsAreWithCorrectStructure($teams)
    {
        $this->checkThatResultsAreWithCorrectStructure($teams, new PlayNextAllStrategy());
    }

    /**
     * @dataProvider providerForFourTeams
     */
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

    /**
     * @dataProvider providerForFourTeams
     */
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
