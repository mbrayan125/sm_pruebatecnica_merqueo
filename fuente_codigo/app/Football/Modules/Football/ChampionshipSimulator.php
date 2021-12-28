<?php

namespace App\Football\Modules\Football;

use App\Football\Models\Championship;
use App\Football\Models\Team;
use App\Football\Modules\Implementation\BestRankTeamChooser;
use App\Football\Modules\Implementation\SimpleMatchGameSimulator;
use App\Football\Modules\Implementation\WorldCupChampionshipMaker;
use App\Football\Repositories\MatchGameRepository;
use App\Football\Repositories\PhaseRepository;
use App\ProjectInterfaces\FootballSimulator\ChampionshipMakerInterface;
use App\ProjectInterfaces\FootballSimulator\ChampionshipTeamChooserInterface;
use App\ProjectInterfaces\FootballSimulator\MatchGameSimulatorInterface;

class ChampionshipSimulator
{
    private $championshipMaker;
    private $teamChooser;
    private $matchSimulator;
    private $report;

    public function __construct(
        ?ChampionshipMakerInterface $championshipMaker = null,
        ?ChampionshipTeamChooserInterface $teamChooser = null,
        ?MatchGameSimulatorInterface $matchSimulator = null
    ) {
        if (is_null($championshipMaker)) {
            $championshipMaker = new WorldCupChampionshipMaker();
        }
        if (is_null($teamChooser)) {
            $teamChooser = new BestRankTeamChooser();
        }
        if (is_null($matchSimulator)) {
            $matchSimulator = new SimpleMatchGameSimulator();
        }
        $this->championshipMaker = $championshipMaker;
        $this->teamChooser = $teamChooser;
        $this->matchSimulator = $matchSimulator;
        $this->report = array();
    }

    public function startSimulation(
        string $name,
        int $year,
        int $month
    ) {

        $newChampionship = $this->championshipMaker::generateChampionship($name, $year, $month);
        $this->championshipMaker::generateChampionshipPhases($newChampionship);
        $teamsForSimulation = $this->teamChooser::selectChampionshipTeams(
            $this->championshipMaker::getAmountInitialTeams()
        );
        $this->championshipMaker::executeSimulation(
            $newChampionship,
            $teamsForSimulation,
            $this->matchSimulator
        );

        return $this->makeReport($newChampionship);
    }

    public function makeSimulationStandingsReport($championship)
    {
        $phaseRepository = new PhaseRepository();
        $initialPhase = $phaseRepository->findPhaseOfChampionship(
            $championship,
            1
        );
        $showTeamStandings = array();
        if ($initialPhase) {
            $initialPhaseGroups = $initialPhase->getPhaseGroups();
            foreach ($initialPhaseGroups as $initialPhaseGroup) {
                $teamGroups = $initialPhaseGroup->getTeamsPhaseGroups();
                foreach($teamGroups as $teamGroup) {
                    $team = $teamGroup->getTeam();
                    $teamStandings = $this->matchSimulator::calculateChampionshipStats($team, $championship);
                    $showTeamStandings[$team->getCountry()] = $teamStandings;
                }
            }
            ksort($showTeamStandings);
        }

        foreach ($showTeamStandings as $country => $standings) {

            $this->addReportTitle("Standings for $country");
            $this->addReportLine(" > " . $standings->allGoals . " goals");
            $this->addReportLine(" > " . $standings->allLostMatches . " matches lost");
            $this->addReportLine(" > " . $standings->allWinMatches . " matches win");
            $this->addReportLine(" > " . $standings->allYellowCards . " yellow cards");
            $this->addReportLine(" > " . $standings->allRedCards . " red cards");
            $this->addReportLine("");
        }

        return $this->report;
    }

