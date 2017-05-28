<?php

namespace Marein\ConnectFour\Domain\Game\WinningRule;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Size;
use Marein\ConnectFour\Domain\Game\Stone;
use PHPUnit\Framework\TestCase;

class HorizontalWinningRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCalculateForWin(): void
    {
        $size = new Size(7, 6);
        $board = Board::empty($size);
        $horizontalWinningRule = new HorizontalWinningRule(4);

        $this->assertFalse($horizontalWinningRule->calculate($board));

        $board = $board->dropStone(Stone::red(), 1);
        $board = $board->dropStone(Stone::red(), 2);
        $board = $board->dropStone(Stone::red(), 3);

        $this->assertFalse($horizontalWinningRule->calculate($board));

        $board = $board->dropStone(Stone::red(), 4);

        $this->assertTrue($horizontalWinningRule->calculate($board));
    }
}