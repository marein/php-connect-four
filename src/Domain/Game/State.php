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
     * Create a running [State].
     *
     * @return State
     */
    public static function running(): State
    {
        return new self(self::RUNNING);
    }

    /**
     * Create a won [State].
     *
     * @param Player $player
     *
     * @return State
     */
    public static function won(Player $player): State
    {
        return new self(self::WON, $player);
    }

    /**
     * Create a drawn [State].
     *
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
     * Returns true if the [State] is running.
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->state == self::RUNNING;
    }

    /**
     * Returns true if the [State] represents a win.
     *
     * @return bool
     */
    public function isWon(): bool
    {
        return $this->state == self::WON;
    }

    /**
     * Returns true if the [State] represents a draw.
     *
     * @return bool
     */
    public function isDrawn(): bool
    {
        return $this->state == self::DRAWN;
    }

    /**
     * Returns a [Player] if present.
     *
     * @return Player|null
     */
    public function winner(): ?Player
    {
        return $this->winner;
    }
}