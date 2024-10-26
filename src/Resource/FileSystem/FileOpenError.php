<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource\FileSystem;

use IfCastle\Exceptions\Resource\ResourceOpenError;

class FileOpenError extends ResourceOpenError implements FileSystemExceptionInterface
{
    protected string $system   = self::SYSTEM;

    public function __construct($resource, string $type = 'file')
    {
        parent::__construct($resource, $type);
    }
}
