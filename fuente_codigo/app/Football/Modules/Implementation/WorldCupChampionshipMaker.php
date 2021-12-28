<?php

namespace App\Football\Modules\Implementation;

use App\Football\Managers\ChampionshipManager;
use App\Football\Managers\MatchCardManager;
use App\Football\Managers\MatchGameManager;
use App\Football\Managers\MatchGoalManager;
use App\Football\Managers\PhaseGroupManager;
use App\Football\Managers\PhaseManager;
use App\Football\Managers\TeamPhaseGroupManager;
use App\Football\Models\Championship;
use App\Football\Models\MatchGame;
use App\Football\Models\Phase;
use App\Football\Models\PhaseGroup;
use App\Football\Models\Team;
use App\Football\Repositories\PhaseRepository;
use App\Football\Repositories\TeamPhaseGroupRepository;
use App\ProjectElements\AppDispatcher;
use App\ProjectInterfaces\FootballSimulator\ChampionshipMakerInterface;
use App\ProjectInterfaces\FootballSimulator\MatchGameSimulatorInterface;

class WorldCupChampionshipMaker implements ChampionshipMakerInterface
{
    private const AMOUNT_OF_TEAMS = 32;
    private const LOCAL_TEAM = "local_team";
    private const VISITOR_TEAM = "visitor_team";
    private const YELLOW_CARD = "yellow_card";
    private const RED_CARD = "red_card";

