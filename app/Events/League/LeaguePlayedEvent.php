<?php

namespace App\Events\League;

use App\Services\League\Entities\League;
use App\Services\League\Entities\Team;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\Pure;

class LeaguePlayedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private array $teams;

    #[Pure] public function __construct(private League $league)
    {
        $this->teams = $this->league->getTeams();
    }

    /**
     * @return Team[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }
}
