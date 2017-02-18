<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\GameNotWinnableException;
use Marein\ConnectFour\Domain\Game\WinningStrategy\CommonWinningStrategy;
use Marein\ConnectFour\Domain\Game\WinningStrategy\WinningStrategy;

final class Configuration
{
    /**
     * @var Size
     */
    private $size;

    /**
     * @var RequiredMatches
     */
    private $requiredMatches;

    /**
     * @var WinningStrategy
     */
    private $winningStrategy;

    /**
     * Configuration constructor.
     *
     * @param Size            $size
     * @param RequiredMatches $requiredMatches
     * @param WinningStrategy $winningStrategy
     */
    private function __construct(Size $size, RequiredMatches $requiredMatches, WinningStrategy $winningStrategy)
    {
        $this->size = $size;
        $this->requiredMatches = $requiredMatches;
        $this->winningStrategy = $winningStrategy;

        $this->guardGameIsWinnable();
    }

    /*************************************************************
     *                         Factory
     *************************************************************/

    /**
     * Create a common [Configuration].
     *
     * @return Configuration
     */
    public static function common()
    {
        return new self(
            new Size(7, 6),
            new RequiredMatches(4),
            new CommonWinningStrategy()
        );
    }

    /**
     * Create a custom [Configuration].
     *
     * @param Size            $size
     * @param RequiredMatches $requiredMatches
     * @param WinningStrategy $winningStrategy
     *
     * @return Configuration
     */
    public static function custom(Size $size, RequiredMatches $requiredMatches, WinningStrategy $winningStrategy)
    {
        return new self($size, $requiredMatches, $winningStrategy);
    }

    /*************************************************************
     *                          Guards
     *************************************************************/

    /**
     * Guard that [Game] is winnable with this [Configuration].
     *
     * @throws GameNotWinnableException
     */
    private function guardGameIsWinnable()
    {
        if ($this->requiredMatches()->value() > $this->size()->height() &&
            $this->requiredMatches()->value() > $this->size()->width()
        ) {
            throw new GameNotWinnableException();
        }
    }

    /*************************************************************
     *                          Getter
     *************************************************************/

    /**
     * Returns the [Size].
     *
     * @return Size
     */
    public function size()
    {
        return $this->size;
    }

    /**
     * Returns the [RequiredMatches].
     *
     * @return RequiredMatches
     */
    public function requiredMatches()
    {
        return $this->requiredMatches;
    }

    /**
     * Returns the [WinningStrategy].
     *
     * @return WinningStrategy
     */
    public function winningStrategy()
    {
        return $this->winningStrategy;
    }
}