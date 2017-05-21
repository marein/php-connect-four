<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
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
        $board = Board::empty($size);
        $horizontalWinningStrategy = new HorizontalWinningStrategy(4);

        $this->assertFalse($horizontalWinningStrategy->calculate($board));

        $board = $board->dropStone(Stone::red(), 1);
        $board = $board->dropStone(Stone::red(), 2);
        $board = $board->dropStone(Stone::red(), 3);

        $this->assertFalse($horizontalWinningStrategy->calculate($board));

        $board = $board->dropStone(Stone::red(), 4);

        $this->assertTrue($horizontalWinningStrategy->calculate($board));
    }
}