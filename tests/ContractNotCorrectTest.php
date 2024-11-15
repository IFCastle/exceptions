<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Framework\TestCase;

class ContractNotCorrectTest extends TestCase
{
    public function test(): void
    {
        $exception                  = new UnexpectedValueType('name', 'value', 'expected');
        $this->assertEquals('Unexpected type occurred for the value \'name\' and type \'STRING\'. Expected \'expected\'', $exception->getMessage());

        $exception                  = new UnexpectedValueType('name', 1, 'expected');
        $this->assertEquals('Unexpected type occurred for the value \'name\' and type \'INTEGER\'. Expected \'expected\'', $exception->getMessage());

        $exception                  = new UnexpectedValueType('name', 1.1, 'expected');
        $this->assertEquals('Unexpected type occurred for the value \'name\' and type \'DOUBLE\'. Expected \'expected\'', $exception->getMessage());

        $exception                  = new UnexpectedValueType('name', true, 'expected');
        $this->assertEquals('Unexpected type occurred for the value \'name\' and type \'TRUE\'. Expected \'expected\'', $exception->getMessage());

        $exception                  = new UnexpectedValueType('name', null, 'expected');
        $this->assertEquals('Unexpected type occurred for the value \'name\' and type \'NULL\'. Expected \'expected\'', $exception->getMessage());

        $exception                  = new UnexpectedValueType('name', [], 'expected');
        $this->assertEquals('Unexpected type occurred for the value \'name\' and type \'ARRAY(0)\'. Expected \'expected\'', $exception->getMessage());

        $exception                  = new UnexpectedValueType('name', new \stdClass(), 'expected');
        $this->assertEquals('Unexpected type occurred for the value \'name\' and type \'stdClass\'. Expected \'expected\'', $exception->getMessage());
    }
}
