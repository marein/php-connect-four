<?php

namespace Marein\ConnectFour\Domain\Game\WinningRule;

use Marein\ConnectFour\Domain\Game\Board;

final class MultipleWinningRule implements WinningRule
{
    /**
     * @var WinningRule[]
     */
    private $winningRules;

    /**
     * MultipleWinningRule constructor.
     *
     * @param WinningRule[] $winningRules
     */
    public function __construct(array $winningRules)
    {
        $this->winningRules = $winningRules;
    }

    /**
     * @inheritdoc
     */
    public function calculate(Board $board): bool
    {
        foreach ($this->winningRules as $winningRule) {
            if ($winningRule->calculate($board)) {
                return true;
            }
        }

        return false;
    }
}