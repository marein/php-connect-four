<?php

namespace Marein\ConnectFour\Domain\Game;

use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    /**
     * @test
     */
    public function aRunningStateCanBeCreated(): void
    {
        $state = State::running();

        $this->assertEquals($state->isRunning(), true);
        $this->assertEquals($state->isWon(), false);
        $this->assertEquals($state->isDrawn(), false);
        $this->assertEquals($state->winner(), null);
    }

    /**
     * @test
     */
    public function aWonStateCanBeCreated(): void
    {
        $player = new Player('12345', Stone::red());
        $state = State::won($player);

        $this->assertEquals($state->isRunning(), false);
        $this->assertEquals($state->isWon(), true);
        $this->assertEquals($state->isDrawn(), false);
        $this->assertEquals($state->winner(), $player);
    }

    /**
     * @test
     */
    public function aDrawnStateCanBeCreated(): void
    {
        $state = State::drawn();

        $this->assertEquals($state->isRunning(), false);
        $this->assertEquals($state->isWon(), false);
        $this->assertEquals($state->isDrawn(), true);
        $this->assertEquals($state->winner(), null);
    }
}