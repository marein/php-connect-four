<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;
use Marein\ConnectFour\Domain\Game\Size;

class MultipleWinningStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldCalculateForWin(): void
    {
        $configuration = Configuration::custom(
            new Size(7, 6),
            new MultipleWinningStrategies([])
        );

        $board = Board::empty($configuration);

        $first = $this->createMock(WinningStrategy::class);
        $first->method('calculate')->willReturn(false);

        $second = $this->createMock(WinningStrategy::class);
        $second->method('calculate')->willReturn(false);

        $strategy = new MultipleWinningStrategies([$first, $second]);

        $this->assertFalse($strategy->calculate($configuration, $board));

        $first = $this->createMock(WinningStrategy::class);
        $first->method('calculate')->willReturn(true);

        $second = $this->createMock(WinningStrategy::class);
        $second->method('calculate')->willReturn(false);

        $strategy = new MultipleWinningStrategies([$first, $second]);

        $this->assertTrue($strategy->calculate($configuration, $board));
    }
}