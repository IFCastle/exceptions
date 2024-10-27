<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use IfCastle\Exceptions\Resource\ResourceNotExists;

class FileNotExists extends ResourceNotExists implements FileSystemExceptionInterface
{
    protected string $system   = self::SYSTEM;
    
    /**
     * @param string|object|resource|array<string, scalar|scalar[]> $resource
     * @param string $type
     */
    public function __construct(mixed $resource, string $type = 'file')
    {
        parent::__construct($resource, $type);
    }
}
