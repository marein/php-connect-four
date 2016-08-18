<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\InvalidSizeException;

class SizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider correctSizeProvider
     *
     * @param int $width
     * @param int $height
     */
    public function itShouldBeCreatedSuccessfully($width, $height)
    {
        new Size($width, $height);
    }

    /**
     * @return array
     */
    public function correctSizeProvider()
    {
        return [
            [4, 4],
            [7, 6],
            [5, 4],
            [10, 9]
        ];
    }

    /**
     * @test
     * @dataProvider wrongSizeProvider
     *
     * @param int $width
     * @param int $height
     */
    public function itShouldThrowAnExceptionOnInvalidSizes($width, $height)
    {
        $this->expectException(InvalidSizeException::class);

        new Size($width, $height);
    }

    /**
     * @return array
     */
    public function wrongSizeProvider()
    {
        return [
            [3, 3],
            [5, 5],
            [-1, 3],
            [2, -3],
            [-1, -3],
            [1, 1],
        ];
    }
}