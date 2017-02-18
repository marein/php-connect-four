<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\GameNotWinnableException;
use Marein\ConnectFour\Domain\Game\WinningStrategy\CommonWinningStrategy;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedWithCommonConfiguration()
    {
        $configuration = Configuration::common();

        $this->assertEquals(4, $configuration->requiredMatches()->value());
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
            new RequiredMatches(4),
            new CommonWinningStrategy()
        );

        $this->assertEquals(4, $configuration->requiredMatches()->value());
        $this->assertEquals(7, $configuration->size()->width());
        $this->assertEquals(6, $configuration->size()->height());
        $this->assertInstanceOf(CommonWinningStrategy::class, $configuration->winningStrategy());
    }

    /**
     * @test
     * @dataProvider exceptionIfNotWinnableProvider
     *
     * @param $size
     * @param $requiredMatches
     */
    public function itShouldThrowExceptionIfGameIsNotWinnable($size, $requiredMatches)
    {
        $this->expectException(GameNotWinnableException::class);

        Configuration::custom($size, $requiredMatches, new CommonWinningStrategy());
    }

    /**
     * @return array
     */
    public function exceptionIfNotWinnableProvider()
    {
        return [
            [new Size(3, 2), new RequiredMatches(4)],
            [new Size(12, 10), new RequiredMatches(13)],
            [new Size(6, 7), new RequiredMatches(8)]
        ];
    }
}