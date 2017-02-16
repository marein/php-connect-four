<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;

final class Board
{
    /**
     * @var Field[]
     */
    private $fields;

    /**
     * @var Field
     */
    private $lastUsedField;

    /**
     * Board constructor.
     *
     * @param Field[]    $fields
     * @param Field|null $lastUsedField
     */
    private function __construct(array $fields, Field $lastUsedField = null)
    {
        $this->fields = $fields;
        $this->lastUsedField = $lastUsedField;
    }

    /*************************************************************
     *                         Factory
     *************************************************************/

    /**
     * Create an empty [Board].
     *
     * @param Configuration $configuration
     *
     * @return Board
     */
    public static function empty(Configuration $configuration)
    {
        $fields = [];

        for ($y = 0; $y < $configuration->size()->height(); $y++) {
            for ($x = 0; $x < $configuration->size()->width(); $x++) {
                $fields[] = Field::empty(new Point($x + 1, $y + 1));
            }
        }

        return new self($fields);
    }

    /*************************************************************
     *                        Behaviour
     *************************************************************/

    /**
     * Drops a [Stone] in the given column.
     *
     * @param Stone $stone
     * @param       $column
     *
     * @return Board
     */
    public function dropStone(Stone $stone, $column)
    {
        $firstEmptyFieldPosition = $this->findPositionOfFirstEmptyFieldInColumn($column);

        $fields = $this->fields;

        $field = &$fields[$firstEmptyFieldPosition];
        $field = $field->placeStone($stone);

        return new self($fields, $field);
    }

    /*************************************************************
     *                     Finder for [Field]
     *************************************************************/

    /**
     * Find first empty field [Field] in column.
     *
     * @param int $column
     *
     * @return integer
     * @throws ColumnAlreadyFilledException
     */
    private function findPositionOfFirstEmptyFieldInColumn($column)
    {
        /** @var Field[] $reversed */
        $reversedFields = array_reverse($this->findFieldsByColumn($column), true);

        foreach ($reversedFields as $position => $field) {
            if ($field->isEmpty()) {
                return $position;
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
    public function findFieldsByColumn($column)
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
    public function findFieldsByRow($row)
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
    public function findFieldsByPoints(array $points)
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
    public function fields(): array
    {
        return $this->fields;
    }

    /**
     * Returns the last used [Field].
     *
     * @return Field
     */
    public function lastUsedField(): Field
    {
        return $this->lastUsedField;
    }
}