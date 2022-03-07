<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Classes\CalculateGoals;
use App\Services\League\Entities\Game;
use App\Services\League\Entities\Team;
use App\Services\League\Factories\GameTeamResultsFactory;
use App\Services\League\Factories\MatchesPlannerFactory;
use App\Services\League\Factories\GameFactory;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class MatchesPlannerTest extends TestCase
{
    use CreatesApplication;

    private MatchesPlannerFactory $planner;

    public function expectedAmountProvider()
    {
        return [
            [[1, 2, 3, 4], 12],
            [[1, 2, 3, 4, 5, 6], 30],
            [[1, 2, 3, 4, 5, 6, 7, 8], 56]
        ];
    }

    public function teamsAmountProvider()
    {
        function fill(int $total, float $prediction)
        {
            $res = [];

            $i = 0;

            while ($i < $total) {
                $res[] = new Team((string)($i + 1), $prediction);
                $i++;
            }

            return $res;
        }

        return [
            [fill(4, 25), 2]
        ];
    }

    protected function setUp(): void
    {
        $game_factory = $this->createStub(GameFactory::class);

        $game_factory->method('build')->willReturn(
            $this->createStub(Game::class)
        );

        $this->planner = new MatchesPlannerFactory(
            $game_factory
        );
    }

    /**
     * @param $teams
     * @param $expected_count
     * @dataProvider expectedAmountProvider
     * @throws \App\Exceptions\League\GameMembersException
     */
    public function testThatMatchesNumberCalculatedCorrectly($teams, $expected_count)
    {
        $matches = $this->planner->plan($teams, 2);

        $this->assertCount($expected_count, $matches);

    }

    /**
     * @dataProvider teamsAmountProvider
     */
    public function testThatMatchesOrderIsCorrectly(array $teams, int $per_week)
    {
        $game_factory = new GameFactory(
            $this->createStub(GameTeamResultsFactory::class),
            $this->createStub(CalculateGoals::class)
        );

        $matches_planner_factory = new MatchesPlannerFactory(
            $game_factory
        );

        $matches = $matches_planner_factory->plan($teams, $per_week);

        $grouped_matches_by_week = array_chunk($matches, $per_week);

        foreach ($grouped_matches_by_week as $matches_by_week) {

            $teams_uuids = [];

            foreach ($matches_by_week as $match) {
                foreach ($match->getTeams() as $team) {
                    $teams_uuids[] = $team->getUuid();
                }
            }

            $this->assertEquals($teams_uuids, array_unique($teams_uuids));
        }

    }
}
