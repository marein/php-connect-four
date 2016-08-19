<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Exception\GameFinishedException;
use Marein\ConnectFour\Domain\Game\Exception\GameNotWinnableException;
use Marein\ConnectFour\Domain\Game\Exception\NextStoneExpectedException;
use Marein\ConnectFour\Domain\Game\Exception\OutOfSizeException;

class Game
{
    /**
     * @var Field[]
     */
    private $fields;

    /**
     * @var Size
     */
    private $size;

    /**
     * @var int
     */
    private $requiredMatches;

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
     * Game constructor.
     *
     * @param Field[] $fields
     * @param Size    $size
     * @param int     $requiredMatches
     *
     * @throws GameNotWinnableException
     */
    private function __construct(array $fields, Size $size, $requiredMatches)
    {
        $this->fields = $fields;
        $this->size = $size;
        $this->requiredMatches = $requiredMatches;
        $this->lastUsedStone = null;
        $this->dropCounter = 0;
        $this->winningStone = null;

        $this->guardGameIsWinnable();
    }

    /*************************************************************
     *                         Factory
     *************************************************************/

    /**
     * Create a [Game] with empty [Field]s.
     *
     * @param Size $size
     * @param int  $requiredMatches
     *
     * @return Game
     */
    public static function createEmpty(Size $size, $requiredMatches)
    {
        $fields = [];

        for ($y = 0; $y < $size->height(); $y++) {
            for ($x = 0; $x < $size->width(); $x++) {
                $fields[] = Field::createEmpty(new Point($x + 1, $y + 1));
            }
        }

        return new self($fields, $size, $requiredMatches);
    }

    /*************************************************************
     *                        Behaviour
     *************************************************************/

    /**
     * Drops a [Stone] in a specified column.
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

        $field = $this->findFirstEmptyFieldInColumn($column);

        $field->placeStone($stone);

        $this->lastUsedStone = $stone;

        $this->dropCounter++;

        $this->calculateWinningStone($field->stone(), $field->point());
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
            $this->findFieldsByColumn($point->x())
        );

        $row = $this->checkFieldsForWin(
            $stone,
            $this->findFieldsByRow($point->y())
        );

        $diagonalDown = $this->checkFieldsForWin(
            $stone,
            $this->findFieldsByPoints(Point::createPointsInDiagonalDown($point, $this->size()))
        );

        $diagonalUp = $this->checkFieldsForWin(
            $stone,
            $this->findFieldsByPoints(Point::createPointsInDiagonalUp($point, $this->size()))
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
            str_repeat($stone->color(), $this->requiredMatches)
        ) !== false;
    }

    /*************************************************************
     *                          Guards
     *************************************************************/

    /**
     * Guard that [Game] is winnable.
     *
     * @throws GameNotWinnableException
     */
    private function guardGameIsWinnable()
    {
        if ($this->requiredMatches > $this->size()->height() && $this->requiredMatches > $this->size()->width()) {
            throw new GameNotWinnableException();
        }
    }

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
        if ($column > $this->size()->width()) {
            throw new OutOfSizeException();
        }
    }

    /*************************************************************
     *                     Finder for [Field]
     *************************************************************/

    /**
     * Find first empty field [Field] in column.
     *
     * @param int $column
     *
     * @return Field
     * @throws ColumnAlreadyFilledException
     */
    private function findFirstEmptyFieldInColumn($column)
    {
        /** @var Field $field */
        foreach (array_reverse($this->findFieldsByColumn($column)) as $field) {
            if ($field->isEmpty()) {
                return $field;
            }
        }

        throw new ColumnAlreadyFilledException();
    }

    /**
     * Find [Field]s by column.
     *
     * @param int $column
     *
     * @return Field[]
     */
    private function findFieldsByColumn($column)
    {
        return array_filter($this->fields, function (Field $field) use ($column) {
            return $field->point()->x() == $column;
        });
    }

    /**
     * Find [Field]s by row.
     *
     * @param int $row
     *
     * @return Field[]
     */
    private function findFieldsByRow($row)
    {
        return array_filter($this->fields, function (Field $field) use ($row) {
            return $field->point()->y() == $row;
        });
    }

    /**
     * Find [Field]s by [Point]s.
     *
     * @param Point[] $points
     *
     * @return Field[]
     */
    private function findFieldsByPoints(array $points)
    {
        return array_filter($this->fields, function (Field $field) use ($points) {
            return in_array($field->point(), $points);
        });
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
        // May Field is just a Value Object? Currently, it's modelled as an Entity.
        // That's why, we return new Field[] to avoid calls like
        // "$game->fields()[20]->placeStone(Stone::pickupRed());"
        // which breaks invariants.
        return array_map(function ($field) {
            return clone $field;
        }, $this->fields);
    }

    /**
     * Returns the current [Size] of the [Game].
     *
     * @return Size
     */
    public function size()
    {
        return $this->size;
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
        return !$this->winningStone && $this->dropCounter == $this->size()->width() * $this->size()->height();
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