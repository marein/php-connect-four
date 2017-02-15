<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Exception\GameFinishedException;
use Marein\ConnectFour\Domain\Game\Exception\NextStoneExpectedException;
use Marein\ConnectFour\Domain\Game\Exception\OutOfSizeException;

class Game
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Stone
     */
    private $lastUsedStone;

    /**
     * @var int
     */
    private $dropCounter;

    /**
     * @var Stone
     */
    private $winningStone;

    /**
     * @var Board
     */
    private $board;

    /**
     * Game constructor.
     *
     * @param Configuration $configuration
     */
    private function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $this->lastUsedStone = null;
        $this->dropCounter = 0;
        $this->winningStone = null;
        $this->board = Board::empty($configuration);
    }

    /*************************************************************
     *                         Factory
     *************************************************************/

    /**
     * Opens a new [Game].
     *
     * @param Configuration $configuration
     *
     * @return Game
     */
    public static function open(Configuration $configuration)
    {
        return new self($configuration);
    }

    /*************************************************************
     *                        Behaviour
     *************************************************************/

    /**
     * Drops a [Stone] in the given column.
     *
     * @param Stone $stone
     * @param int   $column
     *
     * @throws ColumnAlreadyFilledException
     * @throws GameFinishedException
     * @throws NextStoneExpectedException
     * @throws OutOfSizeException
     */
    public function dropStone(Stone $stone, $column)
    {
        $this->guardGameFinished();
        $this->guardColumnFitsInSize($column);
        $this->guardNextStoneExpected($stone);

        $this->board = $this->board->dropStone($stone, $column);

        $this->lastUsedStone = $stone;

        $this->dropCounter++;

        $this->calculateWinningStone(
            $this->board->lastUsedField()->stone(),
            $this->board->lastUsedField()->point()
        );
    }

    /*************************************************************
     *                      Winning calculation
     *************************************************************/

    /**
     * Returns true if the given [Stone] has a match.
     *
     * @param Stone $stone
     * @param Point $point
     */
    private function calculateWinningStone(Stone $stone, Point $point)
    {
        $column = $this->checkFieldsForWin(
            $stone,
            $this->board->findFieldsByColumn($point->x())
        );

        $row = $this->checkFieldsForWin(
            $stone,
            $this->board->findFieldsByRow($point->y())
        );

        $diagonalDown = $this->checkFieldsForWin(
            $stone,
            $this->board->findFieldsByPoints(Point::createPointsInDiagonalDown($point, $this->configuration()->size()))
        );

        $diagonalUp = $this->checkFieldsForWin(
            $stone,
            $this->board->findFieldsByPoints(Point::createPointsInDiagonalUp($point, $this->configuration()->size()))
        );

        if ($column || $row || $diagonalDown || $diagonalUp) {
            $this->winningStone = $stone;
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
     * Guard if the given [Stone] is not the expected one.
     *
     * @param Stone $stone
     *
     * @throws NextStoneExpectedException
     */
    private function guardNextStoneExpected(Stone $stone)
    {
        if ($this->lastUsedStone == $stone) {
            throw new NextStoneExpectedException();
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
     * Returns the winning [Stone] if a match was found during the last drop.
     *
     * @return Stone|null
     */
    public function winningStone()
    {
        return $this->winningStone;
    }

    /**
     * Returns true if the [Game] is draw.
     *
     * @return bool
     */
    public function isDraw()
    {
        return
            !$this->winningStone &&
            $this->dropCounter == $this->configuration()->size()->width() * $this->configuration()->size()->height();
    }

    /**
     * Returns true if the [Game] is won.
     *
     * @return bool
     */
    public function isWin()
    {
        return $this->winningStone !== null;
    }
}