<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\InvalidRequiredMatchesException;

class RequiredMatches
{
    const MINIMUM = 4;

    /**
     * @var int
     */
    private $value;

    /**
     * RequiredMatches constructor.
     *
     * @param $value
     *
     * @throws InvalidRequiredMatchesException
     */
    public function __construct($value)
    {
        $this->value = $value;

        if ($value < static::MINIMUM) {
            throw new InvalidRequiredMatchesException('The value must be at least ' . static::MINIMUM . '.');
        }
    }

    /**
     * Returns the required matches.
     *
     * @return int
     */
    public function value()
    {
        return $this->value;
    }
}