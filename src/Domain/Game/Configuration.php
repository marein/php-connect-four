<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\GameNotWinnableException;

class Configuration
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
     * Configuration constructor.
     *
     * @param Size            $size
     * @param RequiredMatches $requiredMatches
     */
    private function __construct(Size $size, RequiredMatches $requiredMatches)
    {
        $this->size = $size;
        $this->requiredMatches = $requiredMatches;

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
        return new static(
            new Size(7, 6),
            new RequiredMatches(4)
        );
    }

    /**
     * Create a custom [Configuration].
     *
     * @param Size            $size
     * @param RequiredMatches $requiredMatches
     *
     * @return Configuration
     */
    public static function custom(Size $size, RequiredMatches $requiredMatches)
    {
        return new static($size, $requiredMatches);
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
     * Returns the [RequiredMatches].
     *
     * @return RequiredMatches
     */
    public function requiredMatches()
    {
        return $this->requiredMatches;
    }

    /**
     * Returns the [Size].
     *
     * @return Size
     */
    public function size()
    {
        return $this->size;
    }
}