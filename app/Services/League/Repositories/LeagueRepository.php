<?php

namespace App\Services\League\Repositories;

use App\Services\League\Entities\League;

interface LeagueRepository
{
    public function save(League $league): void;

    public function ofUUID(string $league_uid): League;
}
