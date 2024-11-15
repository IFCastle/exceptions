<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class UnexpectedValueTypeTest extends TestCase
{
    public function test(): void
    {
        $exception = new UnexpectedValueType('name', 'value', 'rules');

        $this->assertEquals("Unexpected type occurred for the value 'name' and type 'STRING'. Expected 'rules'", $exception->getMessage());
    }
}
