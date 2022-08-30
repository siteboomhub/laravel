<?php

namespace Tests\Unit\League\Entities;

use App\Services\League\Entities\League;
use App\Services\League\Classes\PlayStrategyResolver;
use App\Services\League\Strategies\PlayStrategyInterface;
use App\Services\League\ValueObjects\Uid;
use Illuminate\Contracts\Events\Dispatcher;
use PHPUnit\Framework\TestCase;

class LeagueTest extends TestCase
{
    protected function setUp(): void
    {
        $this->playStrategyResolver = $this->createMock(PlayStrategyResolver::class);
        $this->dispatcher = $this->createMock(Dispatcher::class);
        $this->playStrategyContract = $this->createMock(PlayStrategyInterface::class);
    }

    private function getWorkedLeague($teams_number = 4)
    {
        return new League(
            uniqid(), array_fill(0, $teams_number, []), 2, []
        );
    }

    public function timesProvider()
    {
        return [
            ['week'],
            ['all']
        ];
    }

    public function testUid()
    {
        $league = $this->getWorkedLeague();

        $this->assertInstanceOf(Uid::class, $league->uid);
    }

    public function testThatTeamsNumberIsTheSame()
    {
        $teams_amount = 5;

        $league = $this->getWorkedLeague($teams_amount);

        $teams = $league->teams;

        $this->assertIsArray($teams);
        $this->assertCount($teams_amount, $teams);
    }

    public function testThatLeagueTeamsIsArray()
    {
        $league = $this->getWorkedLeague();

        $this->assertIsArray($league->games);
    }

    public function testThatGetMatchesPerWeekIsInteger()
    {
        $league = $this->getWorkedLeague();

        $this->assertIsInt($league->games_per_week);
    }
}
