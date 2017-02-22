<?php

namespace Marein\ConnectFour\Domain\Game;

final class Field
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
     * @param Stone $stone
     */
    private function __construct(Point $point, Stone $stone = null)
    {
        $this->point = $point;
        $this->stone = $stone;
    }

    /**
     * Create an empty [Field].
     *
     * @param Point $point
     *
     * @return Field
     */
    public static function empty(Point $point): Field
    {
        return new self($point);
    }

    /**
     * Place a [Stone].
     *
     * @param Stone $stone
     *
     * @return Field
     */
    public function placeStone(Stone $stone): Field
    {
        return new self($this->point(), $stone);
    }

    /**
     * Returns true if the [Field] is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->stone === null;
    }

    /**
     * Returns the [Stone] of the [Field].
     *
     * @return Stone|null
     */
    public function stone(): ?Stone
    {
        return $this->stone;
    }

    /**
     * Returns the point of the [Field].
     *
     * @return Point
     */
    public function point(): Point
    {
        return $this->point;
    }

    /**
     * Returns the string representation of the [Field].
     *
     * @return string
     */
    public function __toString(): string
    {
        if ($this->isEmpty()) {
            return ' ';
        }

        return (string)$this->stone()->color();
    }
}