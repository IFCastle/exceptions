<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use PHPUnit\Framework\TestCase;

class FileReadErrorTest extends TestCase
{
    public function test(): void
    {
        $exception = new FileReadError('file.txt', 'file');

        $this->assertEquals("'FileSystem' error: operation 'read' for the resource 'file.txt' ('file') is failed", $exception->getMessage());
    }
}
