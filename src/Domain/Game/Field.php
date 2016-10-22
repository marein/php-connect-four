<?php

namespace Marein\ConnectFour\Domain\Game;

class Field
{
    /**
     * @var Point
     */
    private $point;

    /**
     * @var Stone
     */
    private $stone;

    /**
     * Field constructor.
     *
     * @param Point $point
     */
    private function __construct(Point $point)
    {
        $this->point = $point;
        $this->stone = null;
    }

    /**
     * Create an empty [Field].
     *
     * @param Point $point
     *
     * @return Field
     */
    public static function createEmpty(Point $point)
    {
        return new static($point);
    }

    /**
     * Place a [Stone] in the [Field].
     *
     * @param Stone $stone
     */
    public function placeStone(Stone $stone)
    {
        $this->stone = $stone;
    }

    /**
     * Returns true if the [Field] is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->stone === null;
    }

    /**
     * Returns the [Stone] of the [Field].
     *
     * @return Stone|null
     */
    public function stone()
    {
        return $this->stone;
    }

    /**
     * Returns the point of the [Field].
     *
     * @return Point
     */
    public function point()
    {
        return $this->point;
    }

    /**
     * Returns the string representation of the [Field].
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->isEmpty()) {
            return ' ';
        }

        return (string)$this->stone()->color();
    }
}