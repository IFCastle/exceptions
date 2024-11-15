<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class ObjectNotInitializedTest extends TestCase
{
    public function test(): void
    {
        $exception = new ObjectNotInitialized('name', 'Additional message');

        $this->assertEquals("Object 'STRING' is not initialized. Additional message", $exception->getMessage());
    }
}
