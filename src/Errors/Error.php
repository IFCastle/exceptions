<?php

declare(strict_types=1);

namespace IfCastle\Exceptions\Errors;

use IfCastle\Exceptions\BaseExceptionInterface;

/**
 * The class for encapsulating of PHP Errors
 * as object BaseExceptionInterface.
 */
class Error extends \ErrorException implements BaseExceptionInterface
{
    
    /**
     * Conformity between PHP-errors and BaseException
     * @var array<int, int>
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
    
    /**
     * @var mixed[]|null
     */
    protected ?array $trace = null;

    /**
     * Loggable flag.
     */
    protected bool $isLoggable      = true;

    /**
     * Fatal error flag.
     */
    protected bool $isFatal         = false;
    
    /**
     * @param array<string, scalar>|null $error
     *
     * @return BaseExceptionInterface|null
     */
    public static function createFromLastError(?array $error = null): ?BaseExceptionInterface
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
     * @param        int    $severity Class of error
     * @param        string $message  Message
     * @param        string $file     File
     * @param        int    $line     Line
     *
     * @return       BaseExceptionInterface
    */
    public static function createError(int $severity, string $message, string $file, int $line): BaseExceptionInterface
    {
        if (!\array_key_exists($severity, self::$ERRORS)) {
            $severity               = self::ERROR;
        }

        if (\in_array($severity, [E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE])) {
            return new UserError($severity, $message, $file, $line);
        }

        switch (self::$ERRORS[$severity]) {
            case self::EMERGENCY    :
                {
                    //
                    // EMERGENCY created as fatal error
                    //
                    $err = new Error($severity, $message, $file, $line);
                    $err->markAsFatal();

                    return $err;
                }
            case self::WARNING  :
                {
                    return new Warning($severity, $message, $file, $line);
                }
            case self::NOTICE   :
            case self::INFO     :
            case self::DEBUG    :
                {
                    return new Notice($severity, $message, $file, $line);
                }
            case self::ALERT    :
            case self::CRITICAL :
            case self::ERROR    :
            default:
                {
                    return new Error($severity, $message, $file, $line);
                }
        }
    }
    
    public function __construct(int        $severity = \E_ERROR,
                                string     $message = '',
                                ?string    $filename = null,
                                ?int       $line = null,
                                int        $code = 0,
                                ?\Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $severity, $filename, $line, $previous);
    }
    
    #[\Override]
    public function getTags(): array
    {
        return [];
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
        if (!\array_key_exists($this->getSeverity(), self::$ERRORS)) {
            return self::ERROR;
        }

        return self::$ERRORS[$this->getSeverity()];
    }
    
    /**
     * @return array{source: string, type: string, function: string}
     */
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
    public function getTemplate(): string
    {
        return '';
    }

    #[\Override]
    public function __toString(): string
    {
        return (string) \json_encode($this->toArray());
    }
}
