<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Exception\GameFinishedException;
use Marein\ConnectFour\Domain\Game\Exception\GameNotWinnableException;
use Marein\ConnectFour\Domain\Game\Exception\OutOfSizeException;
use Marein\ConnectFour\Domain\Game\Exception\NextStoneExpectedException;

class GameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedWithEmptyFields()
    {
        $game = Game::open(Configuration::custom(
            new Size(4, 4),
            new RequiredMatches(4)
        ));

        $filtered = array_filter($game->fields(), function ($field) {
            /** @var Field $field */
            return !$field->isEmpty();
        });

        $this->assertCount(0, $filtered);
    }

    /**
     * @test
     */
    public function itFieldCountShouldBeTheProductOfSize()
    {
        $game = Game::open(Configuration::common());

        $this->assertCount(42, $game->fields());
    }

    /**
     * @test
     */
    public function itShouldBeDrawWhenNoMatchIsFoundAndAllFieldsAreFilled()
    {
        $game = Game::open(Configuration::custom(
            new Size(2, 4),
            new RequiredMatches(4)
        ));

        $game->dropStone(Stone::pickUpRed(), 1);
        $game->dropStone(Stone::pickUpYellow(), 1);
        $game->dropStone(Stone::pickUpRed(), 1);
        $game->dropStone(Stone::pickUpYellow(), 1);
        $game->dropStone(Stone::pickUpRed(), 2);
        $game->dropStone(Stone::pickUpYellow(), 2);
        $game->dropStone(Stone::pickUpRed(), 2);
        $game->dropStone(Stone::pickUpYellow(), 2);

        $this->assertTrue($game->isDraw());
        $this->assertFalse($game->isWin());
    }

    /**
     * @test
     * @dataProvider winWhenMatchProvider
     *
     * @param array $moves
     */
    public function itShouldBeWinWhenMatchIsFound(array $moves)
    {
        $game = Game::open(Configuration::common());

        foreach ($moves as $move) {
            $game->dropStone($move[0], $move[1]);
        }

        $this->assertFalse($game->isDraw());
        $this->assertTrue($game->isWin());
    }

    /**
     * @return array
     */
    public function winWhenMatchProvider()
    {
        return [
            // Match in column
            [
                [
                    [Stone::pickUpRed(), 1],
                    [Stone::pickUpYellow(), 2],
                    [Stone::pickUpRed(), 1],
                    [Stone::pickUpYellow(), 2],
                    [Stone::pickUpRed(), 1],
                    [Stone::pickUpYellow(), 2],
                    [Stone::pickUpRed(), 1]
                ]
            ],
            // Match in row
            [
                [
                    [Stone::pickUpRed(), 1],
                    [Stone::pickUpYellow(), 1],
                    [Stone::pickUpRed(), 2],
                    [Stone::pickUpYellow(), 2],
                    [Stone::pickUpRed(), 3],
                    [Stone::pickUpYellow(), 3],
                    [Stone::pickUpRed(), 4]
                ]
            ],
            // Match in diagonal down
            [
                [
                    [Stone::pickUpRed(), 7],
                    [Stone::pickUpYellow(), 6],
                    [Stone::pickUpRed(), 6],
                    [Stone::pickUpYellow(), 5],
                    [Stone::pickUpRed(), 4],
                    [Stone::pickUpYellow(), 5],
                    [Stone::pickUpRed(), 5],
                    [Stone::pickUpYellow(), 4],
                    [Stone::pickUpRed(), 4],
                    [Stone::pickUpYellow(), 1],
                    [Stone::pickUpRed(), 4]
                ]
            ],
            // Match in diagonal up
            [
                [
                    [Stone::pickUpRed(), 1],
                    [Stone::pickUpYellow(), 2],
                    [Stone::pickUpRed(), 2],
                    [Stone::pickUpYellow(), 3],
                    [Stone::pickUpRed(), 3],
                    [Stone::pickUpYellow(), 4],
                    [Stone::pickUpRed(), 3],
                    [Stone::pickUpYellow(), 4],
                    [Stone::pickUpRed(), 4],
                    [Stone::pickUpYellow(), 6],
                    [Stone::pickUpRed(), 4]
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function itShouldBeWinWhenLastDropIsAMatch()
    {
        $game = Game::open(Configuration::custom(
            new Size(4, 4),
            new RequiredMatches(4)
        ));

        $game->dropStone(Stone::pickUpRed(), 2);
        $game->dropStone(Stone::pickUpYellow(), 1);
        $game->dropStone(Stone::pickUpRed(), 2);
        $game->dropStone(Stone::pickUpYellow(), 1);
        $game->dropStone(Stone::pickUpRed(), 2);
        $game->dropStone(Stone::pickUpYellow(), 1);
        $game->dropStone(Stone::pickUpRed(), 3);
        $game->dropStone(Stone::pickUpYellow(), 2);
        $game->dropStone(Stone::pickUpRed(), 3);
        $game->dropStone(Stone::pickUpYellow(), 3);
        $game->dropStone(Stone::pickUpRed(), 3);
        $game->dropStone(Stone::pickUpYellow(), 4);
        $game->dropStone(Stone::pickUpRed(), 4);
        $game->dropStone(Stone::pickUpYellow(), 4);
        $game->dropStone(Stone::pickUpRed(), 4);
        $game->dropStone(Stone::pickUpYellow(), 1);

        $this->assertFalse($game->isDraw());
        $this->assertTrue($game->isWin());
    }

    /**
     * @test
     */
    public function itShouldExpectOneStoneAfterAnother()
    {
        $this->expectException(NextStoneExpectedException::class);

        $game = Game::open(Configuration::custom(
            new Size(4, 2),
            new RequiredMatches(4)
        ));

        $game->dropStone(Stone::pickUpRed(), 1);
        $game->dropStone(Stone::pickUpRed(), 1);
    }

    /**
     * @test
     */
    public function itShouldNotBePlayableWhenDraw()
    {
        $this->expectException(GameFinishedException::class);

        $game = Game::open(Configuration::custom(
            new Size(2, 4),
            new RequiredMatches(4)
        ));

        $game->dropStone(Stone::pickUpRed(), 1);
        $game->dropStone(Stone::pickUpYellow(), 1);
        $game->dropStone(Stone::pickUpRed(), 1);
        $game->dropStone(Stone::pickUpYellow(), 1);
        $game->dropStone(Stone::pickUpRed(), 2);
        $game->dropStone(Stone::pickUpYellow(), 2);
        $game->dropStone(Stone::pickUpRed(), 2);
        $game->dropStone(Stone::pickUpYellow(), 2);
        $game->dropStone(Stone::pickUpRed(), 1);
    }

    /**
     * @test
     */
    public function itShouldNotBePlayableWhenWin()
    {
        $this->expectException(GameFinishedException::class);

        $game = Game::open(Configuration::custom(
            new Size(4, 2),
            new RequiredMatches(4)
        ));

        $game->dropStone(Stone::pickUpRed(), 1);
        $game->dropStone(Stone::pickUpYellow(), 1);
        $game->dropStone(Stone::pickUpRed(), 2);
        $game->dropStone(Stone::pickUpYellow(), 2);
        $game->dropStone(Stone::pickUpRed(), 3);
        $game->dropStone(Stone::pickUpYellow(), 3);
        $game->dropStone(Stone::pickUpRed(), 4);
        $game->dropStone(Stone::pickUpYellow(), 3);
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionIfGivenColumnIsOutOfSize()
    {
        $this->expectException(OutOfSizeException::class);

        $game = Game::open(Configuration::custom(
            new Size(4, 2),
            new RequiredMatches(4)
        ));

        $game->dropStone(Stone::pickUpRed(), 5);
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionIfColumnIsAlreadyFilled()
    {
        $this->expectException(ColumnAlreadyFilledException::class);

        $game = Game::open(Configuration::custom(
            new Size(4, 2),
            new RequiredMatches(4)
        ));

        $game->dropStone(Stone::pickUpRed(), 1);
        $game->dropStone(Stone::pickUpYellow(), 1);
        $game->dropStone(Stone::pickUpRed(), 1);
    }
}