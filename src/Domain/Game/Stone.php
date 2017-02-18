<?php

namespace Marein\ConnectFour\Domain\Game;

/**
 * This class fakes the missing enumeration feature.
 */
final class Stone
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
    public static function yellow()
    {
        return new self(self::YELLOW);
    }

    /**
     * Creates a red [Stone].
     *
     * @return Stone
     */
    public static function red()
    {
        return new self(self::RED);
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