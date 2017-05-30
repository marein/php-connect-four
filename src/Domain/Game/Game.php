<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Exception\GameFinishedException;
use Marein\ConnectFour\Domain\Game\Exception\OutOfSizeException;
use Marein\ConnectFour\Domain\Game\Exception\PlayersHaveSameStoneException;
use Marein\ConnectFour\Domain\Game\Exception\PlayersNotUniqueException;
use Marein\ConnectFour\Domain\Game\Exception\UnexpectedPlayerException;
use Marein\ConnectFour\Domain\Game\WinningRule\WinningRule;

class Game
{
    /**
     * @var WinningRule
     */
    private $winningRule;

    /**
     * @var int
     */
    private $numberOfMovesUntilDraw;

    /**
     * @var Board
     */
    private $board;

    /**
     * @var Player[]
     */
    private $players;

    /**
     * @var State
     */
    private $state;

    /**
     * Game constructor.
     *
     * @param Configuration $configuration
     * @param Player        $player1
     * @param Player        $player2
     *
     * @throws PlayersNotUniqueException
     * @throws PlayersHaveSameStoneException
     */
    private function __construct(Configuration $configuration, Player $player1, Player $player2)
    {
        $this->guardPlayersAreUnique($player1, $player2);
        $this->guardPlayersHaveDifferentStones($player1, $player2);

        $size = $configuration->size();
        $width = $size->width();
        $height = $size->height();

        $this->winningRule = $configuration->winningRule();
        $this->numberOfMovesUntilDraw = $width * $height;
        $this->board = Board::empty($size);
        $this->players = [$player1, $player2];
        $this->state = State::running();
    }

    /*************************************************************
     *                         Factory
     *************************************************************/

    /**
     * Opens a new [Game].
     *
     * @param Configuration $configuration
     * @param Player        $player1
     * @param Player        $player2
     *
     * @return Game
     * @throws PlayersNotUniqueException
     * @throws PlayersHaveSameStoneException
     */
    public static function open(Configuration $configuration, Player $player1, Player $player2): Game
    {
        return new self($configuration, $player1, $player2);
    }

    /*************************************************************
     *                        Behaviour
     *************************************************************/

    /**
     * The given player makes the move in the given column.
     *
     * @param string $playerId
     * @param int    $column
     *
     * @throws ColumnAlreadyFilledException
     * @throws GameFinishedException
     * @throws UnexpectedPlayerException
     * @throws OutOfSizeException
     */
    public function move(string $playerId, int $column): void
    {
        $this->guardGameFinished();
        $this->guardExpectedPlayer($playerId);

        $this->board = $this->board->dropStone($this->currentPlayer()->stone(), $column);

        $isWin = $this->winningRule->calculate($this->board);

        $this->numberOfMovesUntilDraw--;

        if ($isWin) {
            $this->state = State::won($this->currentPlayer());
        } else if ($this->numberOfMovesUntilDraw == 0) {
            $this->state = State::drawn();
        }

        $this->switchPlayer();
    }

    /**
     * Switch the [Player]s.
     */
    private function switchPlayer(): void
    {
        $this->players = array_reverse($this->players);
    }

    /*************************************************************
     *                          Guards
     *************************************************************/

    /**
     * Guard if the [Player]s are unique.
     *
     * @param Player $player1
     * @param Player $player2
     *
     * @throws PlayersNotUniqueException
     */
    private function guardPlayersAreUnique(Player $player1, Player $player2): void
    {
        if ($player1->id() == $player2->id()) {
            throw new PlayersNotUniqueException();
        }
    }

    /**
     * Guard if the [Player]s have different stones.
     *
     * @param Player $player1
     * @param Player $player2
     *
     * @throws PlayersHaveSameStoneException
     */
    private function guardPlayersHaveDifferentStones(Player $player1, Player $player2): void
    {
        if ($player1->stone() == $player2->stone()) {
            throw new PlayersHaveSameStoneException();
        }
    }

    /**
     * Guard if the given player id is the expected one.
     *
     * @param string $playerId
     *
     * @throws UnexpectedPlayerException
     */
    private function guardExpectedPlayer(string $playerId): void
    {
        if ($this->currentPlayer()->id() !== $playerId) {
            throw new UnexpectedPlayerException();
        }
    }

    /**
     * Guard if the [Game] is finished.
     *
     * @throws GameFinishedException
     */
    private function guardGameFinished(): void
    {
        if (!$this->state->isRunning()) {
            throw new GameFinishedException();
        }
    }

    /*************************************************************
     *                          Getter
     *************************************************************/

    /**
     * Returns the [Player].
     *
     * @return Player
     */
    private function currentPlayer(): Player
    {
        return $this->players[0];
    }

    /**
     * Returns the [Board] of the [Game].
     *
     * @return Board
     */
    public function board(): Board
    {
        return $this->board;
    }

    /**
     * Returns the winner if a match was found during the last move.
     *
     * @return Player|null
     */
    public function winner(): ?Player
    {
        return $this->state->winner();
    }

    /**
     * Returns true if the [Game] is draw.
     *
     * @return bool
     */
    public function isDraw(): bool
    {
        return $this->state->isDrawn();
    }

    /**
     * Returns true if the [Game] is won.
     *
     * @return bool
     */
    public function isWin(): bool
    {
        return $this->state->isWon();
    }
}