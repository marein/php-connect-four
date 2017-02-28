<?php

namespace Marein\ConnectFour\Domain\Game;

use PHPUnit\Framework\TestCase;

class StoneTest extends TestCase
{
    /**
     * @test
     */
    public function aRedStoneCanBeCreated(): void
    {
        $this->assertEquals(Stone::red()->color(), Stone::RED);
    }

    /**
     * @test
     */
    public function aYellowStoneCanBeCreated(): void
    {
        $this->assertEquals(Stone::yellow()->color(), Stone::YELLOW);
    }

    /**
     * @test
     */
    public function aNoneStoneCanBeCreated(): void
    {
        $this->assertEquals(Stone::none()->color(), Stone::NONE);
    }
}