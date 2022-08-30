<?php

namespace Tests\Unit\League\Events;

use App\Services\League\Entities\League;
use App\Events\League\LeaguePlayedEvent;
use App\Services\League\Repositories\LeagueCacheRepository;
use App\Services\League\ValueObjects\Uid;
use Illuminate\Contracts\Cache\Repository;
use PHPUnit\Framework\TestCase;

class LeaguePlayedEventTest extends TestCase
{
    public function testThatTeamsAreSet()
    {
        $uid = uniqid();

        $event = new LeaguePlayedEvent($uid);

        $this->assertEquals($event->uid, $uid);
    }
}
