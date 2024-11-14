<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use PHPUnit\Framework\TestCase;

class FileOpenErrorTest extends TestCase
{
    public function test(): void
    {
        $exception = new FileOpenError('file.txt');
        $this->assertEquals("'FileSystem' error: operation 'open' for the resource 'file.txt' ('file') is failed", $exception->getMessage());
    }
}
