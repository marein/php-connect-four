<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;
use Marein\ConnectFour\Domain\Game\Size;
use Marein\ConnectFour\Domain\Game\Stone;
use PHPUnit\Framework\TestCase;

class HorizontalWinningStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCalculateForWin(): void
    {
        $size = new Size(7, 6);
        $horizontalWinningStrategy = new HorizontalWinningStrategy(4);

        $configuration = Configuration::custom(
            $size,
            $horizontalWinningStrategy
        );

        $board = Board::empty($size);

        $this->assertFalse($horizontalWinningStrategy->calculate(
            $configuration,
            $board
        ));

        $board = $board->dropStone(Stone::red(), 1);
        $board = $board->dropStone(Stone::red(), 2);
        $board = $board->dropStone(Stone::red(), 3);

        $this->assertFalse($horizontalWinningStrategy->calculate(
            $configuration,
            $board
        ));

        $board = $board->dropStone(Stone::red(), 4);

        $this->assertTrue($horizontalWinningStrategy->calculate(
            $configuration,
            $board
        ));
    }
}