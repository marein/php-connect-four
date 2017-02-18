<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;

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
    public function calculate(Configuration $configuration, Board $board)
    {
        foreach ($this->winningStrategies as $winningStrategy) {
            if ($winningStrategy->calculate($configuration, $board)) {
                return true;
            }
        }

        return false;
    }
}