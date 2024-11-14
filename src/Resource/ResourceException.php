<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Resource;

use IfCastle\Exceptions\SystemException;

/**
 * The basic class for exceptions related to resources.
 *
 * Child classes must define the property $system to identify the system
 */
class ResourceException extends SystemException
{
    protected string $template  =
        '{system} error: operation {operation} for the resource {resource} ({type}) is failed';

    protected string $system    = 'undefined';

    /**
     * @param       string|object|resource|array<string, scalar|scalar[]> $resource
     */
    public function __construct(mixed $resource, string $type = '', string $operation = '')
    {
        if (\is_array($resource)) {
            parent::__construct($resource);
        } else {

            if (\is_resource($resource)) {
                $resource = \get_resource_type($resource);
            } elseif (\is_object($resource)) {
                $resource = $resource::class;
            } elseif (!\is_string($resource)) {
                $resource = '!unknown!';
            }

            parent::__construct([
                'resource'  => $resource,
                'operation' => $operation,
                'type'      => $type,
                'system'    => $this->resourceSystem(),
            ]);
        }
    }

    /**
     * Method return Resource.
     *
     */
    public function resource(): string
    {
        return $this->data['resource'] ?? '';
    }

    /**
     * Method return system of Resource.
     */
    public function resourceSystem(): string
    {
        return $this->system;
    }

    /**
     * Method return type of Resource.
     *
     */
    public function resourceType(): string
    {
        return $this->data['type'] ?? '';
    }

    /**
     * Method return operation.
     *
     */
    public function resourceOperation(): string
    {
        return $this->data['operation'] ?? '';
    }
}
