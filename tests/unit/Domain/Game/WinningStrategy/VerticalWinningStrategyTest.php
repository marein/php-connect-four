<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
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
        $board = Board::empty($size);
        $verticalWinningStrategy = new VerticalWinningStrategy(4);

        $this->assertFalse($verticalWinningStrategy->calculate($board));

        $board = $board->dropStone(Stone::red(), 1);
        $board = $board->dropStone(Stone::red(), 1);
        $board = $board->dropStone(Stone::red(), 1);

        $this->assertFalse($verticalWinningStrategy->calculate($board));

        $board = $board->dropStone(Stone::red(), 1);

        $this->assertTrue($verticalWinningStrategy->calculate($board));
    }
}