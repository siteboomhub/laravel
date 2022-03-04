<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Entities\League;
use App\Services\League\Classes\LeagueInterface;
use App\Services\League\Responses\LeagueResults;
use App\Services\League\Repositories\LeagueRepository;
use App\Services\League\Factories\LeagueFactory;
use PHPUnit\Framework\TestCase;

class LeagueInterfaceTest extends TestCase
{
    private LeagueRepository $leagueStorage;
    private LeagueFactory $leagueFactory;
    private LeagueInterface $leagueInterface;

    protected function setUp(): void
    {
        $this->leagueStorage = $this->createMock(LeagueRepository::class);
        $this->leagueFactory = $this->createMock(LeagueFactory::class);
        $leagueResults = $this->createStub(LeagueResults::class);

        $this->leagueInterface = new LeagueInterface(
            $this->leagueStorage,
            $this->leagueFactory,
            $leagueResults
        );
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
        $this->leagueFactory
            ->expects($this->once())
            ->method('build')
            ->with($this->equalTo($matches_per_week), $this->equalTo($teams_number))
            ->willReturn($this->createStub(League::class));

        $this->leagueInterface->createAndSave($matches_per_week, $teams_number);
    }

    /**
     * @dataProvider paramsProvider
     */
    public function testThatLeagueSaved($matches_per_week, $teams_number)
    {
        $leagueMock = $this->createMock(League::class);

        $this->leagueFactory->method('build')->willReturn($leagueMock);

        $this->leagueStorage
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($leagueMock));

        $this->leagueInterface->createAndSave($matches_per_week, $teams_number);
    }

    /**
     * @dataProvider paramsProvider
     */
    public function testThatResultIsUuidWhenCreated($matches_per_week, $teams_number)
    {
        $pre_uuid = uniqid();

        $leagueMock = $this->createMock(League::class);

        $leagueMock->method('getUuid')->willReturn($pre_uuid);

        $this->leagueFactory->method('build')->willReturn($leagueMock);

        $uuid = $this->leagueInterface->createAndSave($matches_per_week, $teams_number);

        $this->assertEquals($pre_uuid, $uuid);
    }

    public function testThatPlayWeekAllMethodsInvoked()
    {
        $pre_uuid = uniqid();

        $leagueMock = $this->createMock(League::class);

        $this->leagueStorage->expects($this->once())
            ->method('get')
            ->with($this->equalTo($pre_uuid))
            ->willReturn($leagueMock);

        $leagueMock->expects($this->once())->method('play');

        $this->leagueStorage->expects($this->once())
            ->method('save')
            ->with($this->equalTo($leagueMock));

        $this->leagueInterface->playWeek($pre_uuid);
    }

    public function testThatPlayAllWeeksAllMethodsInvoked()
    {
        $uuid = uniqid();

        $leagueMock = $this->createMock(League::class);

        $this->leagueStorage->expects($this->once())
            ->method('get')
            ->with($this->equalTo($uuid))
            ->willReturn($leagueMock);

        $leagueMock->expects($this->once())
            ->method('play')
            ->with($this->equalTo('all'));

        $this->leagueStorage->expects($this->once())
            ->method('save')
            ->with($this->equalTo($leagueMock));

        $this->leagueInterface->playAllWeeks($uuid);

    }

    public function testThatLeagueResultsIsCorrect()
    {
        $pre_uuid = uniqid();

        $leagueResults = $this->createMock(LeagueResults::class);

        $leagueResults->method('build')->willReturn([]);

        $this->leagueStorage
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($pre_uuid));

        $results = $this->leagueInterface->getLeagueResults($pre_uuid);

        $this->assertEquals([], $results);
    }
}
