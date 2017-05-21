<?php

namespace Marein\ConnectFour\Domain\Game;

use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedWithItsValues(): void
    {
        $point = new Point(3, 4);

        $this->assertEquals($point->x(), 3);
        $this->assertEquals($point->y(), 4);
    }
}