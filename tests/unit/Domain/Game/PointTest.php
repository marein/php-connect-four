<?php

namespace Marein\ConnectFour\Domain\Game;

class PointTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldBeCreatedWithItsValues(): void
    {
        $point = new Point(3, 4);

        $this->assertEquals($point->x(), 3);
        $this->assertEquals($point->y(), 4);
    }

    /**
     * @test
     * @dataProvider pointsUpProvider
     *
     * @param Size    $size
     * @param Point   $point
     * @param Point[] $expectedPoints
     */
    public function itShouldCalculateDiagonalPointsUp(Size $size, Point $point, array $expectedPoints): void
    {
        // Call array_values for re-index
        $this->assertEquals(
            array_values(Point::createPointsInDiagonalUp($point, $size)),
            $expectedPoints
        );
    }

    /**
     * @return array
     */
    public function pointsUpProvider(): array
    {
        return [
            [
                new Size(7, 6),
                new Point(5, 4),
                [
                    new Point(3, 6),
                    new Point(4, 5),
                    new Point(5, 4),
                    new Point(6, 3),
                    new Point(7, 2)
                ]
            ],
            [
                new Size(7, 6),
                new Point(3, 4),
                [
                    new Point(1, 6),
                    new Point(2, 5),
                    new Point(3, 4),
                    new Point(4, 3),
                    new Point(5, 2),
                    new Point(6, 1)
                ]
            ],
            [
                new Size(7, 6),
                new Point(5, 3),
                [
                    new Point(2, 6),
                    new Point(3, 5),
                    new Point(4, 4),
                    new Point(5, 3),
                    new Point(6, 2),
                    new Point(7, 1)
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider pointsDownProvider
     *
     * @param Size    $size
     * @param Point   $point
     * @param Point[] $expectedPoints
     */
    public function itShouldCalculateDiagonalPointsDown(Size $size, Point $point, array $expectedPoints): void
    {
        // Call array_values for re-index
        $this->assertEquals(
            array_values(Point::createPointsInDiagonalDown($point, $size)),
            $expectedPoints
        );
    }

    /**
     * @return array
     */
    public function pointsDownProvider(): array
    {
        return [
            [
                new Size(7, 6),
                new Point(3, 2),
                [
                    new Point(2, 1),
                    new Point(3, 2),
                    new Point(4, 3),
                    new Point(5, 4),
                    new Point(6, 5),
                    new Point(7, 6)
                ]
            ],
            [
                new Size(7, 6),
                new Point(5, 3),
                [
                    new Point(3, 1),
                    new Point(4, 2),
                    new Point(5, 3),
                    new Point(6, 4),
                    new Point(7, 5)
                ]
            ],
            [
                new Size(7, 6),
                new Point(5, 5),
                [
                    new Point(1, 1),
                    new Point(2, 2),
                    new Point(3, 3),
                    new Point(4, 4),
                    new Point(5, 5),
                    new Point(6, 6)
                ]
            ]
        ];
    }
}