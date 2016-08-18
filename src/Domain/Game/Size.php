<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\InvalidSizeException;

class Size
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * Size constructor.
     *
     * @param $width
     * @param $height
     *
     * @throws InvalidSizeException
     */
    public function __construct($width, $height)
    {
        if ($width < 2 || $height < 2) {
            throw new InvalidSizeException('Width and height must be greater then 1.');
        }

        if (($width * $height) % 2 != 0) {
            throw new InvalidSizeException('Product of width and high must be an even number.');
        }

        $this->height = $height;
        $this->width = $width;
    }

    /**
     * Returns the width of the [Size].
     *
     * @return int
     */
    public function width()
    {
        return $this->width;
    }

    /**
     * Returns the height of the [Size].
     *
     * @return int
     */
    public function height()
    {
        return $this->height;
    }
}