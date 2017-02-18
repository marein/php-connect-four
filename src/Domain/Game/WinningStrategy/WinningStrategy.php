<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;

interface WinningStrategy
{
    /**
     * Returns true if the strategy finds a match.
     *
     * @param Configuration $configuration
     * @param Board         $board
     *
     * @return bool
     */
    public function calculate(Configuration $configuration, Board $board);
}