<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use PHPUnit\Framework\TestCase;

class FileNotWritableTest extends TestCase
{
    public function test(): void
    {
        $exception = new FileNotWritable('file.txt');
        $this->assertEquals("'FileSystem' error: operation 'is_writable' for the resource 'file.txt' ('file') is failed", $exception->getMessage());
    }
}
