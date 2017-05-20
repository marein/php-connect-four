<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use Marein\ConnectFour\Domain\Game\Board;
use Marein\ConnectFour\Domain\Game\Configuration;
use Marein\ConnectFour\Domain\Game\Size;
use PHPUnit\Framework\TestCase;

class MultipleWinningStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldCalculateForWin(): void
    {
        $size = new Size(7, 6);
        $multipleWinningStrategy = new MultipleWinningStrategies([]);

        $configuration = Configuration::custom(
            $size,
            $multipleWinningStrategy
        );

        $board = Board::empty($size);

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