<?php

namespace Tests\Unit\League\Entities;

use App\Services\League\Entities\League;
use App\Services\League\Classes\PlayStrategyResolver;
use App\Services\League\Strategies\PlayStrategyInterface;
use App\Events\League\LeaguePlayedEvent;
use App\Exceptions\League\LeagueAlreadyFinishedException;
use App\Services\League\ValueObjects\LeagueCreating;
use Illuminate\Contracts\Events\Dispatcher;
use PHPUnit\Framework\TestCase;

class LeagueTest extends TestCase
{
    private PlayStrategyResolver $playStrategyResolver;
    private Dispatcher $dispatcher;

    private PlayStrategyInterface $playStrategyContract;

    private LeagueCreating $leagueCreating;

    protected function setUp(): void
    {
        $this->playStrategyResolver = $this->createMock(PlayStrategyResolver::class);
        $this->dispatcher = $this->createMock(Dispatcher::class);
        $this->playStrategyContract = $this->createMock(PlayStrategyInterface::class);
        $this->leagueCreating = $this->createMock(LeagueCreating::class);

        $this->leagueCreating->method('getMatchesPerWeek')
            ->willReturn(2);
    }

    private function getWorkedLeague($teams_number = 4)
    {
        return new League(
            $this->leagueCreating,
            2,
            array_fill(0, $teams_number, [])
        );
    }

    public function timesProvider()
    {
        return [
            ['week'],
            ['all']
        ];
    }

    public function testThatUuidIsString()
    {
        $league = $this->getWorkedLeague();

        $this->assertIsString($league->getUuid());
    }

    public function testThatTeamsNumberIsTheSame()
    {
        $teams_amount = 5;

        $this->leagueCreating->method('getTeams')->willReturn(
            array_fill(0, $teams_amount, [])
        );

        $league = $this->getWorkedLeague($teams_amount);

        $teams = $league->getTeams();

        $this->assertIsArray($teams);
        $this->assertCount($teams_amount, $teams);
    }

    /**
     * @dataProvider timesProvider
     */
    public function testThatResolverGetCorrectTimeAndDispatched($time)
    {
        $league = $this->getWorkedLeague();

        $this->playStrategyContract->method('play')
            ->willReturn([
                'matches' => [],
                'week' => 1
            ]);

        $this->playStrategyResolver
            ->method('resolve')
            ->with($this->equalTo($time))
            ->willReturn($this->playStrategyContract);

        $this->leagueCreating->method('getPlayStrategyResolver')->willReturn(
            $this->playStrategyResolver
        );

        $this->dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(LeaguePlayedEvent::class, $league);

        $this->leagueCreating->method('getDispatcher')->willReturn($this->dispatcher);

        $league->play($time);

        $this->assertEquals(1, $league->getCurrentWeek());
    }

    public function testThatWeReceiveExceptionWhenWantToPlayFinishedLeague()
    {
        $this->leagueCreating->method('getMatches')
            ->willReturn(
                array_fill(0, 12, [])
            );

        $league = new League(
            $this->leagueCreating,
            6,
        );

        $this->expectException(LeagueAlreadyFinishedException::class);

        $league->play();

    }

    public function testThatLeagueTeamsIsArray()
    {
        $league = $this->getWorkedLeague();

        $this->assertIsArray($league->getMatches());
    }

    public function testThatGetMatchesPerWeekIsInteger()
    {
        $league = $this->getWorkedLeague();

        $this->assertIsInt($league->getMatchesPerWeek());
    }
}
