<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Classes\GameTeamResults;
use App\Services\League\Classes\Team;

use PHPUnit\Framework\TestCase;

class TeamTest extends TestCase
{
    private Team $team;

    private GameTeamResults $gameTeamResults;

    protected function setUp(): void
    {
        $this->team = new Team('Manchester', 12);
        $this->gameTeamResults = $this->createStub(GameTeamResults::class);
    }

    public function testThatPredictionIsCorrect()
    {
        $this->team->setPrediction(12);

        $this->assertEquals(12, $this->team->getPrediction());
    }

    public function testThatNameIsCorrect()
    {
        $this->assertEquals('Manchester', $this->team->getName());
    }

    public function testThatUuidIsString()
    {
        $this->assertIsString($this->team->getUuid());
    }

    public function testThatPtsIsInteger()
    {
        $this->assertIsInt($this->team->getPTS());
    }

    public function testThatGDIsInteger()
    {
        $this->assertIsInt($this->team->getGd());
    }

    public function testNewGameWon()
    {
        $played = $this->team->getPlayed();

        $this->gameTeamResults->method('getGd')->willReturn(1);
        $this->gameTeamResults->method('getPts')->willReturn(3);

        $this->team->addGameResults($this->gameTeamResults);

        $this->assertNotEquals($played, $this->team->getPlayed());
        $this->assertEquals(1, $this->team->getGd());
        $this->assertEquals(3, $this->team->getPTS());
        $this->assertEquals(1, $this->team->getWon());
    }

    public function testNewFailGame()
    {
        $this->gameTeamResults->method('getGd')->willReturn(-1);

        $this->team->addGameResults($this->gameTeamResults);

        $this->assertEquals(-1, $this->team->getGd());
        $this->assertEquals(1, $this->team->getLost());

    }

    public function testNewDrawnGame()
    {
        $this->gameTeamResults->method('getGd')->willReturn(0);

        $this->team->addGameResults($this->gameTeamResults);

        $this->assertEquals(0, $this->team->getGd());
        $this->assertEquals(1, $this->team->getDrawn());
    }
}
