<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * If class doesn't exist or not loaded with autoload.
 */
class ClassNotExist extends LoggableException
{
    protected string $template     = 'The class {class} does not exist';

    /**
     * ClassNotExist.
     *
     * @param string|array<string, scalar|scalar[]>          $class Class name
     */
    public function __construct(array|string $class)
    {
        if (!\is_scalar($class)) {
            parent::__construct($class);
        } else {
            parent::__construct(['class'   => $class ]);
        }
    }
}
