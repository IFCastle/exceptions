<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use IfCastle\Exceptions\Resource\ResourceReadError;

class FileReadError extends ResourceReadError implements FileSystemExceptionInterface
{
    protected string $system   = self::SYSTEM;

    /**
     * @param string|object|resource|array<string, scalar|scalar[]> $resource
     */
    public function __construct(mixed $resource, string $type = 'file')
    {
        parent::__construct($resource, $type);
    }
}
