<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use PHPUnit\Framework\TestCase;

class FileUnLockFailedTest extends TestCase
{
    public function test(): void
    {
        $exception = new FileUnLockFailed('file', 'string');

        $this->assertEquals([
            'resource' => 'file',
            'type' => 'string',
            'operation' => 'unlock',
            'system' => 'FileSystem',
        ], $exception->getExceptionData());
    }
}
