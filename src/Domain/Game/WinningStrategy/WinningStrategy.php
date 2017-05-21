<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;

interface WinningStrategy
{
    /**
     * Returns true if the strategy finds a match.
     *
     * @param Board $board
     *
     * @return bool
     */
    public function calculate(Board $board): bool;
}