<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * Value of variable has unexpected type.
 */
class UnexpectedValueType extends LoggableException
{
    protected string $template      = 'Unexpected type occurred for the value {name} and type {type}. Expected {expected}';

    /**
     * Value of variable has unexpected type.
     *
     * @param string|mixed[] $name                      Variable name
     *                                                  or list of parameters for exception
     * @param mixed        $value                       Value
     * @param string|class-string|null  $expected       Excepted type
     */
    public function __construct(array|string $name,
        mixed $value      = null,
        string|null $expected   = null)
    {
        if (!\is_scalar($name)) {
            parent::__construct($name);
            return;
        }

        parent::__construct([
            'name'        => $name,
            'type'        => $this->typeInfo($value),
            'expected'    => $expected,
        ]);
    }
}
