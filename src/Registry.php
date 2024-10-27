<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use PHPUnit\Event\Code\Throwable;

/**
 * Register of exceptions.
 *
 * This is a static class used as global registry for exceptions.
 * It defines the internal storage for exceptions which can be redefined by a programmer.
 *
 * Really, this class does not log an exception.
 * It stores them until called $saveHandler.
 */
class Registry
{
    /**
     * Flag for global handler.
     * @var bool
     */
    public static bool $isActive = false;
    
    /**
     * Options for logger.
     * @var array<string, scalar|scalar[]>
     */
    public static array $LoggerOptions = [];

    /**
     * Options for debug mode.
     * @var array<string, scalar|scalar[]>
     */
    public static array $DebugOptions  = [];

    /**
     * List of exception.
     *
     * @var BaseExceptionInterface[]|\Throwable[]|StorageInterface
     */
    protected static array|StorageInterface $exceptions = [];

    /**
     * Handler which called from save_exception_log.
     */
    protected static ?SaveHandlerInterface $saveHandler = null;

    /**
     * Handler for unhandled exception.
     */
    protected static ?HandlerInterface $unhandledHandler = null;

    /**
     * Handler called for fatal exception.
     */
    protected static ?HandlerInterface $fatalHandler = null;

    /**
     * Old error handler.
     * @var ?callable(int $code, string $message, string $file, int|string $line): bool
     */
    protected static mixed $oldErrorHandler = null;

    /**
     * Old exception handler.
     * @var callable(\Throwable): void
     */
    protected static mixed $oldExceptionHandler = null;

    /**
     * Setup global handler flag.
     */
    protected static bool $installGlobalHandlers = false;

    /**
     * List of fatal php error.
     * @var int[]
     */
    protected static array $FATAL = [\E_ERROR, \E_PARSE, \E_CORE_ERROR, \E_COMPILE_ERROR];

    final private function __construct() {}

    /**
     * Registered exception.
     *
     * This method may be used with set_exception_handler()
     *
     * @param BaseExceptionInterface|\Throwable $exception
     *
     */
    public static function registerException(mixed $exception): void
    {
        if (false === $exception instanceof \Throwable) {
            return;
        }

        if (\is_array(self::$exceptions)) {
            self::$exceptions[] = $exception;
        } elseif (self::$exceptions instanceof StorageInterface) {
            self::$exceptions->addException($exception);
        }
    }

    /**
     * Returns the list of exception.
     *
     * @return      BaseException[]|\Throwable[]
     */
    public static function getExceptionLog(): array
    {
        if (\is_array(self::$exceptions)) {
            return self::$exceptions;
        } else {
            return self::$exceptions->getStorageExceptions();
        }
    }

    /**
     * Resets exception storage.
     */
    public static function resetExceptionLog(): void
    {
        if (self::$exceptions instanceof StorageInterface) {
            self::$exceptions->resetStorage();
        } else {
            self::$exceptions = [];
        }
    }

    /**
     * Saves registry exceptions to log.
     */
    public static function saveExceptionLog(): void
    {
        if (self::$saveHandler instanceof SaveHandlerInterface) {
            self::$saveHandler->saveExceptions(
                (self::$exceptions instanceof StorageInterface) ? self::$exceptions->getStorageExceptions() : self::$exceptions,
                self::resetExceptionLog(...),
                self::$LoggerOptions,
                self::$DebugOptions
            );
        }
    }

    /**
     * Setup custom storage for exceptions.
     *
     * @param       StorageInterface $storage Custom storage
     *
     * @return      ?StorageInterface                   returns older storage if exists
     */
    public static function setRegistryStorage(StorageInterface $storage): ?StorageInterface
    {
        $old = self::$exceptions;

        self::$exceptions = $storage;

        return $old instanceof StorageInterface ? $old : null;
    }

    /**
     * Setup save handler.
     *
     * @param       ?SaveHandlerInterface $handler Handler
     *
     * @return      SaveHandlerInterface|null           Returns old handler if exists
     */
    public static function setSaveHandler(?SaveHandlerInterface $handler = null): ?SaveHandlerInterface
    {
        $old = self::$saveHandler;

        self::$saveHandler = $handler;

        return $old;
    }

