<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;
use Marein\ConnectFour\Domain\Game\Size;
use Marein\ConnectFour\Domain\Game\Stone;
use PHPUnit\Framework\TestCase;

class DiagonalWinningStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCalculateForWin(): void
    {
        $size = new Size(7, 6);
        $diagonalWinningStrategy = new DiagonalWinningStrategy(4);

        $configuration = Configuration::custom(
            $size,
            $diagonalWinningStrategy
        );

        $board = Board::empty($size);

        $this->assertFalse($diagonalWinningStrategy->calculate(
            $configuration,
            $board
        ));

        /**
         *    /
         *   /
         *  /
         * /
         */
        $board = $board->dropStone(Stone::red(), 1);
        $board = $board->dropStone(Stone::red(), 2);
        $board = $board->dropStone(Stone::red(), 2);
        $board = $board->dropStone(Stone::red(), 3);
        $board = $board->dropStone(Stone::red(), 3);
        $board = $board->dropStone(Stone::red(), 3);
        $board = $board->dropStone(Stone::red(), 4);
        $board = $board->dropStone(Stone::red(), 4);
        $board = $board->dropStone(Stone::red(), 4);

        $this->assertFalse($diagonalWinningStrategy->calculate(
            $configuration,
            $board
        ));

        $board = $board->dropStone(Stone::red(), 4);

        $this->assertTrue($diagonalWinningStrategy->calculate(
            $configuration,
            $board
        ));

        $board = Board::empty($size);

        /**
         * \
         *  \
         *   \
         *    \
         */
        $board = $board->dropStone(Stone::red(), 7);
        $board = $board->dropStone(Stone::red(), 6);
        $board = $board->dropStone(Stone::red(), 6);
        $board = $board->dropStone(Stone::red(), 5);
        $board = $board->dropStone(Stone::red(), 5);
        $board = $board->dropStone(Stone::red(), 5);
        $board = $board->dropStone(Stone::red(), 4);
        $board = $board->dropStone(Stone::red(), 4);
        $board = $board->dropStone(Stone::red(), 4);

        $this->assertFalse($diagonalWinningStrategy->calculate(
            $configuration,
            $board
        ));

        $board = $board->dropStone(Stone::red(), 4);

        $this->assertTrue($diagonalWinningStrategy->calculate(
            $configuration,
            $board
        ));
    }
}