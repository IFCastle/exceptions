<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource;

use PHPUnit\Framework\TestCase;

class ResourceExceptionTest extends TestCase
{
    public function testResourceException(): void
    {
        $resourceException = new ResourceException('Resource exception message');
        $this->assertEquals("'undefined' error: operation '' for the resource 'Resource exception message' ('') is failed. undefined error: operation \"\" failed", $resourceException->getMessage());
    }
}
