<?php

namespace Marein\ConnectFour\Domain\Game;

use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedEmpty(): void
    {
        $field = Field::empty(new Point(0, 1));

        $this->assertTrue($field->isEmpty());
    }

    /**
     * @test
     */
    public function aStoneCanBePlaced(): void
    {
        $field = Field::empty(new Point(0, 1));
        $fieldCopy = clone $field;

        $fieldWithStone = $field->placeStone(Stone::red());

        $this->assertFalse($fieldWithStone->isEmpty());
        $this->assertEquals(Stone::red(), $fieldWithStone->stone());
        $this->assertEquals($field, $fieldCopy);
    }
}