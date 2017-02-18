<?php

namespace Marein\ConnectFour\Player;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Game;

class RandomNpcPlayer
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Game
     */
    private $game;

    /**
     * @var float
     */
    private $thinkingTime;

    /**
     * RandomNpcPlayer constructor.
     *
     * @param string $id
     * @param Game   $game
     * @param float  $thinkingTime
     */
    public function __construct(string $id, Game $game, float $thinkingTime)
    {
        $this->id = $id;
        $this->game = $game;
        $this->thinkingTime = $thinkingTime * 1000000;
    }

    /**
     * Play.
     */
    public function play()
    {
        echo PHP_EOL . 'Waiting for NPC...';

        usleep($this->thinkingTime);

        do {
            $error = false;
            try {
                $column = rand(1, $this->game->configuration()->size()->width());
                $this->game->move($this->id, $column);
            } catch (ColumnAlreadyFilledException $e) {
                $error = true;
            }
        } while ($error);
    }
}