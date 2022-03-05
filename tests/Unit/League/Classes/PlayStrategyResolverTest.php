<?php

namespace Tests\Unit\League\Classes;

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
        $playStrategyResolver = new PlayStrategyResolver();

        $result = $playStrategyResolver
            ->resolve($type);

        $this->assertInstanceOf(PlayStrategyInterface::class, $result);

    }
}
