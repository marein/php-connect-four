<?php

namespace Marein\ConnectFour\Domain\Game\WinningRule;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Size;
use Marein\ConnectFour\Domain\Game\Stone;
use PHPUnit\Framework\TestCase;

class VerticalWinningRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCalculateForWin(): void
    {
        $size = new Size(7, 6);
        $board = Board::empty($size);
        $verticalWinningRule = new VerticalWinningRule(4);

        $this->assertFalse($verticalWinningRule->calculate($board));

        $board = $board->dropStone(Stone::red(), 1);
        $board = $board->dropStone(Stone::red(), 1);
        $board = $board->dropStone(Stone::red(), 1);

        $this->assertFalse($verticalWinningRule->calculate($board));

        $board = $board->dropStone(Stone::red(), 1);

        $this->assertTrue($verticalWinningRule->calculate($board));
    }
}