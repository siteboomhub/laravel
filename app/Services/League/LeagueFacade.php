<?php

namespace App\Services\League;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string createAndSave(int $games_per_week = 2, int $teams_number = 4)
 * @method static array play(string $league_uid, string $type = 'week')
 * @method static array getLeagueResults(string $league_uid)
 */
class LeagueFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'league';
    }
}
