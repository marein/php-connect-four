<?php

namespace Marein\ConnectFour\Domain\Game\WinningRule;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Size;
use Marein\ConnectFour\Domain\Game\Stone;
use PHPUnit\Framework\TestCase;

class DiagonalWinningRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCalculateForWin(): void
    {
        $size = new Size(7, 6);
        $board = Board::empty($size);
        $diagonalWinningRule = new DiagonalWinningRule(4);

        $this->assertFalse($diagonalWinningRule->calculate($board));

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

        $this->assertFalse($diagonalWinningRule->calculate($board));

        $board = $board->dropStone(Stone::red(), 4);

        $this->assertTrue($diagonalWinningRule->calculate($board));

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

        $this->assertFalse($diagonalWinningRule->calculate($board));

        $board = $board->dropStone(Stone::red(), 4);

        $this->assertTrue($diagonalWinningRule->calculate($board));
    }
}