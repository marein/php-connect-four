<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\PlayerHasInvalidStoneException;

final class Player
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Stone
     */
    private $stone;

    /**
     * Player constructor.
     *
     * @param string $id
     * @param Stone  $stone
     *
     * @throws PlayerHasInvalidStoneException
     */
    public function __construct(string $id, Stone $stone)
    {
        $this->id = $id;
        $this->stone = $stone;

        $this->guardPlayerHasCorrectStone($stone);
    }

    /*************************************************************
     *                          Guards
     *************************************************************/

    /**
     * Guard that the [Stone] is Stone::red() or Stone::yellow().
     *
     * @param Stone $stone
     *
     * @throws PlayerHasInvalidStoneException
     */
    private function guardPlayerHasCorrectStone(Stone $stone): void
    {
        if ($stone == Stone::none()) {
            throw new PlayerHasInvalidStoneException('Stone should be Stone::red() or Stone::yellow().');
        }
    }

    /*************************************************************
     *                          Getter
     *************************************************************/

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return Stone
     */
    public function stone(): Stone
    {
        return $this->stone;
    }
}