    public static function generateChampionship(string $name, int $year, int $month): Championship
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();
        $worldCupChampionship = ChampionshipManager::createEntity([
            "name" => "World Cup $name",
            "championshipYear" => $year,
            "championshipMonth" => $month 
        ]);
        $persistenceManager::saveEntity($worldCupChampionship);
        $persistenceManager::refreshEntity($worldCupChampionship);
        return $worldCupChampionship;
    }

    public static function generateChampionshipPhases(Championship $championship): void
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();

        $groupsPhase = PhaseManager::createEntity([
            "orderPhase" => 1,
            "name" => "Groups stage",
            "championship" => $championship
        ]);
        $persistenceManager::saveEntity($groupsPhase);

        $roundOf16 = PhaseManager::createEntity([
            "orderPhase" => 2,
            "name" => "Round of 16",
            "championship" => $championship
        ]);
        $persistenceManager::saveEntity($roundOf16);

        $quarterFinals = PhaseManager::createEntity([
            "orderPhase" => 3,
            "name" => "Quarter finals",
            "championship" => $championship
        ]);
        $persistenceManager::saveEntity($quarterFinals);

        $semifinal = PhaseManager::createEntity([
            "orderPhase" => 4,
            "name" => "Semifinal",
            "championship" => $championship
        ]);
        $persistenceManager::saveEntity($semifinal);

        $final = PhaseManager::createEntity([
            "orderPhase" => 5,
            "name" => "Final",
            "championship" => $championship
        ]);
        $persistenceManager::saveEntity($final);


        $persistenceManager::refreshEntity($championship);
    }

    public static function getAmountInitialTeams(): int
    {
        return self::AMOUNT_OF_TEAMS;
    }

    public static function executeSimulation(
        Championship $championship, 
        $teams,
        MatchGameSimulatorInterface $matchSimulator
    ): void {
        $phases = $championship->getPhases();
        foreach ($phases as $phase) {
            self::generateGroupsPhase($phase, $teams);
            self::generatePhaseMatches($phase);
            self::executePhaseMatches($phase, $matchSimulator);
            $teams = self::selectTeamsNextPhase($phase);
        }
    }

    public static function getChampionTeam(Championship $championship): ?Team
    {
        $championTeam = null;
        $phaseRepository = new PhaseRepository();
        $finalPhase = $phaseRepository->findPhaseOfChampionship(
            $championship,
            5
        );
        if ($finalPhase) {
            $finalPhaseGroups = $finalPhase->getPhaseGroups();
            if (sizeof($finalPhaseGroups) == 1) {
                $finalMatch = $finalPhaseGroups[0]->getMatchGames()[0];
                $championTeam = $finalMatch->getWinnerTeam();
            }
        }

        return $championTeam;
    }


    public static function getStandingPhaseGroup(PhaseGroup $phaseGroup): array
    {
        $sortedTeams = self::sortPhaseGroupTeams($phaseGroup, true);
        return $sortedTeams;
    }

    private static function generateGroupsPhase(Phase $phase, $avaiableTeams)
    {
        $orderPhase = $phase->getOrderPhase();
        $persistenceManager = AppDispatcher::getPersistenceManager();
        $variableNames = range('A', 'Z');
        $constantName = "Group";
        $amountTeams = 8;

        if ($orderPhase == 2) {
            $constantName = "Key ";
            $amountTeams = 8;
        }

        if ($orderPhase == 3) {
            $constantName = "Key ";
            $amountTeams = 4;
        }

        if ($orderPhase == 4) {
            $constantName = "Key ";
            $amountTeams = 2;
        }

        if ($orderPhase == 5) {
            $constantName = "Key ";
            $amountTeams = 1;
        }

        $groupsGenerated = array();
        $indexVarItem = 0;
        foreach ($avaiableTeams as $team) {
            $groupsGenerated[$indexVarItem][] = $team;
            $indexVarItem ++;
            if ($indexVarItem >= $amountTeams) {
                $indexVarItem = 0;
            }
        }

        foreach ($groupsGenerated as $indexVarItem => $teams) {

            $newPhaseGroup = PhaseGroupManager::createEntity([
                "phase" => $phase,
                "name" => $constantName . " " . $variableNames[$indexVarItem]
            ]);
            $persistenceManager::saveEntity($newPhaseGroup);

            foreach ($teams as $team) {

                $newTeamPhaseGroup = TeamPhaseGroupManager::createEntity([
                    "phaseGroup" => $newPhaseGroup,
                    "team" => $team
                ]);
                $persistenceManager::saveEntity($newTeamPhaseGroup);
            }

        }
    }

    private static function generatePhaseMatches(Phase $phase)
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();
        $championship = $phase->getChampionship();
        $phaseGroups = $phase->getPhaseGroups();

        $groupNumber = 1;
        foreach ($phaseGroups as $phaseGroup) {

            $matchesGroup = array();
            $teamsPhaseGroup = $phaseGroup->getTeamsPhaseGroups();
            $teamsInGroup = array();
            foreach ($teamsPhaseGroup as $teamPhaseGroup) {
                $teamsInGroup[] = $teamPhaseGroup->getTeam();
            }
            $amountTeamsInGroup = sizeof($teamsInGroup);
            $localFirst = true;

            for ($i = 0; $i < $amountTeamsInGroup; $i++) {
                for ($j = $i + 1; $j < $amountTeamsInGroup; $j++) {
                    $localIndex = $localFirst ? $i : $j;
                    $visitorIndex = $localFirst ? $j : $i;
                    $matchesGroup[] = (object) [
                        "local" => $teamsInGroup[$localIndex],
                        "visitor" => $teamsInGroup[$visitorIndex],
                    ];
                    $localFirst = ! $localFirst;
                }
            }

            //shuffle($matchesGroup);

            $matchNumber = 0;
            foreach ($matchesGroup as $matchGroup) {
                $newMatchGame = MatchGameManager::createEntity([
                    "matchNumber" => $groupNumber + ($matchNumber * 8),
                    "localTeam" => $matchGroup->local,
                    "visitorTeam" => $matchGroup->visitor,
                    "championship" => $championship,
                    "phaseGroup" => $phaseGroup
                ]);
                $persistenceManager::saveEntity($newMatchGame);
                $matchNumber ++;
            }

            $groupNumber ++;
        }
    }

    private static function executePhaseMatches(Phase $phase, MatchGameSimulatorInterface $matchSimulator) 
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();
        $teamPhaseGroupRepository = new TeamPhaseGroupRepository();
        $phaseGroups = $phase->getPhaseGroups();
        foreach ($phaseGroups as $phaseGroup) {
            $matchGames = $phaseGroup->getMatchGames();
            foreach ($matchGames as $matchGame) {

                $dataSimulated = $matchSimulator::simulateMatch($matchGame);
                self::addGoalsToMatch($matchGame, $dataSimulated->local->goals, self::LOCAL_TEAM);
                self::addGoalsToMatch($matchGame, $dataSimulated->visitor->goals, self::VISITOR_TEAM);
                self::addCardsToMatch($matchGame, $dataSimulated->local->yellow_cards, self::LOCAL_TEAM, self::YELLOW_CARD);
                self::addCardsToMatch($matchGame, $dataSimulated->visitor->yellow_cards, self::VISITOR_TEAM, self::YELLOW_CARD);
                self::addCardsToMatch($matchGame, $dataSimulated->local->red_cards, self::LOCAL_TEAM, self::RED_CARD);
                self::addCardsToMatch($matchGame, $dataSimulated->visitor->red_cards, self::VISITOR_TEAM, self::RED_CARD);
                $matchGame->setWinnerTeam($dataSimulated->winner);
                $matchGame->setWayToWin($dataSimulated->wayToWin);
                $persistenceManager::saveEntity($matchGame);

                $teamPhaseGroup = $teamPhaseGroupRepository->findTeamPhaseGroup(
                    $matchGame->getWinnerTeam(),
                    $matchGame->getPhaseGroup()
                );
                $teamPhaseGroup->setPoints($teamPhaseGroup->getPoints() + 3);
                $persistenceManager::saveEntity($teamPhaseGroup);
            }
        }
    }

    private static function addGoalsToMatch(MatchGame $matchGame, $data, string $teamType)
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();
        $amountGoals = 0;
        foreach ($data as $dataGoals) {
            $amountGoals ++;
            $matchGoal = MatchGoalManager::createEntity([
                "half" => $dataGoals->half,
                "minute" => $dataGoals->minute,
                "player" => $dataGoals->player,
                "matchGame" => $matchGame
            ]);
            $persistenceManager::saveEntity($matchGoal);
        }
        if ($teamType == self::LOCAL_TEAM) {
            $matchGame->setLocalGoals($amountGoals);
        } else {
            $matchGame->setVisitorGoals($amountGoals);
        }
        $persistenceManager::saveEntity($matchGame);
    }

    private static function addCardsToMatch(MatchGame $matchGame, $data, string $teamType, string $cardType)
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();
        $amountCards = 0;
        foreach ($data as $dataCard) {
            $amountCards ++;
            $matchGoal = MatchCardManager::createEntity([
                "type" => $cardType,
                "half" => $dataCard->half,
                "minute" => $dataCard->minute,
                "player" => $dataCard->player,
                "matchGame" => $matchGame
            ]);
            $persistenceManager::saveEntity($matchGoal);
        }
        if ($teamType == self::LOCAL_TEAM && $cardType == self::YELLOW_CARD) {
            $matchGame->setLocalYellowCards($amountCards);
        }
        if ($teamType == self::LOCAL_TEAM && $cardType == self::RED_CARD) {
            $matchGame->setLocalRedCards($amountCards);
        }
        if ($teamType == self::VISITOR_TEAM && $cardType == self::YELLOW_CARD) {
            $matchGame->setVisitorYellowCards($amountCards);
        }
        if ($teamType == self::VISITOR_TEAM && $cardType == self::RED_CARD) {
            $matchGame->setVisitorRedCards($amountCards);
        }
        $persistenceManager::saveEntity($matchGame);

    }

    private static function selectTeamsNextPhase(Phase $phase)
    {
        $amountTeamsPass = 1;
        if ($phase->getOrderPhase() == 1) {
            $amountTeamsPass = 2;
        }
        $teamsSelected = array();
        $phaseGroups = $phase->getPhaseGroups();

        foreach ($phaseGroups as $phaseGroup) {

            $sortedPhaseTeams = self::sortPhaseGroupTeams($phaseGroup);
            $groupTeamsSelected = array_slice($sortedPhaseTeams, 0, $amountTeamsPass);
            $teamsSelected = array_merge(
                $teamsSelected,
                $groupTeamsSelected
            );
        }

        return $teamsSelected;
    }

    private static function sortPhaseGroupTeams(PhaseGroup $phaseGroup, bool $returnPhaseGroup = false) 
    {
        $originalArrayGroup = array();
        $sortedPhaseGroup = array();
        $teamsPhaseGroup = $phaseGroup->getTeamsPhaseGroups();

        $index = 0;
        foreach ($teamsPhaseGroup as $teamPhaseGroup) {
            $maskIndex = "i_$index";
            $originalArrayGroup[$maskIndex] = $returnPhaseGroup ? $teamPhaseGroup : $teamPhaseGroup->getTeam();
            $sortedPhaseGroup[$maskIndex] = $teamPhaseGroup->getPoints();
            $index++;
        }
        asort($sortedPhaseGroup);
        $sortedPhaseGroup = array_reverse($sortedPhaseGroup);
        $returnArray = array();
        foreach ($sortedPhaseGroup as $maskIndex => $element) {
            $returnArray[] = $originalArrayGroup[$maskIndex];
        }
        return $returnArray;
    }


}