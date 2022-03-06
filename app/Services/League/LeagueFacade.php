<?php

namespace App\Services\League;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string createAndSave(int $matches_per_week = 2, int $teams_number = 4)
 * @method static array playWeek(string $league_uuid)
 * @method static array playAllWeeks(string $league_uuid)
 * @method static array getLeagueResults(string $league_uuid)
 */
class LeagueFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'league';
    }
}
