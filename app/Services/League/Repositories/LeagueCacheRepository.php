<?php

namespace App\Services\League\Repositories;

use App\Services\League\Entities\League;

class LeagueCacheRepository extends AbstractRepository implements LeagueRepository
{
    public function save(League $league): void
    {
        $this->cache->put($this->getStorageKey($league->uid->value), [
            'week' => $league->currentWeek(),
            'teams' => $league->teams,
            'games' => $league->games,
            'per_week' => $league->games_per_week,
            'last_played_matches' => $league->lastPlayedMatches()
        ]);
    }

    public function ofUUID(string $league_uid): League
    {
        $details = $this->cache->get($this->getStorageKey($league_uid));

        $league = new League(
            $league_uid,
            $details['teams'],
            $details['per_week'],
            $details['games'],
            $details['week']
        );

        $league->setLastPlayedMatches($details['last_played_matches']);

        return $league;
    }

    private function getStorageKey(string $uid): string
    {
        return 'league-' . $uid;
    }
}
