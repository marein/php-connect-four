<?php

namespace Marein\ConnectFour\Domain\Game;

final class State
{
    const RUNNING = 1;
    const WON = 2;
    const DRAWN = 3;

    /**
     * @var int
     */
    private $state;

    /**
     * @var Player|null
     */
    private $winner;

    /**
     * State constructor.
     *
     * @param int         $state
     * @param Player|null $winner
     */
    private function __construct($state, Player $winner = null)
    {
        $this->state = $state;
        $this->winner = $winner;
    }

    /*************************************************************
     *                         Factory
     *************************************************************/

    /**
     * @return State
     */
    public static function running(): State
    {
        return new self(self::RUNNING);
    }

    /**
     * @return State
     */
    public static function won(Player $player): State
    {
        return new self(self::WON, $player);
    }

    /**
     * @return State
     */
    public static function drawn(): State
    {
        return new self(self::DRAWN);
    }

    /*************************************************************
     *                          Getter
     *************************************************************/

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->state == self::RUNNING;
    }

    /**
     * @return bool
     */
    public function isWon(): bool
    {
        return $this->state == self::WON;
    }

    /**
     * @return bool
     */
    public function isDrawn(): bool
    {
        return $this->state == self::DRAWN;
    }

    /**
     * @return Player|null
     */
    public function winner(): ?Player
    {
        return $this->winner;
    }
}