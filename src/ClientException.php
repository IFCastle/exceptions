<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * A special class of error that can be displayed to the client.
 */
class ClientException extends BaseException implements ClientAvailableInterface
{
    /**
     * @param array<string, scalar|null|scalar[]>  $parameters
     * @param array<string, scalar|null|scalar[]>  $debugData
     */
    public function __construct(string $template, array $parameters = [], array $debugData = [])
    {
        parent::__construct(['template' => $template] + $parameters);
        $this->setDebugData($debugData);
    }

    #[\Override]
    public function getClientMessage(): string
    {
        return $this->template !== '' ? $this->template : $this->getMessage();
    }

    #[\Override]
    public function clientSerialize(): array
    {
        return [
            'template'              => $this->template,
            'message'               => $this->getMessage(),
            'parameters'            => $this->getExceptionData(),
        ];
    }
}
