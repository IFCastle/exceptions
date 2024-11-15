<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use PHPUnit\Framework\TestCase;

class FileWriteErrorTest extends TestCase
{
    public function test(): void
    {
        $exception = new FileWriteError('file.txt', 'file');

        $this->assertEquals("'FileSystem' error: operation 'write' for the resource 'file.txt' ('file') is failed", $exception->getMessage());
    }
}
