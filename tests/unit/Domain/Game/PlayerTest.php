<?php

namespace Marein\ConnectFour\Domain\Game;

use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedWithItsValues(): void
    {
        $id = uniqid();
        $stone = Stone::red();

        $player = new Player($id, $stone);

        $this->assertEquals($id, $player->id());
        $this->assertEquals($stone, $player->stone());
    }
}