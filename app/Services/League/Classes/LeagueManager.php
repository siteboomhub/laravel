<?php

namespace App\Services\League\Classes;

use App\Events\League\LeaguePlayedEvent;
use App\Exceptions\League\AmountOfTeamsOnlyOddException;
use App\Exceptions\League\LeagueAlreadyFinishedException;
use App\Exceptions\League\MatchesNumberException;
use App\Services\League\Factories\LeagueFactory;
use App\Services\League\Repositories\LeagueRepository;
use App\Services\League\Responses\LeagueResults;
use App\Services\League\ValueObjects\LeagueConfiguration;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Contracts\Events\Dispatcher;

class LeagueManager
{
    public function __construct(
        private readonly LeagueRepository     $leagueRepository,
        private readonly LeagueFactory        $leagueFactory,
        private readonly LeagueResults        $leagueResults,
        private readonly PlayStrategyResolver $playStrategyResolver,
        private readonly Dispatcher           $dispatcher
    )
    {
    }

    /**
     * @throws MatchesNumberException|AmountOfTeamsOnlyOddException
     * @throws AmountOfTeamsOnlyOddException
     */
    public function createAndSave(int $games_per_week = 2, int $teams_number = 4): string
    {
        $league_configuration = new LeagueConfiguration($games_per_week, $teams_number);

        $league = $this->leagueFactory->build($league_configuration);

        $this->leagueRepository->save($league);

        return $league->uid->value;
    }

    /**
     * @throws LeagueAlreadyFinishedException
     */
    public function play(string $league_uid, string $type = 'week'): void
    {
        $league = $this->leagueRepository->ofUUID($league_uid);

        if ($league->isLeagueFinished()) {
            throw new LeagueAlreadyFinishedException('This League already finished');
        }

        $playStrategy = $this->playStrategyResolver->resolve($type);

        [
            'last_played_matches' => $last_played_matches,
            'week' => $current_week
        ] = $playStrategy->play($league->games_per_week, $league->currentWeek(), $league->games);

        $league->setCurrentWeek($current_week);
        $league->setLastPlayedMatches($last_played_matches);

        $this->leagueRepository->save($league);

        $this->dispatcher->dispatch(LeaguePlayedEvent::class, $league->uid->value);
    }

    #[ArrayShape(['current_week' => "int", 'teams' => "array"])]
    public function getLeagueResults(string $league_uid): array
    {
        $league = $this->leagueRepository->ofUUID($league_uid);

        return $this->leagueResults->build($league);
    }
}
