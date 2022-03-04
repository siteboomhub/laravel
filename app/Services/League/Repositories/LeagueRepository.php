<?php

namespace App\Services\League\Repositories;

use App\Services\League\Classes\League;
use App\Services\League\Factories\LeagueFactoryRestore;
use Illuminate\Contracts\Cache\Repository;

class LeagueRepository
{
    public function __construct(
        private Repository $cacheRepository,
        private LeagueFactoryRestore $leagueFactoryRestore
    )
    {
    }

    public function save(League $league): void
    {
        $this->cacheRepository->put($this->getStorageKey($league->getUuid()), [
            'week' => $league->getCurrentWeek(),
            'teams' => $league->getTeams(),
            'matches' => $league->getMatches(),
            'per_week' => $league->getMatchesPerWeek(),
            'last_played_matches' => $league->getLastPlayedMatches()
        ]);
    }

    public function get(string $league_uuid): League
    {
        $details = $this->cacheRepository->get($this->getStorageKey($league_uuid));

        return $this->leagueFactoryRestore->restore(
            $league_uuid,
            $details['per_week'],
            $details['teams'],
            $details['matches'],
            $details['week'],
            $details['last_played_matches']
        );
    }

    private function getStorageKey(string $uuid): string
    {
        return 'league-' . $uuid;
    }
}
