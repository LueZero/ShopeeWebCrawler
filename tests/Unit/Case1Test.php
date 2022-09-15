<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class Case1Test extends TestCase
{
    public function test1()
    {
        $stack = [];
        $this->assertSame(0, count($stack));
    }
}
