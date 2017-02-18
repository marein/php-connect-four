<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Exception\GameFinishedException;
use Marein\ConnectFour\Domain\Game\Exception\PlayersHaveSameStoneException;
use Marein\ConnectFour\Domain\Game\Exception\PlayersNotUniqueException;
use Marein\ConnectFour\Domain\Game\Exception\UnexpectedPlayerException;
use Marein\ConnectFour\Domain\Game\Exception\OutOfSizeException;

class GameTest extends \PHPUnit_Framework_TestCase
{
    const PLAYER1 = '12345';
    const PLAYER2 = '67890';

    /**
     * @test
     */
    public function itShouldBeCreatedWithEmptyFields()
    {
        $game = $this->create4x4Game();

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
        $game = $this->createCommonGame();

        $this->assertCount(42, $game->fields());
    }

    /**
     * @test
     */
    public function itShouldBeDrawWhenNoMatchIsFoundAndAllFieldsAreFilled()
    {
        $game = $this->create2x4Game();

        $game->move(self::PLAYER1, 1);
        $game->move(self::PLAYER2, 1);
        $game->move(self::PLAYER1, 1);
        $game->move(self::PLAYER2, 1);
        $game->move(self::PLAYER1, 2);
        $game->move(self::PLAYER2, 2);
        $game->move(self::PLAYER1, 2);
        $game->move(self::PLAYER2, 2);

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
        $game = $this->createCommonGame();

        foreach ($moves as $move) {
            $game->move($move[0], $move[1]);
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
                    [self::PLAYER1, 1],
                    [self::PLAYER2, 2],
                    [self::PLAYER1, 1],
                    [self::PLAYER2, 2],
                    [self::PLAYER1, 1],
                    [self::PLAYER2, 2],
                    [self::PLAYER1, 1]
                ]
            ],
            // Match in row
            [
                [
                    [self::PLAYER1, 1],
                    [self::PLAYER2, 1],
                    [self::PLAYER1, 2],
                    [self::PLAYER2, 2],
                    [self::PLAYER1, 3],
                    [self::PLAYER2, 3],
                    [self::PLAYER1, 4]
                ]
            ],
            // Match in diagonal down
            [
                [
                    [self::PLAYER1, 7],
                    [self::PLAYER2, 6],
                    [self::PLAYER1, 6],
                    [self::PLAYER2, 5],
                    [self::PLAYER1, 4],
                    [self::PLAYER2, 5],
                    [self::PLAYER1, 5],
                    [self::PLAYER2, 4],
                    [self::PLAYER1, 4],
                    [self::PLAYER2, 1],
                    [self::PLAYER1, 4]
                ]
            ],
            // Match in diagonal up
            [
                [
                    [self::PLAYER1, 1],
                    [self::PLAYER2, 2],
                    [self::PLAYER1, 2],
                    [self::PLAYER2, 3],
                    [self::PLAYER1, 3],
                    [self::PLAYER2, 4],
                    [self::PLAYER1, 3],
                    [self::PLAYER2, 4],
                    [self::PLAYER1, 4],
                    [self::PLAYER2, 6],
                    [self::PLAYER1, 4]
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function itShouldBeWinWhenLastDropIsAMatch()
    {
        $game = $this->create4x4Game();

        $game->move(self::PLAYER1, 2);
        $game->move(self::PLAYER2, 1);
        $game->move(self::PLAYER1, 2);
        $game->move(self::PLAYER2, 1);
        $game->move(self::PLAYER1, 2);
        $game->move(self::PLAYER2, 1);
        $game->move(self::PLAYER1, 3);
        $game->move(self::PLAYER2, 2);
        $game->move(self::PLAYER1, 3);
        $game->move(self::PLAYER2, 3);
        $game->move(self::PLAYER1, 3);
        $game->move(self::PLAYER2, 4);
        $game->move(self::PLAYER1, 4);
        $game->move(self::PLAYER2, 4);
        $game->move(self::PLAYER1, 4);
        $game->move(self::PLAYER2, 1);

        $this->assertFalse($game->isDraw());
        $this->assertTrue($game->isWin());
    }

    /**
     * @test
     */
    public function itShouldExpectOnePlayerAfterAnother()
    {
        $this->expectException(UnexpectedPlayerException::class);

        $game = $this->create4x2Game();

        $game->move(self::PLAYER1, 1);
        $game->move(self::PLAYER1, 1);
    }

    /**
     * @test
     */
    public function itShouldExpectUniquePlayers()
    {
        $this->expectException(PlayersNotUniqueException::class);

        Game::open(
            Configuration::common(),
            new Player(self::PLAYER1, Stone::pickUpRed()),
            new Player(self::PLAYER1, Stone::pickUpYellow())
        );
    }

    /**
     * @test
     */
    public function itShouldExpectPlayerWithDifferentStones()
    {
        $this->expectException(PlayersHaveSameStoneException::class);

        Game::open(
            Configuration::common(),
            new Player(self::PLAYER1, Stone::pickUpRed()),
            new Player(self::PLAYER2, Stone::pickUpRed())
        );
    }

    /**
     * @test
     */
    public function itShouldNotBePlayableWhenDraw()
    {
        $this->expectException(GameFinishedException::class);

        $game = $this->create2x4Game();

        $game->move(self::PLAYER1, 1);
        $game->move(self::PLAYER2, 1);
        $game->move(self::PLAYER1, 1);
        $game->move(self::PLAYER2, 1);
        $game->move(self::PLAYER1, 2);
        $game->move(self::PLAYER2, 2);
        $game->move(self::PLAYER1, 2);
        $game->move(self::PLAYER2, 2);
        $game->move(self::PLAYER1, 1);
    }

    /**
     * @test
     */
    public function itShouldNotBePlayableWhenWin()
    {
        $this->expectException(GameFinishedException::class);

        $game = $this->create4x2Game();

        $game->move(self::PLAYER1, 1);
        $game->move(self::PLAYER2, 1);
        $game->move(self::PLAYER1, 2);
        $game->move(self::PLAYER2, 2);
        $game->move(self::PLAYER1, 3);
        $game->move(self::PLAYER2, 3);
        $game->move(self::PLAYER1, 4);
        $game->move(self::PLAYER2, 3);
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionIfGivenColumnIsOutOfSize()
    {
        $this->expectException(OutOfSizeException::class);

        $game = $this->create4x2Game();

        $game->move(self::PLAYER1, 5);
    }

    /**
     * @test
     */
    public function itShouldThrowExceptionIfColumnIsAlreadyFilled()
    {
        $this->expectException(ColumnAlreadyFilledException::class);

        $game = $this->create4x2Game();

        $game->move(self::PLAYER1, 1);
        $game->move(self::PLAYER2, 1);
        $game->move(self::PLAYER1, 1);
    }

    /**
     * @return Game
     */
    private function create2x4Game(): Game
    {
        return Game::open(
            Configuration::custom(
                new Size(2, 4),
                new RequiredMatches(4)
            ),
            new Player(self::PLAYER1, Stone::pickUpRed()),
            new Player(self::PLAYER2, Stone::pickUpYellow())
        );
    }

    /**
     * @return Game
     */
    private function create4x2Game(): Game
    {
        return Game::open(
            Configuration::custom(
                new Size(4, 2),
                new RequiredMatches(4)
            ),
            new Player(self::PLAYER1, Stone::pickUpRed()),
            new Player(self::PLAYER2, Stone::pickUpYellow())
        );
    }

    /**
     * @return Game
     */
    private function create4x4Game(): Game
    {
        return Game::open(
            Configuration::custom(
                new Size(4, 4),
                new RequiredMatches(4)
            ),
            new Player(self::PLAYER1, Stone::pickUpRed()),
            new Player(self::PLAYER2, Stone::pickUpYellow())
        );
    }

    /**
     * @return Game
     */
    private function createCommonGame(): Game
    {
        return Game::open(
            Configuration::common(),
            new Player(self::PLAYER1, Stone::pickUpRed()),
            new Player(self::PLAYER2, Stone::pickUpYellow())
        );
    }
}