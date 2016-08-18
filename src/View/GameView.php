<?php

namespace Marein\ConnectFour\View;

use Marein\ConnectFour\Domain\Game\Game;
use Marein\ConnectFour\Domain\Game\Field;
use Marein\ConnectFour\Domain\Game\Stone;

class GameView
{
    /**
     * Render the [Game].
     *
     * @param Game $game
     */
    public function render(Game $game)
    {
        $this->clearScreen();
        $this->printHeader($game);
        $this->printFields($game);
    }

    /**
     * Clear the screen.
     */
    protected function clearScreen()
    {
        echo "\033\143" . PHP_EOL;
    }

    /**
     * Render the header of the [Game].
     *
     * @param Game $game
     */
    protected function printHeader(Game $game)
    {
        $width = $game->size()->width();

        foreach (range(1, $width) as $column) {
            echo '| ' . $column . ' ' . ($column == $width ? '|' : '');
        }

        echo PHP_EOL . PHP_EOL;
    }

    /**
     * Render the [Field]s [Game].
     *
     * @param Game $game
     */
    protected function printFields(Game $game)
    {
        $fields = $game->fields();
        $width = $game->size()->width();

        foreach ($fields as $i => $field) {
            echo '| ' . $this->fieldToString($field) . ' ';

            if (($i + 1) % $width == 0) {
                echo '|' . PHP_EOL . PHP_EOL;
            }
        }
    }

    /**
     * Colorize the [Field] for the console.
     *
     * @param Field $field
     *
     * @return mixed
     */
    protected function fieldToString(Field $field)
    {
        return str_replace(
            [Stone::RED, Stone::YELLOW],
            ["\033[0;31mO\033[0m", "\033[1;33mO\033[0m"],
            $field
        );
    }
}