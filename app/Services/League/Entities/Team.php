<?php

namespace App\Services\League\Entities;

class Team
{
    private string $uuid;

    private int $played = 0;

    private int $won = 0;

    private int $drawn = 0;

    private int $lost = 0;

    private int $pts = 0;

    private int $gd = 0;

    public function __construct(private string $name, private int $prediction)
    {
        $this->uuid = uniqid();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrediction(): int
    {
        return $this->prediction;
    }

    public function getPTS(): int
    {
        return $this->pts;
    }

    public function getPlayed(): int
    {
        return $this->played;
    }

    public function getWon(): int
    {
        return $this->won;
    }

    public function getDrawn(): int
    {
        return $this->drawn;
    }

    public function getLost(): int
    {
        return $this->lost;
    }

    public function getGd(): int
    {
        return $this->gd;
    }


    public function setPrediction(int $value)
    {
        $this->prediction = $value;
    }

    public function addGameResults(GameTeamResults $gameTeamResults)
    {
        $this->played++;

        $gd = $gameTeamResults->getGd();

        $this->pts += $gameTeamResults->getPts();
        $this->gd += $gd;

        if ($gd > 0) {
            $this->won++;
        } else if ($gd < 0) {
            $this->lost++;
        } else {
            $this->drawn++;
        }
    }
}
