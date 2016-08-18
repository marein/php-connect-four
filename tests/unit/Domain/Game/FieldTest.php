<?php

namespace Marein\ConnectFour\Domain\Game;

class FieldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedEmpty()
    {
        $field = Field::createEmpty(new Point(0, 1));

        $this->assertTrue($field->isEmpty());
        $this->assertNull($field->stone());
    }

    /**
     * @test
     */
    public function aStoneCanBePlaced()
    {
        $field = Field::createEmpty(new Point(0, 1));

        $field->placeStone(Stone::pickUpRed());

        $this->assertFalse($field->isEmpty());
        $this->assertEquals(Stone::pickUpRed(), $field->stone());
    }
}