<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Errors;

use IfCastle\Exceptions\BaseExceptionInterface;

/**
 * The class for encapsulating of PHP Errors
 * as object BaseExceptionI.
 */
class Error implements BaseExceptionInterface
{
    /**
  * Conformity between PHP-errors and BaseExceptionI.
  */
    protected static array $ERRORS =
        [
            E_ERROR              => self::ERROR,
            E_WARNING            => self::WARNING,
            E_PARSE              => self::CRITICAL,
            E_NOTICE             => self::NOTICE,
            E_CORE_ERROR         => self::EMERGENCY,
            E_CORE_WARNING       => self::WARNING,
            E_COMPILE_ERROR      => self::EMERGENCY,
            E_COMPILE_WARNING    => self::WARNING,
            E_USER_ERROR         => self::ERROR,
            E_USER_WARNING       => self::INFO,
            E_USER_NOTICE        => self::DEBUG,
            E_STRICT             => self::ERROR,
            E_RECOVERABLE_ERROR  => self::ERROR,
            E_DEPRECATED         => self::INFO,
            E_USER_DEPRECATED    => self::INFO,
        ];

    protected ?array $trace = null;

    /**
     * Loggable flag.
     */
    protected bool $isLoggable      = true;

    /**
     * Fatal error flag.
     */
    protected bool $isFatal         = false;

    public static function createFromLastError(?array $error = null): ?static
    {
        if ($error === null) {
            return null;
        }

        return static::createError(
            $error['type'] ?? 0, $error['message'] ?? '', $error['file'] ?? '', $error['line'] ?? 0
        );
    }

    /**
     * Errors factory.
     *
     * @param        int    $code    Class of error
     * @param        string $message Message
     * @param        string $file    File
     * @param        int    $line    Line
     *
     * @return       Error
    */
    public static function createError(int $code, string $message, string $file, int $line): static
    {
        if (!\array_key_exists($code, self::$ERRORS)) {
            $code                   = self::ERROR;
        }

        if (\in_array($code, [E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE])) {
            return new UserError($code, $message, $file, $line);
        }

        switch (self::$ERRORS[$code]) {
            case self::EMERGENCY    :
                {
                    //
                    // EMERGENCY created as fatal error
                    //
                    $err = new Error($code, $message, $file, $line);
                    $err->markAsFatal();

                    return $err;
                }
            case self::WARNING  :
                {
                    return new Warning($code, $message, $file, $line);
                }
            case self::NOTICE   :
            case self::INFO     :
            case self::DEBUG    :
                {
                    return new Notice($code, $message, $file, $line);
                }
            case self::ALERT    :
            case self::CRITICAL :
            case self::ERROR    :
            default:
                {
                    return new Error($code, $message, $file, $line);
                }
        }
    }

    /**
     * Errors constructor.
    */
    public function __construct(protected int $code, protected string $message, protected string $file, protected int $line) {}

    #[\Override]
    public function getMessage(): string
    {
        return $this->message;
    }

    #[\Override]
    public function getPrevious(): null
    {
        return null;
    }

    #[\Override]
    public function getTags(): array
    {
        return [];
    }

    #[\Override]
    public function getCode(): int
    {
        return $this->code;
    }

    #[\Override]
    public function getFile(): string
    {
        return $this->file;
    }

    #[\Override]
    public function getLine(): int
    {
        return $this->line;
    }

    #[\Override]
    public function getTrace(): ?array
    {
        return $this->trace;
    }

    #[\Override]
    public function getTraceAsString(): string
    {
        if (empty($this->trace)) {
            return '';
        }

        return \print_r($this->trace, true);
    }

    #[\Override]
    public function isLoggable(): bool
    {
        return $this->isLoggable;
    }

    #[\Override]
    public function setLoggable(bool $flag): static
    {
        $this->isLoggable = $flag;

        return $this;
    }

    #[\Override]
    public function isFatal(): bool
    {
        return $this->isFatal;
    }

    #[\Override]
    public function markAsFatal(): static
    {
        $this->isFatal = true;

        return $this;
    }

    #[\Override]
    public function isContainer(): bool
    {
        return false;
    }

    /**
     * Returns level of error.
     */
    #[\Override]
    public function getLevel(): int
    {
        if (!\array_key_exists($this->code, self::$ERRORS)) {
            return self::ERROR;
        }

        return self::$ERRORS[$this->code];
    }

    #[\Override]
    public function getSource(): array
    {
        return ['source' => $this->getFile(), 'type' => '', 'function' => ''];
    }

    #[\Override]
    public function getPreviousException(): \Throwable|BaseExceptionInterface|null
    {
        return null;
    }

    #[\Override]
    public function getExceptionData(): array
    {
        return [];
    }

    #[\Override]
    public function getDebugData(): array
    {
        return [];
    }

    #[\Override]
    public function toArray(): array
    {
        return
        [
            'type'      => static::class,
            'source'    => $this->getSource(),
            'message'   => $this->getMessage(),
            'code'      => $this->getCode(),
            'data'      => [],
        ];
    }

    #[\Override]
    public function appendData(array $data): static
    {
        /** nothing to do */
        return $this;
    }

    #[\Override]
    public function template(): string
    {
        return '';
    }

    #[\Override]
    public function __toString(): string
    {
        return (string) \json_encode($this->toArray());
    }
}
