<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * If required value is empty.
 */
class RequiredValueEmpty extends LoggableException
{
    protected string $template      = 'The Required value {name} is empty ({expected})';
    
    /**
     * If required value is empty.
     *
     * @param string|array<string|scalar|scalar[]> $name Variable name
     *                                                  or array with parameters.
     * @param string|null  $expected                    Excepted type
     */
    public function __construct(array|string $name, string $expected = null)
    {
        if (!\is_scalar($name)) {
            parent::__construct($name);
        } else {
            parent::__construct(['name' => $name, 'expected' => $expected]);
        }
    }
}
