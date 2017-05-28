<?php

namespace Marein\ConnectFour\Domain\Game\WinningRule;

use Marein\ConnectFour\Domain\Game\Board;

interface WinningRule
{
    /**
     * Returns true if the rule applies.
     *
     * @param Board $board
     *
     * @return bool
     */
    public function calculate(Board $board): bool;
}