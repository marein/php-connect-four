<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Exception\OutOfSizeException;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedWithEmptyFields(): void
    {
        $board = $this->createBoard();

        $filtered = array_filter($board->fields(), function ($field) {
            /** @var Field $field */
            return !$field->isEmpty();
        });

        $this->assertCount(0, $filtered);
    }

    /**
     * @test
     */
    public function itFieldCountShouldBeTheProductOfSize(): void
    {
        $board = $this->createBoard();

        $this->assertCount(42, $board->fields());
    }

    /**
     * @test
     */
    public function aStoneCanBeDropped(): void
    {
        $board = $this->createBoard();
        $boardCopy = clone $board;

        $boardWithStone = $board->dropStone(Stone::red(), 1);
        $expectedPoint = new Point(1, 6);
        $fields = $boardWithStone->findFieldsByPoints([$expectedPoint]);
        $affectedField = array_pop($fields);
        $this->assertEquals(Stone::red(), $affectedField->stone());

        $boardWithStone = $boardWithStone->dropStone(Stone::yellow(), 1);
        $expectedPoint = new Point(1, 5);
        $fields = $boardWithStone->findFieldsByPoints([$expectedPoint]);
        $affectedField = array_pop($fields);
        $this->assertEquals(Stone::yellow(), $affectedField->stone());

        $this->assertEquals($board, $boardCopy);
    }

    /**
     * @test
     */
    public function aStoneCanNotBeDroppedWhenColumnAlreadyFilled(): void
    {
        $this->expectException(ColumnAlreadyFilledException::class);

        $board = $this->createBoard();

        $boardWithStone = $board->dropStone(Stone::red(), 1);
        $boardWithStone = $boardWithStone->dropStone(Stone::yellow(), 1);
        $boardWithStone = $boardWithStone->dropStone(Stone::red(), 1);
        $boardWithStone = $boardWithStone->dropStone(Stone::yellow(), 1);
        $boardWithStone = $boardWithStone->dropStone(Stone::red(), 1);
        $boardWithStone = $boardWithStone->dropStone(Stone::red(), 1);
        $boardWithStone->dropStone(Stone::yellow(), 1);
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionIfGivenColumnIsOutOfSize(): void
    {
        $this->expectException(OutOfSizeException::class);

        $board = $this->createBoard();

        $board->dropStone(Stone::red(), 8);
    }

    /**
     * @return Board
     */
    private function createBoard(): Board
    {
        $size = new Size(7, 6);

        return Board::empty($size);
    }
}