    public static function setUnhandledHandler(?HandlerInterface $handler = null): ?HandlerInterface
    {
        $old                        = self::$unhandledHandler;

        self::$unhandledHandler     = $handler;

        return $old;
    }

    public static function setFatalHandler(?HandlerInterface $handler = null): HandlerInterface|null
    {
        $old                        = self::$fatalHandler;

        self::$fatalHandler         = $handler;

        return $old;
    }

    /**
     * Invokes the handler if there is.
     *
     */
    public static function callFatalHandler(?BaseExceptionInterface $exception = null): void
    {
        if (self::$fatalHandler instanceof HandlerInterface) {
            self::$fatalHandler->exceptionHandler($exception);
        }
    }

    /**
     * Return list of logger options.
     * @return      array<string, scalar|scalar[]>
     */
    public static function getLoggerOptions(): array
    {
        return self::$LoggerOptions;
    }

    /**
     * Registers three default handlers:
     *
     * 1.  shutdown_function
     * 2.  error_handler
     * 3.  exception_handler
     *
     */
    public static function installGlobalHandlers(): void
    {
        if (self::$installGlobalHandlers) {
            return;
        }

        \register_shutdown_function(self::shutdownFunction(...));
        self::$oldErrorHandler        = \set_error_handler(self::errorHandler(...));
        self::$oldExceptionHandler    = \set_exception_handler(self::exceptionHandler(...));
        self::$installGlobalHandlers  = true;
    }

    /**
     * Restores default handlers.
     *
     */
    public static function restoreGlobalHandlers(): void
    {
        if (!self::$installGlobalHandlers) {
            return;
        }

        self::$installGlobalHandlers  = false;

        if (self::$oldErrorHandler !== null) {
            \set_error_handler(self::$oldErrorHandler);
        } else {
            \restore_error_handler();
        }

        if (self::$oldExceptionHandler !== null) {
            \set_exception_handler(self::$oldExceptionHandler);
        } else {
            \restore_exception_handler();
        }
    }


    public static function exceptionHandler(\Throwable $exception): void
    {
        if ($exception instanceof BaseExceptionInterface === false) {
            self::registerException($exception);
        } elseif (!$exception->isLoggable() && !$exception->isContainer()) {
            // When exception reaches this handler
            // its not logged if:
            // - already was logged
            // - or is container
            $exception->setLoggable(true);
            self::registerException($exception);
        }

        new UnhandledException($exception);

        if (self::$unhandledHandler instanceof HandlerInterface) {
            self::$unhandledHandler->exceptionHandler($exception);
        }
    }

    /**
     * The method for set_error_handler.
     *
     *
     */
    public static function errorHandler(int $code, string $message, string $file, int|string $line): bool
    {
        self::registerException(
            Errors\Error::createError($code, $message, $file, (int) $line)
        );

        /* Don't execute PHP internal error handler */
        return true;
    }

    public static function fatalErrorHandler(): void
    {
        // !Warning! You should not call error_get_last in root namespace!
        $error                      = error_get_last();

        if (!\is_array($error) || !\in_array($error['type'], self::$FATAL)) {
            return;
        }

        self::errorHandler($error['type'], $error['message'], $error['file'], $error['line']);
    }

    public static function shutdownFunction(): void
    {
        self::fatalErrorHandler();
        self::saveExceptionLog();
    }

    /**
     * Returns true if debug mode was enabled.
     *
     * @param       ?string      $class          name of class or namespace
     */
    public static function isDebug(?string $class = null): bool
    {
        // If global debug mode on - return true.
        if (isset(self::$DebugOptions['debug']) && self::$DebugOptions['debug']) {
            return true;
        }

        // if namespaces aren't defined - return
        if (\is_null($class) || empty(self::$DebugOptions['namespaces'])) {
            return false;
        }

        // Searching for matches
        foreach (self::$DebugOptions['namespaces'] as $namespace) {
            if (\str_starts_with($class, (string) $namespace)) {
                return true;
            }
        }

        return false;
    }
}
