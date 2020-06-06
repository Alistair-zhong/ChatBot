<?php

namespace Tests\Feature;

use Tests\TestCase;

class LearningTest extends TestCase
{
    /**
     * @test
     */
    public function array_is_empty()
    {
        $stack = [];
        $this->assertEmpty($stack);

        return $stack;
    }

    /**
     * @test
     */
    public function arg2()
    {
        $this->assertTrue(true);

        return 'arg2';
    }

    /**
     * @depends array_is_empty
     * @depends arg2
     * @test
     */
    public function can_push(array $stack)
    {
        $this->assertSame([[], 'arg2'], \func_get_args());

        array_push($stack, 'niro');
        $this->assertSame('niro', end($stack));
        $this->assertNotEmpty($stack);

        return $stack;
    }

    /**
     * @test
     * @dataProvider sumProvider
     */
    public function sum($a, $b, $sum)
    {
        $this->assertSame($a + $b, $sum);

        return $sum;
    }

    public function sumProvider()
    {
        return [
            ['a' => 1, 'b' => 2, 'sum' => 3],
            ['a' => 2, 'b' => 2, 'sum' => 4],
        ];
    }

    /**
     * @test
     * @depends sum
     */
    public function value_returned_from_sum_cannot_pass_in($one)
    {
        $this->assertNull($one);
    }

    /**
     * @test
     */
    public function expect_output_string()
    {
        $this->expectOutputString('foo');
        echo 'foo';
    }
}
