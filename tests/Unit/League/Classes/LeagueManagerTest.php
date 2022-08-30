<?php

namespace Tests\Unit\League\Classes;

use App\Exceptions\League\LeagueAlreadyFinishedException;
use App\Services\League\Classes\LeagueManager;
use App\Services\League\Classes\PlayStrategyResolver;
use App\Services\League\Entities\League;
use App\Services\League\Responses\LeagueResults;
use App\Services\League\Repositories\LeagueCacheRepository;
use App\Services\League\Factories\LeagueFactory;
use App\Services\League\Strategies\PlayStrategyInterface;
use App\Services\League\ValueObjects\LeagueConfiguration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Events\Dispatcher;

class LeagueManagerTest extends TestCase
{
    private LeagueCacheRepository $leagueStorage;
    private LeagueFactory $leagueFactory;
    private LeagueManager $leagueManager;
    private PlayStrategyResolver $playStrategyResolver;
    private Dispatcher $dispatcher;

    protected function setUp(): void
    {
        $this->leagueStorage = $this->createMock(LeagueCacheRepository::class);
        $this->leagueFactory = $this->createMock(LeagueFactory::class);
        $leagueResults = $this->createStub(LeagueResults::class);
        $this->playStrategyResolver = $this->createStub(PlayStrategyResolver::class);
        $this->dispatcher = $this->createStub(Dispatcher::class);

        $this->leagueManager = new LeagueManager(
            $this->leagueStorage,
            $this->leagueFactory,
            $leagueResults,
            $this->playStrategyResolver,
            $this->dispatcher
        );

        $this->playStrategy = $this->createMock(PlayStrategyInterface::class);

        $this->playStrategyResolver->method('resolve')->willReturn($this->playStrategy);
    }

    public function paramsProvider()
    {
        return [
            [2, 4]
        ];
    }

    /**
     * @dataProvider paramsProvider
     */
    public function testThatLeagueCreated($matches_per_week, $teams_number)
    {
        $leagueConfiguration = new LeagueConfiguration($matches_per_week, $teams_number);

        $league = $this->getFakeLeague();

        $this->leagueFactory
            ->expects($this->once())
            ->method('build')
            ->with($leagueConfiguration)
            ->willReturn($league);

        $this->leagueManager->createAndSave($matches_per_week, $teams_number);
    }

    /**
     * @dataProvider paramsProvider
     */
    public function testThatLeagueSaved($matches_per_week, $teams_number)
    {
        $leagueMock = $this->getFakeLeague();

        $this->leagueFactory->method('build')->willReturn($leagueMock);

        $this->leagueStorage
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($leagueMock));

        $this->leagueManager->createAndSave($matches_per_week, $teams_number);
    }

    /**
     * @dataProvider paramsProvider
     */
    public function testThatResultIsUidWhenCreated($matches_per_week, $teams_number)
    {
        $pre_uuid = uniqid();

        $leagueMock = $this->getFakeLeague($pre_uuid);

        $this->leagueFactory->method('build')->willReturn($leagueMock);

        $uuid = $this->leagueManager->createAndSave($matches_per_week, $teams_number);

        $this->assertEquals($pre_uuid, $uuid);
    }

    public function testThatPlayWeekAllMethodsInvoked()
    {
        $pre_uuid = uniqid();
        $expected_week = rand(0, 10);
        $games_per_week = 2;

        $this->playStrategy->method('play')->willReturn([
            'last_played_matches' => array_fill(0, $games_per_week, []),
            'week' => $expected_week
        ]);

        $leagueMock = $this->getMockBuilder(League::class)->setConstructorArgs([
            $pre_uuid, [], $games_per_week, []
        ])->onlyMethods(['setCurrentWeek', 'setLastPlayedMatches', 'isLeagueFinished'])->getMock();

        $leagueMock->method('isLeagueFinished')->willReturn(false);

        $this->leagueStorage->expects($this->once())
            ->method('ofUUID')
            ->with($this->equalTo($pre_uuid))
            ->willReturn($leagueMock);

        $leagueMock->expects($this->once())->method('setCurrentWeek')->with($this->equalTo($expected_week));

        $leagueMock->expects($this->once())->method('setLastPlayedMatches')
            ->with($this->countOf($games_per_week));

        $this->leagueStorage->expects($this->once())
            ->method('save')
            ->with($this->equalTo($leagueMock));

        $this->leagueManager->play($pre_uuid);
    }

    public function testThatFinishedLeagueCannotBePlayed()
    {
        $pre_uuid = uniqid();
        $games_per_week = 2;

        $leagueMock = $this->getMockBuilder(League::class)->setConstructorArgs([
            $pre_uuid, [], $games_per_week, [], 0
        ])->getMock();

        $leagueMock->method('isLeagueFinished')->willReturn(true);

        $this->leagueStorage->method('ofUUID')->willReturn($leagueMock);

        $this->expectException(LeagueAlreadyFinishedException::class);
        $this->leagueManager->play($pre_uuid);
    }

    public function testThatLeagueResultsAreCorrect()
    {
        $pre_uuid = uniqid();

        $leagueResults = $this->createMock(LeagueResults::class);

        $leagueResults->method('build')->willReturn([]);

        $this->leagueStorage
            ->expects($this->once())
            ->method('ofUUID')
            ->with($this->equalTo($pre_uuid));

        $results = $this->leagueManager->getLeagueResults($pre_uuid);

        $this->assertEquals([], $results);
    }

    private function getFakeLeague(string $uid = null, int $games_per_week = 0): League&MockObject
    {
        if(is_null($uid)){
            $uid = uniqid();
        }

        return $this->getMockBuilder(League::class)->setConstructorArgs([
            $uid, [], $games_per_week, []
        ])->getMock();
    }
}
