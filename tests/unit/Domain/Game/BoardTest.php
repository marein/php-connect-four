<?php

namespace Marein\ConnectFour\Domain\Game;

use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    /**
     * All the tests of the class game should cover these tests. So check for immutability.
     *
     * @todo Move tests for [Board] from GameTest.php to BoardTest.php
     */

    /**
     * @test
     */
    public function itShouldBeImmutable(): void
    {
        $configuration = Configuration::common();
        $board = Board::empty($configuration);

        $hashOfOriginalBoardBeforeChanges = $this->createBoardHash($board);

        $changedBoard = $board->dropStone(Stone::red(), 1);

        $hashOfOriginalBoardAfterChanges = $this->createBoardHash($board);

        $hashOfChangedBoard = $this->createBoardHash($changedBoard);

        $this->assertEquals($hashOfOriginalBoardBeforeChanges, $hashOfOriginalBoardAfterChanges);
        $this->assertNotEquals($hashOfOriginalBoardBeforeChanges, $hashOfChangedBoard);
    }

    /**
     * @param Board $board
     *
     * @return string
     */
    private function createBoardHash(Board $board): string
    {
        return md5(serialize($board));
    }
}