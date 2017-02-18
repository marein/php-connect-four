<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;

final class HorizontalWinningStrategy implements WinningStrategy
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

        // Create a string representation of fields e.g. "000121"
        $haystack = implode($board->findFieldsByRow($point->y()));
        // Create a string like "1111|2222" depending on the stone and the required matches.
        $needle = str_repeat($stone->color(), $requiredMatches->value());

        // Check whether "1111|2222" is in "000121"
        return strpos($haystack, $needle) !== false;
    }
}