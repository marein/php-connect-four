<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;
use Marein\ConnectFour\Domain\Game\Size;
use Marein\ConnectFour\Domain\Game\Stone;

class DiagonalWinningStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldCalculateForWin()
    {
        $configuration = Configuration::custom(
            new Size(7, 6),
            new DiagonalWinningStrategy(4)
        );

        $board = Board::empty($configuration);

        $this->assertFalse($configuration->winningStrategy()->calculate(
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

        $this->assertFalse($configuration->winningStrategy()->calculate(
            $configuration,
            $board
        ));

        $board = $board->dropStone(Stone::red(), 4);

        $this->assertTrue($configuration->winningStrategy()->calculate(
            $configuration,
            $board
        ));

        $board = Board::empty($configuration);

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

        $this->assertFalse($configuration->winningStrategy()->calculate(
            $configuration,
            $board
        ));

        $board = $board->dropStone(Stone::red(), 4);

        $this->assertTrue($configuration->winningStrategy()->calculate(
            $configuration,
            $board
        ));
    }
}