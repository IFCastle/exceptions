<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * Contract is not correct
 * (When an object does not support the required conditions of Trait,
 * Traits
 * throws that exception).
 */
class ContractNotCorrect        extends LoggableException
{
    public const string PROPERTY = 'property';

    public const string INTERFACE = 'interface';

    public const string METHOD = 'method';

    protected string $template  = 'Contract is not correctly for {type} in the trait {trait} which used by {object}';
    
    /**
     * The Contract is not correct.
     *
     * @param object      $object object used trait
     * @param string      $type   type of contract
     * @param null        $value  incorrect value
     * @param string|null $trait  trait name
     * @param string      $notice extended message
     */
    public function __construct(object $object, $type = self::PROPERTY, $value = null, string $trait = null, string $notice = '')
    {
        parent::__construct([
            'message'       => $notice,
            'object'        => $this->typeInfo($object),
            'type'          => $type,
            'value'         => $this->toString($value),
            'trait'         => $trait,
        ]);
    }
}
