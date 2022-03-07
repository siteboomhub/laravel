<?php

namespace Tests\Unit\League\Events;

use App\Services\League\Entities\League;
use App\Events\League\LeaguePlayedEvent;
use App\Services\League\Repositories\LeagueRepository;
use Illuminate\Contracts\Cache\Repository;
use PHPUnit\Framework\TestCase;

class LeaguePlayedEventTest extends TestCase
{
    /**
     * @var Repository|mixed|\PHPUnit\Framework\MockObject\Stub
     */
    private mixed $storage;

    public function provider()
    {
        return [
            [ ['team 1'], ['team 2'] ]
        ];
    }

    protected function setUp(): void
    {
        $this->storage = $this->createStub(LeagueRepository::class);
    }

    /**
     * @dataProvider provider
     */
    public function testThatTeamsAreSet($teams)
    {
        $league = $this->createStub(League::class);

        $league->method('getTeams')->willReturn($teams);

        $this->storage->method('get')->willReturn($league);

        $leaguePlayedEvent = new LeaguePlayedEvent($league);

        $this->assertEquals($teams, $leaguePlayedEvent->getTeams());
    }
}
