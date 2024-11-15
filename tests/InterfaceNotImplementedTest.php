<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class InterfaceNotImplementedTest extends TestCase
{
    public function test(): void
    {
        $exception = new InterfaceNotImplemented('myClassName', 'Interface');
        $this->assertEquals("Class 'myClassName' does not implement interface 'Interface'", $exception->getMessage());

        $exception = new InterfaceNotImplemented(new \stdClass(), 'Interface');
        $this->assertEquals("Class 'stdClass' does not implement interface 'Interface'", $exception->getMessage());
    }
}
