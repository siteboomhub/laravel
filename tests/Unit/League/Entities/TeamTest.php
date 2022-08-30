<?php

namespace Tests\Unit\League\Entities;


use App\Services\League\Entities\Team;
use App\Services\League\ValueObjects\GameTeamResults;
use App\Services\League\ValueObjects\Uid;
use PHPUnit\Framework\TestCase;

class TeamTest extends TestCase
{
    private Team $team;

    private GameTeamResults $gameTeamResults;

    protected function setUp(): void
    {
        $this->team = new Team('Manchester');
        $this->gameTeamResults = $this->createStub(GameTeamResults::class);
    }

    public function testThatNameIsCorrect()
    {
        $this->assertEquals('Manchester', $this->team->name());
    }

    public function testThatUuidIsString()
    {
        $this->assertInstanceOf(Uid::class, $this->team->uid);
    }

    public function testThatPtsIsInteger()
    {
        $this->assertIsInt($this->team->pts());
    }

    public function testThatGDIsInteger()
    {
        $this->assertIsInt($this->team->gd());
    }

    public function testNewGameWon()
    {
        $played = $this->team->played();

        $this->gameTeamResults->method('getGd')->willReturn(1);
        $this->gameTeamResults->method('getPts')->willReturn(3);

        $this->team->addGameResults($this->gameTeamResults);

        $this->assertNotEquals($played, $this->team->played());
        $this->assertEquals(1, $this->team->gd());
        $this->assertEquals(3, $this->team->pts());
        $this->assertEquals(1, $this->team->won());
    }

    public function testNewFailGame()
    {
        $this->gameTeamResults->method('getGd')->willReturn(-1);

        $this->team->addGameResults($this->gameTeamResults);

        $this->assertEquals(-1, $this->team->gd());
        $this->assertEquals(1, $this->team->lost());

    }

    public function testNewDrawnGame()
    {
        $this->gameTeamResults->method('getGd')->willReturn(0);

        $this->team->addGameResults($this->gameTeamResults);

        $this->assertEquals(0, $this->team->gd());
        $this->assertEquals(1, $this->team->drawn());
    }
}
