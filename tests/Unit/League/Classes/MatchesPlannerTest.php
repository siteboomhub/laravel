<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Entities\Game;
use App\Services\League\Factories\MatchesPlannerFactory;
use App\Services\League\Factories\GameFactory;
use PHPUnit\Framework\TestCase;

class MatchesPlannerTest extends TestCase
{
    private MatchesPlannerFactory $planner;

    public function expectedAmountProvider()
    {
        return [
            [ [1, 2, 3, 4], 12],
            [ [1, 2, 3, 4, 5, 6], 30],
            [ [1, 2, 3, 4, 5, 6, 7, 8], 56]
        ];
    }

    public function teamsAmountProvider()
    {
        return [
            [ [1, 2, 3, 4], 2],
            [ [1, 2, 3, 4, 5, 6], 4],
            [ [1, 2, 3, 4, 5, 6, 7, 8], 2]
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
        $matches = $this->planner->plan($teams);

        $this->assertCount($expected_count, $matches);

    }

    /**
     * @dataProvider teamsAmountProvider
     */
    public function testThatMatchesOrderIsCorrectly(array $teams, int $per_week)
    {
        $matches = $this->planner->plan($teams);

        $grouped_matches = array_chunk($matches, $per_week);

        foreach ($grouped_matches as $grouped_match){
            $this->assertEquals($grouped_match, array_unique($grouped_match));
        }

    }
}
