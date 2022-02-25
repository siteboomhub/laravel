<?php

namespace Tests\Unit\NewLeague;

use App\Services\League\Classes\League;
use App\Services\League\Classes\PlayStrategyResolver;
use App\Services\League\PlayStrategyInterface;
use App\Events\League\LeaguePlayedEvent;
use App\Services\League\Exceptions\LeagueAlreadyFinishedException;
use App\Services\League\Exceptions\MatchesNumberException;
use App\Services\League\Factories\MatchesPlannerFactory;
use Illuminate\Contracts\Events\Dispatcher;
use PHPUnit\Framework\TestCase;

class LeagueTest extends TestCase
{
    private PlayStrategyResolver $playStrategyResolver;
    private MatchesPlannerFactory $matchesPlannerFactory;
    private Dispatcher $dispatcher;

    private PlayStrategyInterface $playStrategyContract;

    protected function setUp(): void
    {
        $this->playStrategyResolver = $this->createMock(PlayStrategyResolver::class);
        $this->matchesPlannerFactory = $this->createMock(MatchesPlannerFactory::class);
        $this->dispatcher = $this->createMock(Dispatcher::class);
        $this->playStrategyContract = $this->createMock(PlayStrategyInterface::class);
    }

    private function getWorkedLeague($teams_number = 4)
    {
        return new League(
            uniqid(),
            array_fill(0, $teams_number, []),
            $this->playStrategyResolver,
            $this->matchesPlannerFactory,
            $this->dispatcher,
            2,
            [[], [], [], []]
        );
    }

    public function timesProvider()
    {
        return [
            ['week'],
            ['all']
        ];
    }

    public function testThatMatchesNotEnoughForLeaguePlay()
    {
        //think about formula
        // 1 game per week 2 teams
        // 2 game per week 3 teams
        // 3 game per week 3 teams
        // 4 game per week 4 teams
        // 5 game per week 4 teams

        // 1, 2, 3, 4

        // 1, 2 - one
        // 2, 3 - second
        // 3, 4 - third
        // 1, 3
        // 1, 4
        //
        $this->expectException(MatchesNumberException::class);

        new League(
            uniqid(),
            [[], [], [], []],
            $this->playStrategyResolver,
            $this->matchesPlannerFactory,
            $this->dispatcher,
            20
        );
    }

    public function testThatMatchesPerWeekIsLessThanMaxAllowed()
    {
        $this->expectException(MatchesNumberException::class);

        new League(
            uniqid(),
            [[], [], [], []],
            $this->playStrategyResolver,
            $this->matchesPlannerFactory,
            $this->dispatcher,
            20
        );
    }

    public function testThatUuidIsString()
    {
        $league = $this->getWorkedLeague();

        $this->assertIsString($league->getUuid());
    }

    public function testThatTeamsNumberIsTheSame()
    {
        $teams_amount = 5;

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

        $this->dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(LeaguePlayedEvent::class, $league);

        $league->play($time);

        $this->assertEquals(1, $league->getCurrentWeek());
    }

    public function testThatWeReceiveExceptionWhenWantToPlayFinishedLeague()
    {
        $league = new League(
            uniqid(),
            [[], [], [], []],
            $this->playStrategyResolver,
            $this->matchesPlannerFactory,
            $this->dispatcher,
            2,
            array_fill(0, 12, []),
            current_week: 6
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
