<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class UnSerializeExceptionTest extends TestCase
{
    public function test(): void
    {
        $exception = new UnSerializeException('name', 'value', 'rules');
        
        $this->assertEquals("Unserialize process was failed (type: 'value', node: 'rules'). name", $exception->getMessage());
    }
}
