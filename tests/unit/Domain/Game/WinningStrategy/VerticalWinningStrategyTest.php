<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;
use Marein\ConnectFour\Domain\Game\Size;
use Marein\ConnectFour\Domain\Game\Stone;
use PHPUnit\Framework\TestCase;

class VerticalWinningStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCalculateForWin(): void
    {
        $size = new Size(7, 6);
        $verticalWinningStrategy = new VerticalWinningStrategy(4);

        $configuration = Configuration::custom(
            $size,
            $verticalWinningStrategy
        );

        $board = Board::empty($size);

        $this->assertFalse($verticalWinningStrategy->calculate(
            $configuration,
            $board
        ));

        $board = $board->dropStone(Stone::red(), 1);
        $board = $board->dropStone(Stone::red(), 1);
        $board = $board->dropStone(Stone::red(), 1);

        $this->assertFalse($verticalWinningStrategy->calculate(
            $configuration,
            $board
        ));

        $board = $board->dropStone(Stone::red(), 1);

        $this->assertTrue($verticalWinningStrategy->calculate(
            $configuration,
            $board
        ));
    }
}