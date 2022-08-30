<?php

namespace Tests\Unit\League\Factories;

use App\Services\League\Entities\Team;
use App\Services\League\Factories\GamesPlannerFactory;
use PHPUnit\Framework\TestCase;

class MatchesPlannerTest extends TestCase
{
    private GamesPlannerFactory $planner;

    public function teamsAmountProvider()
    {
        function fill(int $total)
        {
            $res = [];

            $i = 0;

            while ($i < $total) {
                $res[] = new Team((string)($i + 1));
                $i++;
            }

            return $res;
        }

        return [
            [fill(4), 2]
        ];
    }

    protected function setUp(): void
    {
        $this->planner = new GamesPlannerFactory;
    }

    /**
     * @param $teams
     * @param $expected_count
     * @throws \App\Exceptions\League\GameMembersException
     */
    public function testThatMatchesNumberCalculatedCorrectly()
    {
        $teams = [new Team('A'), new Team('B'), new Team('C'), new Team('D')];

        $teams_amount = count($teams);

        $matches = $this->planner->plan($teams, 2);

        $this->assertCount($teams_amount * ($teams_amount-1), $matches);
    }

    /**
     * @dataProvider teamsAmountProvider
     */
    public function testThatMatchesOrderIsCorrectly(array $teams, int $per_week)
    {
        $matches = $this->planner->plan($teams, $per_week);

        $grouped_matches_by_week = array_chunk($matches, $per_week);

        foreach ($grouped_matches_by_week as $matches_by_week) {

            $teams_uuids = [];

            foreach ($matches_by_week as $match) {
                foreach ($match->teams as $team) {
                    $teams_uuids[] = $team->uid->value;
                }
            }

            $this->assertEquals($teams_uuids, array_unique($teams_uuids));
        }

    }
}
