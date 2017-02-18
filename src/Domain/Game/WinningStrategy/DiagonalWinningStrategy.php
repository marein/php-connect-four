<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;
use Marein\ConnectFour\Domain\Game\Point;

final class DiagonalWinningStrategy implements WinningStrategy
{
    /**
     * @inheritdoc
     */
    public function calculate(Configuration $configuration, Board $board)
    {
        if (!$board->lastUsedField()) {
            return false;
        }

        $stone = $board->lastUsedField()->stone();
        $point = $board->lastUsedField()->point();
        $requiredMatches = $configuration->requiredMatches();

        $fields1 = $board->findFieldsByPoints(
            Point::createPointsInDiagonalDown($point, $configuration->size())
        );
        $fields2 = $board->findFieldsByPoints(
            Point::createPointsInDiagonalUp($point, $configuration->size())
        );

        // Create a string representation of fields e.g. "000121 000121"
        $haystack = implode($fields1) . ' ' . implode($fields2);
        // Create a string like "1111|2222" depending on the stone and the required matches.
        $needle = str_repeat($stone->color(), $requiredMatches->value());

        // Check whether "1111|2222" is in "000121 000121"
        return strpos($haystack, $needle) !== false;
    }
}