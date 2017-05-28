<?php

namespace Marein\ConnectFour\Domain\Game\WinningRule;

use PHPUnit\Framework\TestCase;

class CommonWinningRuleTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldHaveTheRightRules(): void
    {
        // Test for the right rules directly at property level.
        $commonReflectionProperty = new \ReflectionProperty(CommonWinningRule::class, 'winningRule');
        $commonReflectionProperty->setAccessible(true);
        $multipleReflectionProperty = new \ReflectionProperty(MultipleWinningRule::class, 'winningRules');
        $multipleReflectionProperty->setAccessible(true);

        $common = new CommonWinningRule();

        $multiple = $commonReflectionProperty->getValue($common);

        $this->assertInstanceOf(MultipleWinningRule::class, $multiple);

        $rules = $multipleReflectionProperty->getValue($multiple);

        $this->assertCount(3, $rules);
        $this->assertInstanceOf(VerticalWinningRule::class, $rules[0]);
        $this->assertInstanceOf(HorizontalWinningRule::class, $rules[1]);
        $this->assertInstanceOf(DiagonalWinningRule::class, $rules[2]);
    }
}