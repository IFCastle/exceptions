<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class RecursionLimitExceededTest extends TestCase
{
    public function test(): void
    {
        $exception = new RecursionLimitExceeded(12);

        $this->assertEquals("Recursion limit exceeded: '12'", $exception->getMessage());
    }
}
