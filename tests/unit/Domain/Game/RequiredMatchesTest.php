<?php

namespace Marein\ConnectFour\Domain\Game;

use Marein\ConnectFour\Domain\Game\Exception\InvalidRequiredMatchesException;

class RequiredMatchesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreated()
    {
        $this->assertEquals(4, (new RequiredMatches(4))->value());
    }

    /**
     * @test
     * @dataProvider wrongValueProvider
     *
     * @param int $value
     */
    public function itShouldThrowAnExceptionOnInvalidValue($value)
    {
        $this->expectException(InvalidRequiredMatchesException::class);

        new RequiredMatches($value);
    }

    /**
     * @return array
     */
    public function wrongValueProvider()
    {
        return [
            [3],
            [-1],
            [2]
        ];
    }
}