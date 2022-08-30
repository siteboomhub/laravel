<?php

namespace Tests\Unit\League\Entities;

use App\Services\League\Entities\Game;
use App\Services\League\Entities\Team;
use App\Exceptions\League\GameMembersException;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function wrongTeamsProvider()
    {
        return [
            [[1]],
            [[1, 2, 3, 4]],
            [[1, 2, 3]],
            [[]],
        ];
    }

    /**
     * @dataProvider wrongTeamsProvider
     */
    public function testThatTwoTeamsRequiredCondition($teams_with_wrong_number)
    {
        $this->expectException(GameMembersException::class);

        new Game($teams_with_wrong_number);
    }

    public function testThatGameGetTeamsResultIsArray()
    {
        $team = $this->createMock(Team::class);

        $game = new Game([$team, $team]);

        $this->assertIsArray($game->teams);
    }

    public function testThatGameGetMappedGoalsResultIsArray()
    {
        $team = $this->createMock(Team::class);

        $game = new Game([$team, $team]);

        $this->assertIsArray($game->getMappedGoals());
    }
}
