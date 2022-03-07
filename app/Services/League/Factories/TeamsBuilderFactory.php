<?php

namespace App\Services\League\Factories;


use App\Exceptions\League\NotEnoughTeamsException;
use App\Services\League\Entities\Team;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class TeamsBuilderFactory
{
    private const CLUBS_FILE_PATH = 'public/league-clubs.json';

    public function __construct(private Filesystem $storage)
    {
    }

    /**
     * @throws FileNotFoundException|NotEnoughTeamsException
     */
    public function build(int $teams_number): array
    {
        $all_clubs = Arr::shuffle($this->getClubsContent());

        if ($teams_number > count($all_clubs)) {
            throw new NotEnoughTeamsException("Your file with clubs doesn't have enough teams");
        }

        $teams = [];

        $default_prediction = round(100 / $teams_number, 1);

        for ($i = 0; $i < $teams_number; $i++) {
            $teams[] = new Team($all_clubs[$i]['name'], $default_prediction);
        }

        return $teams;
    }

    private function getClubsContent(): array
    {
        try {
            return json_decode($this->storage->get(self::CLUBS_FILE_PATH), true);
        } catch (FileNotFoundException) {
            throw new FileNotFoundException('Please, check clubs file is exists');
        }
    }
}
