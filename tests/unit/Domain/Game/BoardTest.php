<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Exception\OutOfSizeException;
use Marein\ConnectFour\Domain\Game\WinningStrategy\CommonWinningStrategy;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    /**
     * @test
     */
    public function aStoneCanBeDropped(): void
    {
        $configuration = Configuration::custom(
            new Size(7, 6),
            new CommonWinningStrategy()
        );
        $board = Board::empty($configuration);
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

        $configuration = Configuration::custom(
            new Size(7, 6),
            new CommonWinningStrategy()
        );
        $board = Board::empty($configuration);

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
        return Board::empty(
            Configuration::custom(
                new Size(7, 6),
                new CommonWinningStrategy()
            )
        );
    }
}