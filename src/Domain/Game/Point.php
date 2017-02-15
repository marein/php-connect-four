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
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Returns the x of the [Point].
     *
     * @return int
     */
    public function x()
    {
        return $this->x;
    }

    /**
     * Returns the y of the [Point].
     *
     * @return int
     */
    public function y()
    {
        return $this->y;
    }

    /**
     *    /
     *   /
     *  /
     * /
     * @param Point $fromPoint
     * @param Size  $withinSize
     *
     * @return Point[]
     */
    public static function createPointsInDiagonalUp(Point $fromPoint, Size $withinSize)
    {
        $points = [];

        for ($x = $fromPoint->x(), $y = $fromPoint->y(); $x > 0 && $y <= $withinSize->height(); $x--, $y++) {
            array_unshift($points, new self($x, $y));
        }

        for ($x = $fromPoint->x(), $y = $fromPoint->y(); $x <= $withinSize->width() && $y > 0; $x++, $y--) {
            array_push($points, new self($x, $y));
        }

        return array_unique($points, SORT_REGULAR);
    }

    /**
     * \
     *  \
     *   \
     *    \
     * @param Point $fromPoint
     * @param Size  $withinSize
     *
     * @return array
     */
    public static function createPointsInDiagonalDown(Point $fromPoint, Size $withinSize)
    {
        $points = [];

        for ($x = $fromPoint->x(), $y = $fromPoint->y(); $x > 0 && $y > 0; $x--, $y--) {
            array_unshift($points, new self($x, $y));
        }

        for ($x = $fromPoint->x(), $y = $fromPoint->y(); $x <= $withinSize->width() && $y <= $withinSize->height(); $x++, $y++) {
            array_push($points, new self($x, $y));
        }

        return array_unique($points, SORT_REGULAR);
    }
}