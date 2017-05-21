<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;

final class MultipleWinningStrategies implements WinningStrategy
{
    /**
     * @var WinningStrategy[]
     */
    private $winningStrategies;

    /**
     * MultipleWinningStrategies constructor.
     *
     * @param WinningStrategy[] $winningStrategies
     */
    public function __construct(array $winningStrategies)
    {
        $this->winningStrategies = $winningStrategies;
    }

    /**
     * @inheritdoc
     */
    public function calculate(Board $board): bool
    {
        foreach ($this->winningStrategies as $winningStrategy) {
            if ($winningStrategy->calculate($board)) {
                return true;
            }
        }

        return false;
    }
}