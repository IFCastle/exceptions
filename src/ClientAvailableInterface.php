<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * ## ClientAvailableInterface.
 *
 * Exception can be shown to the client
 */
interface ClientAvailableInterface
{
    public function getClientMessage(): string;

    /**
     * Serialize exception for client.
     *
     * @return array<string, scalar|scalar[]>
     */
    public function clientSerialize(): array;
}
