<?php

namespace Tests\Unit\League\Classes;

use App\Services\League\Classes\TeamsBuilder;
use App\Exceptions\League\NotEnoughTeamsException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\TestCase;

class TeamsBuilderTest extends TestCase
{
    private Storage $storage;

    private TeamsBuilder $teamsBuilder;

    private function getFileContent()
    {
        return json_encode([
            [
                'name' => 'team 1'
            ],
            [
                'name' => 'team 2'
            ],
            [
                'name' => 'team 3'
            ],
            [
                'name' => 'team 4'
            ],
            [
                'name' => 'team 5'
            ],
            [
                'name' => 'team 6'
            ],
            [
                'name' => 'team 7'
            ],
        ]);
    }

    protected function setUp(): void
    {
        $this->storage = $this->createStub(Filesystem::class);
        $this->teamsBuilder = new TeamsBuilder($this->storage);

        $this->storage->method('get')->willReturn(
            $this->getFileContent()
        );
    }

    public function testBuild()
    {
        $teams_amount = 5;

        $teams = $this->teamsBuilder->build($teams_amount);

        $this->assertIsArray($teams);

        $this->assertCount($teams_amount, $teams);
    }

    public function testThatNotEnoughTeamsInFile()
    {
        $this->expectException(NotEnoughTeamsException::class);

        $this->teamsBuilder->build(500);
    }
}
