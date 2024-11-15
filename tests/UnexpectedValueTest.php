<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class UnexpectedValueTest extends TestCase
{
    public function test(): void
    {
        $exception = new UnexpectedValue('name', 'value', 'rules');

        $this->assertEquals("Unexpected value 'value' occurred in the variable 'name'. rules", $exception->getMessage());
    }
}
