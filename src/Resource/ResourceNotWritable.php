<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource;

class ResourceNotWritable extends ResourceException
{
    /**
     * @param string|object|resource|array<string, scalar|scalar[]> $resource
     */
    public function __construct(mixed $resource, string $type = 'resource')
    {
        parent::__construct($resource, $type, 'is_writable');
    }
}
