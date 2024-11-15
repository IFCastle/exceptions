<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use PHPUnit\Framework\TestCase;

class FileNotReadableTest extends TestCase
{
    public function testNotReadable(): void
    {
        $exception = new FileNotReadable('file.txt', 'file');

        $this->assertEquals("'FileSystem' error: operation 'readable' for the resource 'file.txt' ('file') is failed", $exception->getMessage());
    }
}
