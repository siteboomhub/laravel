<?php

namespace App\Services\League\Entities;

use App\Services\League\ValueObjects\GameTeamResults;
use App\Services\League\ValueObjects\Uid;

class Team
{
    public readonly Uid $uid;
    private int $played = 0;
    private int $won = 0;
    private int $drawn = 0;
    private int $lost = 0;
    private int $pts = 0;
    private int $gd = 0;

    public function __construct(private readonly string $name)
    {
        $this->uid = new Uid(uniqid());
    }

    public function name(): string
    {
        return $this->name;
    }

    public function pts(): int
    {
        return $this->pts;
    }

    public function played(): int
    {
        return $this->played;
    }

    public function won(): int
    {
        return $this->won;
    }

    public function drawn(): int
    {
        return $this->drawn;
    }

    public function lost(): int
    {
        return $this->lost;
    }

    public function gd(): int
    {
        return $this->gd;
    }

    public function addGameResults(GameTeamResults $gameTeamResults): void
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

    public function equals(Team $team): bool
    {
        return $this->uid->equals($team->uid);
    }
}
