<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class UnexpectedMethodModeTest extends TestCase
{
    public function test(): void
    {
        $exception = new UnexpectedMethodMode('name', 'value', 'rules');
        
        $this->assertEquals("Unexpected method mode occurred (method: 'name', mode: 'value' = 'rules')", $exception->getMessage());
    }
}
