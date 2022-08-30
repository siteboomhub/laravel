<?php

namespace Tests\Unit\League\Repositories;

use App\Services\League\Entities\League;
use App\Services\League\Repositories\LeagueCacheRepository;
use App\Services\League\Factories\LeagueFactoryRestore;
use Illuminate\Contracts\Cache\Repository;
use PHPUnit\Framework\TestCase;

class LeagueStorageTest extends TestCase
{
    private Repository $storage;
    private LeagueCacheRepository $leagueStorage;

    protected function setUp(): void
    {
        $this->storage = $this->createMock(Repository::class);

        $this->restoreFactory = $this->createMock(LeagueFactoryRestore::class);

        $this->leagueStorage = new LeagueCacheRepository($this->storage);
    }

    public function provider()
    {
        return [
            ['123', 1, [], [], 2, []]
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testThatStoragePutCallWithCorrectParams($uuid, $week, $teams, $matches, $per_week, $last_played_matches)
    {
        $league = $this->getMockBuilder(League::class)
            ->setConstructorArgs([$uuid, $teams, $per_week, $matches])->getMock();

        $league->method('currentWeek')->willReturn($week);
        $league->method('lastPlayedMatches')->willReturn($last_played_matches);

        $this->storage->expects($this->once())->method('put')
            ->with(
                $this->equalTo('league-' . $uuid),
                $this->equalTo([
                    'week' => $week,
                    'teams' => $teams,
                    'games' => $matches,
                    'per_week' => $per_week,
                    'last_played_matches' => $last_played_matches
                ])
            );

        $this->leagueStorage->save($league);
    }

    /**
     * @dataProvider provider
     */
    public function testThatLeagueCreatingIsCorrect($uuid, $week, $teams, $matches, $per_week, $last_played_matches)
    {
        $this->storage->expects($this->once())->method('get')
            ->with($this->equalTo('league-' . $uuid))
            ->willReturn([
                'week' => $week,
                'teams' => $teams,
                'games' => $matches,
                'per_week' => $per_week,
                'last_played_matches' => $last_played_matches
            ]);

        $this->leagueStorage->ofUUID($uuid);
    }
}