    public function makeReport($championship)
    {
        $this->addReportLine("");
        $this->addReportTitle("Championship " . $championship->getId() . " " . $championship->getName());
        $this->addReportLine("");
        $phases = $championship->getPhases();
        foreach ($phases as $phase) {
            $phaseGroups = $phase->getPhaseGroups();

            foreach ($phaseGroups as $phaseGroup) {
                $this->addReportTitle($phase->getName() . " > " . $phaseGroup->getName(), true);
                $phaseGroupMatches = $phaseGroup->getMatchGames();

                foreach ($phaseGroupMatches as $match) {

                    $teamWinner = $match->getWinnerTeam();
                    $lineMark = sprintf(
                        " > %s [ %d ] vs [ %d ] %s -> %s win %s",
                        $match->getLocalTeam()->getCountry(),
                        $match->getLocalGoals(),
                        $match->getVisitorGoals(),
                        $match->getVisitorTeam()->getCountry(),
                        $teamWinner->getCountry() ,
                        $match->getWayToWin() != "Goals" ? " on " .$match->getWayToWin() : ""
                    );

                    $this->addReportLine($lineMark);
                    $matchGoals = $match->getGoals();
                    $insertLines = array();
                    foreach ($matchGoals as $matchGoal) {
                        $lineGoal = sprintf(
                            " - %d'' %d %s (%s)",
                            $matchGoal->getMinute(),
                            $matchGoal->getPlayer()->getDorsalNumber(),
                            $matchGoal->getPlayer()->getDorsalName(),
                            $matchGoal->getPlayer()->getTeam()->getCountry()
                        );
                        $this->timeSort($insertLines, $matchGoal->getMinute(), $lineGoal);
                    }
                    ksort($insertLines);
                    $this->addReportLine("      ---  Goals  ---");
                    $this->addReportArray($insertLines);


                    $matchCards = $match->getCards();
                    $insertLines = array();
                    foreach ($matchCards as $matchCard) {
                        $lineGoal = sprintf(
                            " - %d'' %d %s (%s) - %s",
                            $matchCard->getMinute(),
                            $matchCard->getPlayer()->getDorsalNumber(),
                            $matchCard->getPlayer()->getDorsalName(),
                            $matchCard->getPlayer()->getTeam()->getCountry(),
                            $matchCard->getType()
                        );
                        $this->timeSort($insertLines, $matchCard->getMinute(), $lineGoal);
                    }
                    ksort($insertLines);
                    $this->addReportLine("      ---  Cards  ---");
                    $this->addReportArray($insertLines);


                    $this->addReportSeparator();

                }
                $this->addReportLine($phaseGroup->getName() . " final standing");
                $finalStanding = $this->championshipMaker::getStandingPhaseGroup($phaseGroup);


                $position = 1;
                foreach ($finalStanding as $teamStanding) {
                    $lineStanding = sprintf(
                        " %d - %s -> %d Points",
                        $position,
                        $teamStanding->getTeam()->getCountry(),
                        $teamStanding->getPoints()
                    );
                    $this->addReportLine($lineStanding);
                    $position ++;
                }

                $this->addReportSeparator();

            }
        }

        $championTeam = $this->championshipMaker::getChampionTeam(
            $championship
        );

        if ($championTeam) {
            $this->addReportTitle("Champion team -> " . $championTeam->getCountry() . " congratulations!");
        }

        return $this->makeSimulationStandingsReport($championship);
    }

    private function addReportTitle(string $title, bool $mainTitle = false)
    {
        $characters = $mainTitle ? "=======================" : "---------------------------------";
        $this->report[] = $characters;
        $this->report[] = $title;
        $this->report[] = $characters;
        $this->report[] = "";
    }

    private function addReportLine(string $line)
    {
        $this->report[] = $line;
    }

    private function addReportArray(array $lines) 
    {
        $this->report = array_merge(
            $this->report,
            $lines
        );
    }

    private function addReportSeparator()
    {
        $this->report[] = "";
        $this->report[] = "---------------------------";
        $this->report[] = "";
    }

    private function timeSort(array &$array, $time, $line)
    {
        if (array_key_exists($time, $array)) {
            $array[$time] .= " " . $line;
        } else {
            $array[$time] = $line;
        }
    }
}