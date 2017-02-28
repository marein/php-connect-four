<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\WinningStrategy\CommonWinningStrategy;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedWithCommonConfiguration(): void
    {
        $configuration = Configuration::common();

        $this->assertEquals(7, $configuration->size()->width());
        $this->assertEquals(6, $configuration->size()->height());
        $this->assertInstanceOf(CommonWinningStrategy::class, $configuration->winningStrategy());
    }

    /**
     * @test
     */
    public function itShouldBeCreatedWithCustomConfiguration(): void
    {
        $configuration = Configuration::custom(
            new Size(7, 6),
            new CommonWinningStrategy()
        );

        $this->assertEquals(7, $configuration->size()->width());
        $this->assertEquals(6, $configuration->size()->height());
        $this->assertInstanceOf(CommonWinningStrategy::class, $configuration->winningStrategy());
    }
}