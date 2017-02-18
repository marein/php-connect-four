<?php

namespace Marein\ConnectFour\Domain\Game;

class StoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function aRedStoneCanBeCreated()
    {
        $this->assertEquals(Stone::red()->color(), Stone::RED);
    }

    /**
     * @test
     */
    public function aYellowStoneCanBeCreated()
    {
        $this->assertEquals(Stone::yellow()->color(), Stone::YELLOW);
    }
}