<?php

namespace Marein\ConnectFour\Domain\Game;

class BoardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * All the tests of the class game should cover these tests. So check for immutability.
     *
     * @todo Move tests for [Board] from GameTest.php to BoardTest.php
     */

    /**
     * @test
     */
    public function itShouldBeImmutable()
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