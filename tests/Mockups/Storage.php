<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Mockups;

use IfCastle\Exceptions\BaseExceptionInterface;
use IfCastle\Exceptions\StorageInterface;

class Storage implements StorageInterface
{
    /**
     * List of exceptions.
     *
     * @var BaseExceptionInterface[]|\Throwable[]
     */
    public array $Exceptions = [];

    /**
     * @return      $this
     */
    #[\Override]
    public function addException(BaseExceptionInterface|\Throwable $exception): static
    {
        $this->Exceptions[] = $exception;

        return $this;
    }

    #[\Override]
    public function getStorageExceptions(): array
    {
        return $this->Exceptions;
    }

    #[\Override]
    public function resetStorage(): static
    {
        $this->Exceptions = [];

        return $this;
    }
}
