<?php

namespace Marein\ConnectFour\Player;

use Marein\ConnectFour\Domain\Game\Game;
use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Stone;

class ConsolePlayer
{
    private $game;

    /**
     * ConsolePlayer constructor.
     *
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * Play.
     *
     * @param Stone $stone
     */
    public function play(Stone $stone)
    {
        do {
            $error = false;
            do {
                $column = trim(readline(PHP_EOL . 'Choose your column [1 - 7]: '));
            } while (!in_array($column, range(1, $this->game->size()->width())));
            try {
                $this->game->dropStone($stone, $column);
            } catch (ColumnAlreadyFilledException $e) {
                $error = true;
            }
        } while ($error);
    }
}