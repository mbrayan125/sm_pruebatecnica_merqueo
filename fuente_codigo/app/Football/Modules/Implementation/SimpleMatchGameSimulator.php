<?php

namespace App\Football\Modules\Implementation;

use App\Football\Models\Championship;
use App\Football\Models\MatchGame;
use App\Football\Models\Team;
use App\Football\Repositories\MatchGameRepository;
use App\ProjectInterfaces\FootballSimulator\MatchGameSimulatorInterface;
use Exception;
use stdClass;

class SimpleMatchGameSimulator implements MatchGameSimulatorInterface
{
    private const YELLOW_CARD = "yellow_card";
    private const RED_CARD = "red_card";

    private const MIN_GOALS = 0;
    private const MAX_GOALS = 6;

    private const MIN_YELLOW_CARDS = 0;
    private const MAX_YELLOW_CARDS = 4;

    private const MIN_RED_CARDS = 0;
    private const MAX_RED_CARDS = 2;

    public static function simulateMatch(MatchGame $match): stdClass
    {
        $localTeam = $match->getLocalTeam();
        $visitorTeam = $match->getVisitorTeam();

        $localGoals = self::generateGoalsTeam($localTeam);
        $visitorGoals = self::generateGoalsTeam($visitorTeam);

        $localYellowCards = self::generateCards($localTeam, self::YELLOW_CARD);
        $visitorYellowCards = self::generateCards($visitorTeam, self::YELLOW_CARD);

        $localRedCards = self::generateCards($localTeam, self::RED_CARD);
        $visitorRedCards = self::generateCards($visitorTeam, self::RED_CARD);

        $returnData = new stdClass();
        $returnData->local = (object) [
                "goals" => $localGoals,
                "yellow_cards" => $localYellowCards,
                "red_cards" => $localRedCards,
        ];
        $returnData->visitor = (object) [
                "goals" => $visitorGoals,
                "yellow_cards" => $visitorYellowCards,
                "red_cards" => $visitorRedCards,
        ];
        $winnerData = self::determineWinner(
            $match->getChampionship(),
            $localTeam, 
            $visitorTeam, 
            $returnData->local, 
            $returnData->visitor
        );
        $returnData->winner = $winnerData->winner;
        $returnData->wayToWin = $winnerData->wayToWin;

        return $returnData;
    }

    public static function calculateChampionshipStats(Team $team, Championship $championship): stdClass
    {
        $allGoals = 0;
        $allYellowCards = 0;
        $allRedCards = 0;
        $allMatches = 0;
        $allWinMatches = 0;

        $matchGameRepository = new MatchGameRepository();

        $localMatches = $matchGameRepository->getMatchesLocalFromTeamInChampionship(
            $championship,
            $team
        );
        foreach ($localMatches as $match) {
            if ($team == $match->getWinnerTeam()) {
                $allWinMatches ++;
            }
            $allGoals += $match->getLocalGoals();
            $allYellowCards += $match->getLocalYellowCards();
            $allRedCards += $match->getLocalRedCards();
            $allMatches ++;
        }

        $visitorMatches = $matchGameRepository->getMatchesVisitorFromTeamInChampionship(
            $championship,
            $team
        );
        foreach ($visitorMatches as $match) {
            if ($team == $match->getWinnerTeam()) {
                $allWinMatches ++;
            }
            $allGoals += $match->getVisitorGoals();
            $allYellowCards += $match->getVisitorYellowCards();
            $allRedCards += $match->getVisitorRedCards();
            $allMatches ++;
        }

        return (object) [
            "allGoals" => $allGoals,
            "allYellowCards" => $allYellowCards,
            "allRedCards" => $allRedCards,
            "allLostMatches" => $allMatches - $allWinMatches,
            "allWinMatches" => $allWinMatches
        ];
    }

    private static function generateGoalsTeam(Team $team): array
    {
        $goalsArray = array();
        $amountGoals = rand(self::MIN_GOALS, self::MAX_GOALS);
        for ($i = 0 ; $i <= $amountGoals; $i++) {
            $dataGoal = new stdClass();
            $dataGoal->player = self::chooseRandomPlayer($team);
            $timeGenerated = self::chooseRandomTime();
            $dataGoal->half = $timeGenerated->half;
            $dataGoal->minute = $timeGenerated->minute;
            $goalsArray[] = $dataGoal;
        }
        return $goalsArray;
    }

