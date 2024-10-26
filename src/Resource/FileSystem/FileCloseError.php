<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use IfCastle\Exceptions\Resource\ResourceCloseError;

class FileCloseError extends ResourceCloseError implements FileSystemExceptionInterface
{
    protected string $system   = self::SYSTEM;

    public function __construct(mixed $resource, string $type = 'file')
    {
        parent::__construct($resource, $type);
    }
}
