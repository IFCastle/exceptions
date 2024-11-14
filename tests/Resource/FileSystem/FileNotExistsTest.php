<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use PHPUnit\Framework\TestCase;

class FileNotExistsTest extends TestCase
{
    public function test(): void
    {
        $exception = new FileNotExists('file.txt');
        $this->assertEquals("'FileSystem' error: 'file' is not exist. Resource: 'file.txt', Operation: 'is_file'", $exception->getMessage());
    }
}
