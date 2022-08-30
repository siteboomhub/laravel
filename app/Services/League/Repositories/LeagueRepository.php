<?php

namespace App\Services\League\Repositories;

use App\Services\League\Entities\League;
use App\Services\League\Factories\LeagueFactoryRestore;
use Illuminate\Contracts\Cache\Repository;

interface LeagueRepository
{
    public function save(League $league): void;

    public function ofUUID(string $league_uid): League;
}
