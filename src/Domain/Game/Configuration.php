<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\WinningStrategy\CommonWinningStrategy;
use Marein\ConnectFour\Domain\Game\WinningStrategy\WinningStrategy;

final class Configuration
{
    /**
     * @var Size
     */
    private $size;

    /**
     * @var WinningStrategy
     */
    private $winningStrategy;

    /**
     * Configuration constructor.
     *
     * @param Size            $size
     * @param WinningStrategy $winningStrategy
     */
    private function __construct(Size $size, WinningStrategy $winningStrategy)
    {
        $this->size = $size;
        $this->winningStrategy = $winningStrategy;
    }

    /*************************************************************
     *                         Factory
     *************************************************************/

    /**
     * Create a common [Configuration].
     *
     * @return Configuration
     */
    public static function common(): Configuration
    {
        return new self(
            new Size(7, 6),
            new CommonWinningStrategy()
        );
    }

    /**
     * Create a custom [Configuration].
     *
     * @param Size            $size
     * @param WinningStrategy $winningStrategy
     *
     * @return Configuration
     */
    public static function custom(Size $size, WinningStrategy $winningStrategy): Configuration
    {
        return new self($size, $winningStrategy);
    }

    /*************************************************************
     *                          Getter
     *************************************************************/

    /**
     * Returns the [Size].
     *
     * @return Size
     */
    public function size(): Size
    {
        return $this->size;
    }

    /**
     * Returns the [WinningStrategy].
     *
     * @return WinningStrategy
     */
    public function winningStrategy(): WinningStrategy
    {
        return $this->winningStrategy;
    }
}