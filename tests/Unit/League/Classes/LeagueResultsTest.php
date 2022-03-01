<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Classes\League;
use App\Services\League\Responses\LeagueResults;
use App\Services\League\Classes\Team;
use PHPUnit\Framework\TestCase;

class LeagueResultsTest extends TestCase
{
    private LeagueResults $leagueResults;

    private League $league;

    private Team $team;

    protected function setUp(): void
    {
        $this->league = $this->createMock(League::class);

        $this->team = $this->createMock(Team::class);

        $this->league->method('getTeams')->willReturn([$this->team]);

        $this->leagueResults = new LeagueResults($this->league);
    }

    public function provider()
    {
        return [
            1
        ];
    }

    public function testThatResultFormatIsCorrect()
    {
        $results = $this->leagueResults->format();

        $this->assertArrayHasKey('current_week', $results);
        $this->assertArrayHasKey('teams', $results);
        $this->assertArrayHasKey('last_played_matches', $results);

        foreach ($results['teams'] as $team){
            $this->assertArrayHasKey('name', $team);
            $this->assertArrayHasKey('pts', $team);
            $this->assertArrayHasKey('played', $team);
            $this->assertArrayHasKey('won', $team);
            $this->assertArrayHasKey('drawn', $team);
            $this->assertArrayHasKey('lost', $team);
            $this->assertArrayHasKey('gd', $team);
            $this->assertArrayHasKey('prediction_score', $team);
        }
    }

    /**
     * @param $current_week
     * @dataProvider provider
     */
    public function testThatResultsAreCorrect($current_week)
    {
        $results = $this->leagueResults->format();

        $this->assertEquals($current_week, $results['current_week']);
        $this->assertEquals([$this->team], $results['teams']);
    }
}
