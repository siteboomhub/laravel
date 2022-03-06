<?php

namespace App\Services\League\Classes;

use App\Services\League\Factories\LeagueFactory;
use App\Services\League\Repositories\LeagueRepository;
use App\Services\League\Responses\LeagueResults;
use JetBrains\PhpStorm\ArrayShape;

class LeagueInterface
{
    public function __construct(
        private LeagueRepository $leagueStorage,
        private LeagueFactory    $leagueFactory,
        private LeagueResults    $leagueResults,
        private LeagueCreatingValidation $leagueCreatingValidation
    )
    {
    }

    /**
     * @throws \App\Exceptions\League\MatchesNumberException
     */
    public function createAndSave(int $matches_per_week = 2, int $teams_number = 4): string
    {
        $this->leagueCreatingValidation->validate();

        $league = $this->leagueFactory->build($matches_per_week, $teams_number);

        $this->leagueStorage->save($league);

        return $league->getUuid();
    }

    public function playWeek(string $league_uuid)
    {
        $league = $this->leagueStorage->get($league_uuid);

        $league->play();

        $this->leagueStorage->save($league);
    }

    public function playAllWeeks(string $league_uuid)
    {
        $league = $this->leagueStorage->get($league_uuid);

        $league->play('all');

        $this->leagueStorage->save($league);
    }

    #[ArrayShape(['current_week' => "int", 'teams' => "array"])]
    public function getLeagueResults(string $league_uuid): array
    {
        $league = $this->leagueStorage->get($league_uuid);

        return $this->leagueResults->build($league);
    }
}