    private static function generateCards(Team $team, string $cardType)
    {
        $cardsArray = array();
        $minValue = $cardType == self::YELLOW_CARD ? self::MIN_YELLOW_CARDS : self::MIN_RED_CARDS;
        $maxValue = $cardType == self::YELLOW_CARD ? self::MAX_YELLOW_CARDS : self::MAX_RED_CARDS;
        $amountYellowCards = rand($minValue, $maxValue);
        for ($i = 0 ; $i <= $amountYellowCards; $i++) {
            $dataCard = new stdClass();
            $dataCard->player = self::chooseRandomPlayer($team);
            $timeGenerated = self::chooseRandomTime();
            $dataCard->half = $timeGenerated->half;
            $dataCard->minute = $timeGenerated->minute;
            $cardsArray[] = $dataCard;
        }
        return $cardsArray;
    }

    private static function chooseRandomPlayer(Team $team)
    {
        $players = $team->getPlayers();
        $randNumber = rand(0, sizeof($players) -1);
        return $players[$randNumber];
    }

    private static function chooseRandomTime(): stdClass
    {
        $half = rand(1, 2);
        $minute = rand(1, 48) + ($half == 1 ? 0 : 45);
        return (object) [
            "minute" => $minute,
            "half" => $half
        ];
    }

    private static function determineWinner(
        Championship $championship,
        Team $localTeam, 
        Team $visitorTeam, 
        stdClass $localData, 
        stdClass $visitorData
    ): stdClass
    {
        $dataWinner = new stdClass();

        $localGoals = sizeof($localData->goals);
        $visitorGoals = sizeof($visitorData->goals);

        if ($localGoals != $visitorGoals) {
            $dataWinner->wayToWin = "Goals";
            $dataWinner->winner = $localGoals > $visitorGoals ? $localTeam : $visitorTeam;
            return $dataWinner;
        }

        $localTeamStats = self::calculateChampionshipStats($localTeam, $championship);
        $visitorTeamStats = self::calculateChampionshipStats($visitorTeam, $championship);

        $localLostMatches = $localTeamStats->allLostMatches;
        $visitorLostMatches = $visitorTeamStats->allLostMatches;

        if ($localLostMatches != $visitorLostMatches) {
            $dataWinner->wayToWin = "Lost matches";
            $dataWinner->winner = $localLostMatches < $visitorLostMatches ? $localTeam : $visitorTeam;
            return $dataWinner;
        }

        $localChampionshipGoals = $localTeamStats->allGoals;
        $visitorChampionshipGoals = $visitorTeamStats->allGoals;

        if ($localChampionshipGoals != $visitorChampionshipGoals) {
            $dataWinner->wayToWin = "Amount goals";
            $dataWinner->winner = $localChampionshipGoals > $visitorChampionshipGoals ? $localTeam : $visitorTeam;
            return $dataWinner;
        }

        $localYellowCards = sizeof($localData->yellow_cards);
        $visitorYellowCards = sizeof($visitorData->yellow_cards);
        $localChampionshipYellowCards = $localTeamStats->allYellowCards + $localYellowCards;
        $visitorChampionshipYellowCards = $visitorTeamStats->allYellowCards + $visitorYellowCards;

        if ($localChampionshipYellowCards != $visitorChampionshipYellowCards) {
            $dataWinner->wayToWin = "Yellow cards";
            $dataWinner->winner = $localChampionshipYellowCards < $visitorChampionshipYellowCards ? $localTeam : $visitorTeam;
            return $dataWinner;
        }

        $localRedCards = sizeof($localData->red_cards);
        $visitorRedCards = sizeof($visitorData->red_cards);
        $localChampionshipRedCards = $localTeamStats->allRedCards + $localRedCards;
        $visitorChampionshipRedCards = $visitorTeamStats->allRedCards + $visitorRedCards;

        if ($localChampionshipRedCards != $visitorChampionshipRedCards) {
            $dataWinner->wayToWin = "Red cards";
            $dataWinner->winner = $localChampionshipRedCards < $visitorChampionshipRedCards ? $localTeam : $visitorTeam;
            return $dataWinner;
        }

        $dataWinner->wayToWin = "Local state";
        $dataWinner->winner = $localTeam;
        return $dataWinner;
    }
}