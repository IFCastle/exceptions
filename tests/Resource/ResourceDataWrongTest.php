<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource;

use PHPUnit\Framework\TestCase;

class ResourceDataWrongTest extends TestCase
{
    public function test(): void
    {
        $exception                  = new ResourceDataWrong('resource', 'string', 'string');
        
        $this->assertEquals([
            'resource' => 'STRING',
            'type' => 'string',
            'operation' => 'format:string',
            'format' => 'string',
            'system' => 'undefined',
        ], $exception->getExceptionData());
    }
}
