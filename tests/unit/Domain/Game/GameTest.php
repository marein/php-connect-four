<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\GameFinishedException;
use Marein\ConnectFour\Domain\Game\Exception\PlayersHaveSameStoneException;
use Marein\ConnectFour\Domain\Game\Exception\PlayersNotUniqueException;
use Marein\ConnectFour\Domain\Game\Exception\UnexpectedPlayerException;
use Marein\ConnectFour\Domain\Game\WinningRule\CommonWinningRule;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    const PLAYER1 = '12345';
    const PLAYER2 = '67890';

    /**
     * @test
     */
    public function itShouldBeDrawWhenNoMatchIsFoundAndAllFieldsAreFilled(): void
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

        $this->assertTrue($game->state()->isDrawn());
        $this->assertFalse($game->state()->isWon());
    }

    /**
     * @test
     */
    public function itShouldBeWinWhenMatchIsFound(): void
    {
        $game = $this->createCommonGame();

        $game->move(self::PLAYER1, 1);
        $game->move(self::PLAYER2, 1);
        $game->move(self::PLAYER1, 2);
        $game->move(self::PLAYER2, 2);
        $game->move(self::PLAYER1, 3);
        $game->move(self::PLAYER2, 3);
        $game->move(self::PLAYER1, 4);

        $this->assertEquals(self::PLAYER1, $game->state()->winner()->id());
        $this->assertFalse($game->state()->isDrawn());
        $this->assertTrue($game->state()->isWon());
    }

    /**
     * @test
     */
    public function itShouldBeWinWhenLastDropIsAMatch(): void
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

        $this->assertEquals(self::PLAYER2, $game->state()->winner()->id());
        $this->assertFalse($game->state()->isDrawn());
        $this->assertTrue($game->state()->isWon());
    }

    /**
     * @test
     */
    public function itShouldExpectOnePlayerAfterAnother(): void
    {
        $this->expectException(UnexpectedPlayerException::class);

        $game = $this->create4x2Game();

        $game->move(self::PLAYER1, 1);
        $game->move(self::PLAYER1, 1);
    }

    /**
     * @test
     */
    public function itShouldExpectUniquePlayers(): void
    {
        $this->expectException(PlayersNotUniqueException::class);

        Game::open(
            Configuration::common(),
            new Player(self::PLAYER1, Stone::red()),
            new Player(self::PLAYER1, Stone::yellow())
        );
    }

    /**
     * @test
     */
    public function itShouldExpectPlayerWithDifferentStones(): void
    {
        $this->expectException(PlayersHaveSameStoneException::class);

        Game::open(
            Configuration::common(),
            new Player(self::PLAYER1, Stone::red()),
            new Player(self::PLAYER2, Stone::red())
        );
    }

    /**
     * @test
     */
    public function itShouldNotBePlayableWhenDraw(): void
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
    public function itShouldNotBePlayableWhenWin(): void
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
     * @return Game
     */
    private function create2x4Game(): Game
    {
        return Game::open(
            Configuration::custom(
                new Size(2, 4),
                new CommonWinningRule()
            ),
            new Player(self::PLAYER1, Stone::red()),
            new Player(self::PLAYER2, Stone::yellow())
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
                new CommonWinningRule()
            ),
            new Player(self::PLAYER1, Stone::red()),
            new Player(self::PLAYER2, Stone::yellow())
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
                new CommonWinningRule()
            ),
            new Player(self::PLAYER1, Stone::red()),
            new Player(self::PLAYER2, Stone::yellow())
        );
    }

    /**
     * @return Game
     */
    private function createCommonGame(): Game
    {
        return Game::open(
            Configuration::common(),
            new Player(self::PLAYER1, Stone::red()),
            new Player(self::PLAYER2, Stone::yellow())
        );
    }
}