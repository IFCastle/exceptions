<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * The method does not support this mode of work.
 */
class UnexpectedMethodMode extends LoggableException
{
    protected string $template      = 'Unexpected method mode occurred (method: {method}, mode: {mode} = {value})';
    
    /**
     * The method does not support this mode of work.
     *
     * @param string|mixed[] $method               The method name
     *                                              or list of parameters for exception
     *                                              or another exception for container
     * @param string|null     $mode                 Name of mode
     * @param string|int|null $value                Mode value
     */
    public function __construct(array|string $method, string $mode   = null, string|int $value  = null)
    {
        if (!\is_scalar($method)) {
            parent::__construct($method);
        } else {
            parent::__construct([
                'method'  => $method,
                'mode'    => $mode,
                'value'   => $value,
            ]);
        }
    }
}
