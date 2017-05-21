<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;

final class CommonWinningStrategy implements WinningStrategy
{
    /**
     * @var WinningStrategy
     */
    private $winningStrategy;

    /**
     * CommonWinningStrategy constructor.
     */
    public function __construct()
    {
        $this->winningStrategy = new MultipleWinningStrategies([
            new VerticalWinningStrategy(4),
            new HorizontalWinningStrategy(4),
            new DiagonalWinningStrategy(4)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function calculate(Board $board): bool
    {
        return $this->winningStrategy->calculate($board);
    }
}