<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource;

class ResourceDataWrong extends ResourceException
{
    protected string $template = '{system} error: data is wrong (expected {format}) for resource {resource}';

    /**
     * @param string|object|resource|array<string, scalar|scalar[]> $resource
     */
    public function __construct(mixed $resource, string $type = 'resource', string $format = 'format')
    {
        parent::__construct([
            'resource'  => !\is_string($resource) ? $this->typeInfo($resource) : $resource,
            'type'      => $type,
            'operation' => 'format:' . $format,
            'format'    => $format,
            'system'    => $this->system,
        ]);
    }
}
