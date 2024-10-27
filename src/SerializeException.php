<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * Object can't be serialized!
 */
class SerializeException extends LoggableException
{
    protected string $template      = 'Serialize process was failed (type:{type}, object:{object}, src:{srcObject}). {reason}';

    /**
     * Object can't be serialized!
     *
     * @param       string|array<string, scalar|scalar[]> $reason    Reason of error
     * @param       object       $object    The object which must have been serialized
     * @param       string       $type      Type of serializing
     * @param       object       $srcObject The object where started the process
     */
    public function __construct(array|string $reason, $object = null, string $type = 'phpserialize', $srcObject = null)
    {
        if (!\is_string($reason)) {
            parent::__construct($reason);
            return;
        }

        parent::__construct([
            'message'       => 'Serialize Failed',
            'reason'        => $reason,
            'type'          => $type,
            'object'        => $this->typeInfo($object),
            'srcObject'     => $this->typeInfo($srcObject),
        ]);
    }
}
