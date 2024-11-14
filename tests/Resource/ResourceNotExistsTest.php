<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource;

use PHPUnit\Framework\TestCase;

class ResourceNotExistsTest extends TestCase
{
    public function testNotExists(): void
    {
        $exception = new ResourceNotExists('file.txt', 'file');
        
        $this->assertEquals("'undefined' error: 'file' is not exist. Resource: 'STRING', Operation: 'is_file'", $exception->getMessage());
    }
}
