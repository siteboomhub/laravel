<?php

namespace Unit\League\Classes;

use App\Services\League\Classes\Game;
use App\Services\League\Classes\Team;
use App\Services\League\Strategies\PlayWeekStrategy;
use PHPUnit\Framework\TestCase;

abstract class StrategyPlay extends TestCase
{
    protected Game $match;

    protected Team $team;

    protected function setUp(): void
    {
        $this->match = $this->createStub(Game::class);

        $this->team = $this->createStub(Team::class);
    }

    public function checkThatResultsAreWithCorrectStructure($teams, PlayStrategyInterface $strategy)
    {
        $this->addConsecutiveCalls($teams);

        $per_week = 2;

        $results = $strategy->play(
            $per_week,
            0,
            [$this->match, $this->match]
        );

        $this->assertArrayHasKey('week', $results);
        $this->assertArrayHasKey('matches', $results);
        $this->assertCount($per_week, $results['matches']);
    }

    protected function addConsecutiveCalls($teams)
    {
        $team_uuids = array_keys($teams);

        $this->match->method('getTeams')->willReturn([$this->team, $this->team]);
        $this->match->method('getMappedGoals')->willReturnOnConsecutiveCalls(
            [$team_uuids[0] => $teams[$team_uuids[0]], $team_uuids[1] => $teams[$team_uuids[1]]],
            [$team_uuids[2] => $teams[$team_uuids[2]], $team_uuids[3] => $teams[$team_uuids[3]]]
        );
        $this->team->method('getUuid')->willReturnOnConsecutiveCalls(
            $team_uuids
        );
    }
}
