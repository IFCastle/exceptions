<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * Register of exceptions.
 *
 * This is a static class which used as global registry for exceptions.
 * It defines the internal storage for exceptions which can be redefined by a programmer.
 *
 * Really this class not log an exception.
 * It's stores them until called $save_handler.
 */
class Registry
{
    /**
     * Options for logger.
     * @var array|\ArrayAccess
     */
    public static array $LoggerOptions = [];

    /**
     * Options for debug mode.
     * @var array|\ArrayAccess
     */
    public static array $DebugOptions  = [];

    /**
     * List of exception.
     *
     * @var BaseException[]|\Exception[]|StorageInterface
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
     * @var callback
     */
    protected static $oldErrorHandler;

    /**
     * Old exception handler.
     * @var callback
     */
    protected static $oldExceptionHandler;

    /**
     * Setup global handler flag.
     */
    protected static bool $installGlobalHandlers = false;

    /**
     * List of fatal php error.
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
        if (!($exception instanceof \Throwable || $exception instanceof BaseExceptionInterface)) {
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
     * @return      BaseException[]|\Exception[]
     */
    public static function getExceptionLog(): array
    {
        if (\is_array(self::$exceptions)) {
            return self::$exceptions;
        }
        if (self::$exceptions instanceof StorageInterface) {
            $result = self::$exceptions->getStorageExceptions();
            if (!\is_array($result)) {
                return [new \UnexpectedValueException('StorageI->get_storage() return not array')];
            }
            return $result;
        }


        return [];

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
     */
    public static function getLoggerOptions(): array
    {
        if (\is_array(self::$LoggerOptions) ||
        self::$LoggerOptions instanceof \ArrayAccess) {
            return self::$LoggerOptions;
        }
        return [];
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

        if (!empty(self::$oldErrorHandler)) {
            \set_error_handler(self::$oldErrorHandler);
        } else {
            \restore_error_handler();
        }

        if (!empty(self::$oldExceptionHandler)) {
            \set_exception_handler(self::$oldExceptionHandler);
        } else {
            \restore_exception_handler();
        }
    }


    public static function exceptionHandler(\Throwable $exception): void
    {
        if ($exception instanceof BaseExceptionInterface === false) {
            self::registerException($exception);
        } elseif (!($exception->isLoggable() || $exception->isContainer())) {
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
        $error                      = \error_get_last();

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

        // if namespaces not defined - return
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
