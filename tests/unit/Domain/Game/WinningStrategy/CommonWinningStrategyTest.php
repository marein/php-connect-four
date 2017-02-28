<?php

namespace Marein\ConnectFour\Domain\Game\WinningStrategy;

use PHPUnit\Framework\TestCase;

class CommonWinningStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldHaveTheRightStrategies(): void
    {
        // Test for the right strategies directly at property level.
        $commonReflectionProperty = new \ReflectionProperty(CommonWinningStrategy::class, 'winningStrategy');
        $commonReflectionProperty->setAccessible(true);
        $multipleReflectionProperty = new \ReflectionProperty(MultipleWinningStrategies::class, 'winningStrategies');
        $multipleReflectionProperty->setAccessible(true);

        $common = new CommonWinningStrategy();

        $multiple = $commonReflectionProperty->getValue($common);

        $this->assertInstanceOf(MultipleWinningStrategies::class, $multiple);

        $strategies = $multipleReflectionProperty->getValue($multiple);

        $this->assertCount(3, $strategies);
        $this->assertInstanceOf(VerticalWinningStrategy::class, $strategies[0]);
        $this->assertInstanceOf(HorizontalWinningStrategy::class, $strategies[1]);
        $this->assertInstanceOf(DiagonalWinningStrategy::class, $strategies[2]);
    }
}