<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Classes\CalculateGoals;
use App\Services\League\Entities\Team;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CalculateGoalsTest extends TestCase
{
    private MockObject $goalsService;

    protected function setUp(): void
    {
        $this->goalsService = $this->getMockBuilder(CalculateGoals::class)
            ->onlyMethods(['calculatePoints'])
            ->getMock();
    }

    public function testThatInMatchAreTwoTeams()
    {
        $this->goalsService->method('calculatePoints')->willReturn(true);

        $this->assertCount(2, $this->goalsService->calculate([
            new Team('team 1', 1),
            new Team('team 2', 1)])
        );
    }

    public function testThatMatchHasHigherDrawnResult()
    {
        $this->goalsService->method('calculatePoints')->willReturn(true);

        $goals = $this->goalsService->calculate([
            new Team('team 1', 1),
            new Team('team 2', 1)
        ]);

        $this->assertEquals([3, 3], $goals);

    }

    public function testThatMatchHasLowerDrawnResult()
    {
        $this->goalsService->method('calculatePoints')->willReturn(false);

        $goals = $this->goalsService->calculate([
            new Team('team 1', 1),
            new Team('team 2', 1)
        ]);

        $this->assertEquals([2, 2], $goals);

    }

    public function testThatFirstTeamWon()
    {
        $this->goalsService->method('calculatePoints')->will($this->onConsecutiveCalls(true, false));

        $goals = $this->goalsService->calculate([
            new Team('team 1', 1),
            new Team('team 2', 1)
        ]);

        $this->assertEquals([3, 2], $goals);

    }

    public function testThatSecondTeamWon()
    {
        $this->goalsService->method('calculatePoints')->will($this->onConsecutiveCalls(false, true));

        $goals = $this->goalsService->calculate([
            new Team('team 1', 1),
            new Team('team 2', 1)
        ]);

        $this->assertEquals([2, 3], $goals);

    }
}
