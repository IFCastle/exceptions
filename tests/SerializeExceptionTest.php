<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class SerializeExceptionTest extends TestCase
{
    public function test(): void
    {
        $exception = new SerializeException('reason', new \stdClass(), 'phpserialize', new \stdClass());
        
        $this->assertEquals("Serialize process was failed (type:'phpserialize', object:'stdClass', src:'stdClass'). reason", $exception->getMessage());
    }
}
