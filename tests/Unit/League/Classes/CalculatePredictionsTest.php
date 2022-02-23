<?php

namespace Tests\Unit\NewLeague;

use App\Services\League\Classes\League;
use App\Services\League\Classes\Team;
use App\Services\League\Listeners\CalculatePredictions;
use PHPUnit\Framework\MockObject\Stub\Stub;
use PHPUnit\Framework\TestCase;

class CalculatePredictionsTest extends TestCase
{
    private CalculatePredictions $listener;

    private League|Stub $league;

    private Team|Stub $team;

    protected function setUp(): void
    {
        $this->listener = new CalculatePredictions();
        $this->league = $this->createStub(League::class);
        $this->team = $this->createStub(Team::class);
    }

    public function provider()
    {
        return [
            [ [25, 5], [83, 17] ]
        ];
    }

    /**
     * @param $pts
     * @param $predictions
     * @dataProvider provider
     */
    public function testThatExpectedPredictionsAreCorrect(array $pts, array $predictions)
    {
        $this->team->method('getPts')->willReturnOnConsecutiveCalls(...$pts);

        $teams = array_fill(0, count($pts), $this->team);

        $this->league->method('getTeams')->willReturn($teams);

        $ptsAmount = count($pts);

        $i = 0;

        while ($i < $ptsAmount){
            $this->team->expects($this->once())
                ->method('setPrediction')
                ->with($predictions[$i]);
            $i++;
        }

        $this->listener->handle($this->league);
    }
}
