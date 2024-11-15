<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * Object can't be unserialized!
 */
class UnSerializeException extends LoggableException
{
    protected string $template      = 'Unserialize process was failed (type: {type}, node: {node})';

    /**
     * Object can't be serialized!
     *
     * @param       string|mixed[]      $reason         Reason of error
     * @param       string              $type           Type of serialize
     * @param       string|null         $node           The node which must have been serialized
     */
    public function __construct(array|string $reason, string $type = 'phpserialize', string|null $node = null)
    {
        if (!\is_string($reason)) {
            parent::__construct($reason);
            return;
        }

        parent::__construct([
            'message'       => $reason,
            'type'          => $type,
            'node'          => \is_string($node) ? $node : $this->typeInfo($node),
        ]);
    }
}
