<?php

namespace Marein\ConnectFour\Domain\Game;

final class Point
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * Point constructor.
     *
     * @param int $x
     * @param int $y
     */
    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Returns the x of the [Point].
     *
     * @return int
     */
    public function x(): int
    {
        return $this->x;
    }

    /**
     * Returns the y of the [Point].
     *
     * @return int
     */
    public function y(): int
    {
        return $this->y;
    }
}