<?php

namespace Tests\Unit\NewLeague;

use App\Services\League\Classes\GameTeamResults;
use PHPUnit\Framework\TestCase;

class GameTeamResultsTest extends TestCase
{
    public function goalsAndTeamUuidForGDProvider()
    {
        return [
            [ ['123' => 2, '234' => 3], '123', -1],
            [ ['123' => 2, '234' => 2], '123', 0],
            [ ['123' => 3, '234' => 3], '123', 0],
            [ ['123' => 3, '234' => 2], '123', 1],
        ];
    }

    public function goalsAndTeamUuidForPTSProvider()
    {
        return [
            [ ['123' => 2, '234' => 3], '123', 0],
            [ ['123' => 2, '234' => 2], '123', 1],
            [ ['123' => 3, '234' => 3], '123', 1],
            [ ['123' => 3, '234' => 2], '123', 3],
        ];
    }

    /**
     * @param $goals
     * @param $uuid
     * @param $expectedGD
     * @dataProvider goalsAndTeamUuidForGDProvider
     */
    public function testThatGDCorrectlyCalculated($goals, $uuid, $expectedGD)
    {
        $gameTeamResults = new GameTeamResults($goals, $uuid);

        $this->assertEquals($expectedGD, $gameTeamResults->getGd());
    }

    /**
     * @param $goals
     * @param $uuid
     * @param $expectedPTS
     * @dataProvider goalsAndTeamUuidForPTSProvider
     */
    public function testThatPTSCorrectlyCalculated($goals, $uuid, $expectedPTS)
    {
        $gameTeamResults = new GameTeamResults($goals, $uuid);

        $this->assertEquals($expectedPTS, $gameTeamResults->getPts());
    }
}
