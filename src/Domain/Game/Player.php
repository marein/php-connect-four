<?php

namespace Marein\ConnectFour\Domain\Game;

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
     */
    public function __construct(string $id, Stone $stone)
    {
        $this->id = $id;
        $this->stone = $stone;
    }

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