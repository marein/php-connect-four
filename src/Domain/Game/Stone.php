<?php

namespace Marein\ConnectFour\Domain\Game;

class Stone
{
    const RED = 1;
    const YELLOW = 2;

    /**
     * @var int
     */
    private $color;

    /**
     * Stone constructor.
     *
     * @param int $color
     */
    private function __construct($color)
    {
        $this->color = $color;
    }

    /**
     * Creates a yellow [Stone].
     *
     * @return Stone
     */
    public static function pickUpYellow()
    {
        return new static(static::YELLOW);
    }

    /**
     * Creates a red [Stone].
     *
     * @return Stone
     */
    public static function pickUpRed()
    {
        return new static(static::RED);
    }

    /**
     * Returns the color of the [Stone].
     *
     * @return int
     */
    public function color()
    {
        return $this->color;
    }
}