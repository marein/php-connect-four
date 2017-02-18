<?php

namespace Marein\ConnectFour\Player;

use Marein\ConnectFour\Domain\Game\Exception\ColumnAlreadyFilledException;
use Marein\ConnectFour\Domain\Game\Game;

class ConsolePlayer
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
     * ConsolePlayer constructor.
     *
     * @param string $id
     * @param Game   $game
     */
    public function __construct(string $id, Game $game)
    {
        $this->id = $id;
        $this->game = $game;
    }

    /**
     * Play.
     */
    public function play()
    {
        do {
            $error = false;
            do {
                $column = trim(readline(PHP_EOL . 'Choose your column [1 - 7]: '));
            } while (!in_array($column, range(1, $this->game->configuration()->size()->width())));
            try {
                $this->game->move($this->id, $column);
            } catch (ColumnAlreadyFilledException $e) {
                $error = true;
            }
        } while ($error);
    }
}