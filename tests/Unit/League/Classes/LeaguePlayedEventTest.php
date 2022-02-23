<?php

namespace Tests\Unit\NewLeague;

use App\Services\League\Classes\League;
use App\Services\Events\League\LeaguePlayedEvent;
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
            ['team 1', 'team 2']
        ];
    }

    protected function setUp(): void
    {
        $this->storage = $this->createStub(LeagueStorage::class);
    }

    /**
     * @dataProvider provider
     */
    public function testThatTeamsAreSet($teams)
    {
        $league = $this->createStub(League::class);

        $league->method('getTeams')->willReturn($teams);

        $this->storage->method('get')->willReturn($league);

        $leaguePlayedEvent = new LeaguePlayedEvent($this->storage, 'uuid');

        $this->assertEquals($teams, $leaguePlayedEvent->getTeams());
    }
}
