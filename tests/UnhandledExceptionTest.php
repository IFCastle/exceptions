<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class UnhandledExceptionTest extends TestCase
{
    public function test(): void
    {
        $exception = new UnhandledException(new \Exception('test'));
        
        $this->assertEquals("Unhandled Exception 'Exception' occurred in the [source:'IfCastle\Exceptions\UnhandledExceptionTest', type:'->', function:'test']", $exception->getMessage());
    }
}
