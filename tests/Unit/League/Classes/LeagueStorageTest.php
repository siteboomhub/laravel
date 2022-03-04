<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Classes\League;
use App\Services\League\Repositories\LeagueRepository;
use App\Services\League\Factories\LeagueFactoryRestore;
use Illuminate\Contracts\Cache\Repository;
use PHPUnit\Framework\TestCase;

class LeagueStorageTest extends TestCase
{
    private Repository $storage;

    private LeagueFactoryRestore $restoreFactory;

    private LeagueRepository $leagueStorage;

    protected function setUp(): void
    {
        $this->storage = $this->createMock(Repository::class);

        $this->restoreFactory = $this->createMock(LeagueFactoryRestore::class);

        $this->leagueStorage = new LeagueRepository(
            $this->storage,
            $this->restoreFactory
        );
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
        $league = $this->createMock(League::class);

        $league->method('getUuid')->willReturn($uuid);
        $league->method('getCurrentWeek')->willReturn($week);
        $league->method('getTeams')->willReturn($teams);
        $league->method('getMatches')->willReturn($matches);
        $league->method('getMatchesPerWeek')->willReturn($per_week);
        $league->method('getLastPlayedMatches')->willReturn($last_played_matches);

        $this->storage->expects($this->once())->method('put')
            ->with(
                $this->equalTo('league-' . $uuid),
                $this->equalTo([
                    'week' => $week,
                    'teams' => $teams,
                    'matches' => $matches,
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
                'matches' => $matches,
                'per_week' => $per_week,
                'last_played_matches' => $last_played_matches
            ]);

        $this->restoreFactory->expects($this->once())
            ->method('restore')
            ->with(
                $this->equalTo($uuid),
                $this->equalTo($per_week),
                $this->equalTo($teams),
                $this->equalTo($matches),
                $this->equalTo($week),
                $this->equalTo($last_played_matches),
            );

        $this->leagueStorage->get($uuid);
    }
}
