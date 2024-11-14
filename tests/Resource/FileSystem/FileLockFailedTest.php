<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use PHPUnit\Framework\TestCase;

class FileLockFailedTest extends TestCase
{
    public function test(): void
    {
        $exception = new FileLockFailed('file.txt');
        $this->assertEquals("'FileSystem' error: operation 'lock' for the resource 'file.txt' ('file') is failed", $exception->getMessage());
    }
}
