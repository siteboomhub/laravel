<?php

namespace Tests\Unit\League\Entities;

use App\Services\League\Classes\CalculateGoals;
use App\Services\League\Entities\Game;
use App\Services\League\Entities\Team;
use App\Exceptions\League\GameMembersException;
use App\Services\League\Factories\CalculateGoalsFactory;
use App\Services\League\Factories\GameTeamResultsFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private MockObject|CalculateGoalsFactory $calculateGoalsFactoryMock;

    private MockObject|GameTeamResultsFactory $gameTeamResultsFactory;

    public function wrongTeamsProvider()
    {
        return [
            [[1]],
            [[1, 2, 3, 4]],
            [[1, 2, 3]],
            [[]],
        ];
    }

    public function teamsResultsProvider(): array
    {
        return [
            [['uuid' => '123', 'goals' => 2], ['uuid' => '1234', 'goals' => 3]]
        ];
    }

    protected function setUp(): void
    {
        $this->calculateGoalsFactoryMock = $this
            ->getMockBuilder(CalculateGoalsFactory::class)
            ->getMock();

        $this->gameTeamResultsFactory = $this
            ->getMockBuilder(GameTeamResultsFactory::class)
            ->getMock();
    }

    /**
     * @dataProvider wrongTeamsProvider
     */
    public function testThatTwoTeamsRequiredCondition($teams_with_wrong_number)
    {
        $this->expectException(GameMembersException::class);

        new Game(
            $teams_with_wrong_number,
            $this->calculateGoalsFactoryMock,
            $this->gameTeamResultsFactory
        );
    }

    /**
     * @param $team1
     * @param $team2
     * @throws GameMembersException
     * @dataProvider teamsResultsProvider
     */
    public function testThatWeCreateTeamResultsForTwoTeams($team1, $team2)
    {
        $team = $this->createStub(Team::class);

        $team->method('getUuid')->willReturnOnConsecutiveCalls(
            $team1['uuid'], $team2['uuid'],
            $team1['uuid'], $team2['uuid']
        );

        $calculateGoalsMock = $this->getMockBuilder(CalculateGoals::class)
            ->disableOriginalConstructor()
            ->getMock();

        $calculateGoalsMock->method('calculate')->willReturn([
            $team1['goals'], $team2['goals']
        ]);

        $this->calculateGoalsFactoryMock->method('build')->willReturn(
            $calculateGoalsMock
        );

        $game = new Game(
            [$team, $team],
            $this->calculateGoalsFactoryMock,
            $this->gameTeamResultsFactory
        );

        $mappedGoals = [$team1['uuid'] => $team1['goals'], $team2['uuid'] => $team2['goals']];

        $this->gameTeamResultsFactory
            ->expects($this->exactly(2))
            ->method('build')
            ->with(
                $this->equalTo($mappedGoals),
                $this->callback(function ($uuid) use ($team1, $team2) {
                    return in_array($uuid, [$team1['uuid'], $team2['uuid']]);
                })
            );

        $team->expects($this->exactly(2))->method('addGameResults');

        $game->play();
    }


    public function testThatGameGetTeamsResultIsArray()
    {
        $team = $this->createMock(Team::class);

        $game = new Game(
            [$team, $team],
            $this->calculateGoalsFactoryMock,
            $this->gameTeamResultsFactory
        );

        $this->assertIsArray($game->getTeams());
    }

    public function testThatGameGetMappedGoalsResultIsArray()
    {
        $team = $this->createMock(Team::class);

        $game = new Game(
            [$team, $team],
            $this->calculateGoalsFactoryMock,
            $this->gameTeamResultsFactory
        );

        $this->assertIsArray($game->getMappedGoals());
    }
}
