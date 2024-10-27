<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * If object not initialized
 * but using!
 */
class ObjectNotInitialized extends LoggableException
{
    protected string $template      = 'Object {object} is not initialized';

    /**
     * If an object isn't initialized.
     *
     * @param   object      $object     Object
     * @param   string      $message    Addition message
     */
    public function __construct(mixed $object = null, string $message = '')
    {
        parent::__construct(['object' => $this->typeInfo($object), 'message' => $message]);
    }
}
