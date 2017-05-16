<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;
use Marein\ConnectFour\Domain\Game\Exception\InvalidNumberOfRequiredMatchesException;

final class HorizontalWinningStrategy implements WinningStrategy
{
    const MINIMUM = 4;

    /**
     * @var int
     */
    private $numberOfRequiredMatches;

    /**
     * HorizontalWinningStrategy constructor.
     *
     * @param int $numberOfRequiredMatches
     *
     * @throws InvalidNumberOfRequiredMatchesException
     */
    public function __construct(int $numberOfRequiredMatches)
    {
        if ($numberOfRequiredMatches < self::MINIMUM) {
            throw new InvalidNumberOfRequiredMatchesException('The value must be at least ' . self::MINIMUM . '.');
        }

        $this->numberOfRequiredMatches = $numberOfRequiredMatches;
    }

    /**
     * @inheritdoc
     */
    public function calculate(Configuration $configuration, Board $board): bool
    {
        if (!$board->lastUsedField()) {
            return false;
        }

        $stone = $board->lastUsedField()->stone();
        $point = $board->lastUsedField()->point();

        // Create a string representation of fields e.g. "000121"
        $haystack = implode($board->findFieldsByRow($point->y()));
        // Create a string like "1111|2222" depending on the stone and the required matches.
        $needle = str_repeat((string)$stone->color(), $this->numberOfRequiredMatches);

        // Check whether "1111|2222" is in "000121"
        return strpos($haystack, $needle) !== false;
    }
}