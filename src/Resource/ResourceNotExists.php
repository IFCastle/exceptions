<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource;

class ResourceNotExists extends ResourceException
{
    protected string $template = '{system} error: {type} is not exist. Resource: {resource}, Operation: {operation}';

    /**
     * @param string|object|resource|array<string, scalar|scalar[]> $resource
     */
    public function __construct(mixed $resource, string $type = 'resource')
    {
        parent::__construct([
            'resource'  => $this->typeInfo($resource),
            'operation' => 'is_' . $type,
            'type'      => $type,
            'system'    => $this->system,
        ]);
    }
}
