<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Errors;

use IfCastle\Exceptions\BaseExceptionInterface;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public function testCreateError(): void
    {
        $line = __LINE__ + 1;
        $error = Error::createError(E_ERROR, 'This is an error', __FILE__, __LINE__);
        
        $this->assertEquals(E_ERROR, $error->getSeverity());
        $this->assertEquals('This is an error', $error->getMessage());
        $this->assertEquals(__FILE__, $error->getFile());
        $this->assertEquals($line, $error->getLine());
        $this->assertEquals(BaseExceptionInterface::ERROR, $error->getLevel());
        $this->assertEquals([
            'source' => __FILE__,
            'type' => '',
            'function' => ''
        ], $error->getSource());
    }
}
