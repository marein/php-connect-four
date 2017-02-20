<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\WinningStrategy\CommonWinningStrategy;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedWithCommonConfiguration()
    {
        $configuration = Configuration::common();

        $this->assertEquals(7, $configuration->size()->width());
        $this->assertEquals(6, $configuration->size()->height());
        $this->assertInstanceOf(CommonWinningStrategy::class, $configuration->winningStrategy());
    }

    /**
     * @test
     */
    public function itShouldBeCreatedWithCustomConfiguration()
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