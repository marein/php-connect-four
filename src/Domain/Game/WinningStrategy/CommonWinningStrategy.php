<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;

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
            new VerticalWinningStrategy(),
            new HorizontalWinningStrategy(),
            new DiagonalWinningStrategy()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function calculate(Configuration $configuration, Board $board)
    {
        return $this->winningStrategy->calculate($configuration, $board);
    }
}