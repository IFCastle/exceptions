<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use PHPUnit\Framework\TestCase;

class FileCloseErrorTest extends TestCase
{
    public function test(): void
    {
        $exception = new FileCloseError('file.txt');
        $this->assertEquals("'FileSystem' error: operation 'close' for the resource 'file.txt' ('file') is failed. FileSystem error: operation \"close\" failed", $exception->getMessage());
    }
}
