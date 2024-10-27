<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

interface SaveHandlerInterface
{
    /**
     * @param array<\Throwable> $exceptions
     * @param callable $resetLog
     * @param array<string, mixed> $loggerOptions
     * @param array<string, mixed> $debugOptions
     */
    public function saveExceptions(array $exceptions, callable $resetLog, array $loggerOptions = [], array $debugOptions = []): void;
}
