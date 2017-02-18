<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Exception\GameFinishedException;
use Marein\ConnectFour\Domain\Game\Exception\OutOfSizeException;
use Marein\ConnectFour\Domain\Game\Exception\PlayersHaveSameStoneException;
use Marein\ConnectFour\Domain\Game\Exception\PlayersNotUniqueException;
use Marein\ConnectFour\Domain\Game\Exception\UnexpectedPlayerException;

class Game
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var int
     */
    private $numberOfMoves;

    /**
     * @var Board
     */
    private $board;

    /**
     * @var Player[]
     */
    private $players;

    /**
     * @var Player
     */
    private $winner;

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

        $this->configuration = $configuration;
        $this->numberOfMoves = 0;
        $this->board = Board::empty($configuration);
        $this->players = [$player1, $player2];
        $this->winner = null;
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
     */
    public static function open(Configuration $configuration, Player $player1, Player $player2)
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
    public function move($playerId, $column)
    {
        $this->guardGameFinished();
        $this->guardColumnFitsInSize($column);
        $this->guardExpectedPlayer($playerId);

        $this->board = $this->board->dropStone($this->currentPlayer()->stone(), $column);

        $this->calculateWinner(
            $this->currentPlayer(),
            $this->board->lastUsedField()->point()
        );

        $this->numberOfMoves++;
        $this->switchPlayer();
    }

    /**
     * Switch the [Player]s.
     */
    private function switchPlayer()
    {
        $this->players = array_reverse($this->players);
    }

    /*************************************************************
     *                    Winner calculation
     *************************************************************/

    /**
     * Returns true if the given [Player] has a match.
     *
     * @todo This class should not be responsible to do this.
     *       Make interface WinningStrategy with following concrete implementations:
     *       DiagonalWinningStrategy, VerticalWinningStrategy, HorizontalWinningStrategy, AllWinningStrategy.
     *       The WinningStrategy can be set via [Configuration].
     *
     * @param Player $player
     * @param Point  $point
     */
    private function calculateWinner(Player $player, Point $point)
    {
        $column = $this->checkFieldsForWin(
            $player->stone(),
            $this->board->findFieldsByColumn($point->x())
        );

        $row = $this->checkFieldsForWin(
            $player->stone(),
            $this->board->findFieldsByRow($point->y())
        );

        $diagonalDown = $this->checkFieldsForWin(
            $player->stone(),
            $this->board->findFieldsByPoints(Point::createPointsInDiagonalDown($point, $this->configuration()->size()))
        );

        $diagonalUp = $this->checkFieldsForWin(
            $player->stone(),
            $this->board->findFieldsByPoints(Point::createPointsInDiagonalUp($point, $this->configuration()->size()))
        );

        if ($column || $row || $diagonalDown || $diagonalUp) {
            $this->winner = $player;
        }
    }

    /**
     * Check if the given [Field]s have a match.
     *
     * @param Stone   $stone
     * @param Field[] $fields
     *
     * @return bool
     */
    private function checkFieldsForWin(Stone $stone, array $fields)
    {
        return strpos(
                implode($fields),
                str_repeat($stone->color(), $this->configuration->requiredMatches()->value())
            ) !== false;
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
    private function guardPlayersAreUnique(Player $player1, Player $player2)
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
    private function guardPlayersHaveDifferentStones(Player $player1, Player $player2)
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
    private function guardExpectedPlayer(string $playerId)
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
    private function guardGameFinished()
    {
        if ($this->isDraw() || $this->isWin()) {
            throw new GameFinishedException();
        }
    }

    /**
     * Guard that column fits in [Size].
     *
     * @param int $column
     *
     * @throws OutOfSizeException
     */
    private function guardColumnFitsInSize($column)
    {
        if ($column > $this->configuration()->size()->width()) {
            throw new OutOfSizeException();
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
    private function currentPlayer()
    {
        return $this->players[0];
    }

    /**
     * Returns the [Field]s of the [Game].
     *
     * @return Field[]
     */
    public function fields()
    {
        return $this->board->fields();
    }

    /**
     * Returns the [Configuration] of the [Game].
     *
     * @return Configuration
     */
    public function configuration()
    {
        return $this->configuration;
    }

    /**
     * Returns the winner if a match was found during the last move.
     *
     * @return Player|null
     */
    public function winner()
    {
        return $this->winner;
    }

    /**
     * Returns true if the [Game] is draw.
     *
     * @return bool
     */
    public function isDraw()
    {
        return
            !$this->winner &&
            $this->numberOfMoves == $this->configuration()->size()->width() * $this->configuration()->size()->height();
    }

    /**
     * Returns true if the [Game] is won.
     *
     * @return bool
     */
    public function isWin()
    {
        return $this->winner !== null;
    }
}