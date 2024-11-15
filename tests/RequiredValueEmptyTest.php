<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class RequiredValueEmptyTest extends TestCase
{
    public function test(): void
    {
        $exception = new RequiredValueEmpty('name', 'string3');

        $this->assertEquals("The Required value 'name' is empty (expected: 'string3')", $exception->getMessage());
    }
}
