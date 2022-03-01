<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Classes\Game;
use App\Services\League\Classes\MatchesPlanner;
use App\Services\League\Factories\GameFactory;
use PHPUnit\Framework\TestCase;

class MatchesPlannerTest extends TestCase
{
    public function dataProvider()
    {
        return [
            [ [1, 2, 3, 4], 12],
            [ [1, 2, 3], 6],
            [ [1, 2, 3, 4, 5], 20]
        ];
    }

    /**
     * @param $teams
     * @param $expected_count
     * @throws \App\Exceptions\League\GameMembersException
     * @dataProvider dataProvider
     */
    public function testThatMatchesNumberCalculatedCorrectly($teams, $expected_count)
    {
        $game_factory = $this->createStub(GameFactory::class);

        $game_factory->method('build')->willReturn(
            $this->createStub(Game::class)
        );

        $planner = new MatchesPlanner(
            $teams,
            $game_factory
        );

        $matches = $planner->plan();

        $this->assertCount($expected_count, $matches);

    }
}
