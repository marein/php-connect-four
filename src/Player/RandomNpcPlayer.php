<?php

namespace Marein\ConnectFour\Player;

use Marein\ConnectFour\Domain\Game\Game;
use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Stone;

class RandomNpcPlayer
{
    private $game;

    /**
     * RandomNpcPlayer constructor.
     *
     * @param Game $game
     * @param      $thinkingTime
     */
    public function __construct(Game $game, $thinkingTime)
    {
        $this->game = $game;
        $this->thinkingTime = $thinkingTime * 1000000;
    }

    /**
     * Play.
     *
     * @param Stone $stone
     */
    public function play(Stone $stone)
    {
        echo PHP_EOL . 'Waiting for NPC...';

        usleep($this->thinkingTime);

        do {
            $error = false;
            try {
                $column = rand(1, $this->game->size()->width());
                $this->game->dropStone($stone, $column);
            } catch (ColumnAlreadyFilledException $e) {
                $error = true;
            }
        } while ($error);
    }
}