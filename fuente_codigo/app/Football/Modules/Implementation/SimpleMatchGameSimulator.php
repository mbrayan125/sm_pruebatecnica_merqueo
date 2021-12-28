<?php

namespace App\Football\Modules\Implementation;

use App\Football\Models\Team;
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

    public static function simulateMatch(Team $localTeam, Team $visitorTeam): stdClass
    {
        $localGoals = self::generateGoalsTeam($localTeam);
        $visitorGoals = self::generateGoalsTeam($visitorTeam);

        $localYellowCards = self::generateCards($localTeam, self::YELLOW_CARD);
        $visitorYellowCards = self::generateCards($visitorTeam, self::YELLOW_CARD);

        $localRedCards = self::generateCards($localTeam, self::RED_CARD);
        $visitorRedCards = self::generateCards($visitorTeam, self::RED_CARD);

        return (object) [
            "local" => [
                "goals" => $localGoals,
                "yellow_cards" => $localYellowCards,
                "red_cards" => $localRedCards,
            ],
            "visitor" => [
                "goals" => $visitorGoals,
                "yellow_cards" => $visitorYellowCards,
                "red_cards" => $visitorRedCards,
            ]
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
        return $players[$randNumber]->getId();
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
}