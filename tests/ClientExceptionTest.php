<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class ClientExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $exception                  = new ClientException(
            'The exception with {parameter1}, {parameter2}',
            ['parameter1' => 'value1', 'parameter2' => 'value2'],
            ['debug' => 'debug'],
        );
        
        $this->assertEquals('The exception with \'value1\', \'value2\'', $exception->getMessage());
        $this->assertEquals('The exception with {parameter1}, {parameter2}', $exception->getClientMessage());
        $this->assertEquals('The exception with {parameter1}, {parameter2}', $exception->getTemplate());
    }
    
    public function testClientSerialize(): void
    {
        $exception                  = new ClientException(
            'The exception with {parameter1}, {parameter2}',
            ['parameter1' => 'value1', 'parameter2' => 'value2'],
            ['debug' => 'debug'],
        );
        
        $this->assertEquals('The exception with \'value1\', \'value2\'', $exception->getMessage());
        $this->assertEquals('The exception with {parameter1}, {parameter2}', $exception->getClientMessage());
        $this->assertEquals('The exception with {parameter1}, {parameter2}', $exception->getTemplate());
        
        $this->assertEquals([
            'message'       => 'The exception with \'value1\', \'value2\'',
            'template'      => 'The exception with {parameter1}, {parameter2}',
            'parameters'    => ['parameter1' => 'value1', 'parameter2' => 'value2'],
        ], $exception->clientSerialize());
    }
}
