<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Classes\CalculateGoals;
use App\Services\League\Classes\PlayStrategyResolver;
use App\Services\League\Strategies\PlayStrategyInterface;
use PHPUnit\Framework\TestCase;

class PlayStrategyResolverTest extends TestCase
{
    public function provider()
    {
        return [
            ['week'],
            ['all'],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testThatReturnCorrectObject($type)
    {
        $mockCalculateGoalsService = $this->createMock(CalculateGoals::class);

        $playStrategyResolver = new PlayStrategyResolver($mockCalculateGoalsService);

        $result = $playStrategyResolver
            ->resolve($type);

        $this->assertInstanceOf(PlayStrategyInterface::class, $result);

    }
}
