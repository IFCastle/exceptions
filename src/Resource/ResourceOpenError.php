<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource;

class ResourceOpenError extends ResourceException
{
    /**
     * @param string|object|resource|array<string, scalar|scalar[]> $resource
     * @param string $type
     */
    public function __construct(mixed $resource, string $type = 'resource')
    {
        parent::__construct($resource, $type, 'open');
    }
}
