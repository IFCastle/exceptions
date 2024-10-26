
# Fatal Exception

The "Fatal Error" aspect is implemented as a property `isFatal` and a public method `isFatal()`. Notably, unlike the `isLoggable()` flag, the `isFatal` flag cannot be reset (except from within the class).

Since `isFatal` is a property, any exception can become fatal at any time:

```php
    try
    {
        ...
    }
    catch(BaseException $e)
    {
        throw $e->markAsFatal();
    }
```

A container exception can also be used to assign this characteristic to another exception:

```php
    try
    {
        ...
    }
    catch(BaseException $e)
    {
        // Now the exception $e has the fatal aspect.
        throw new FatalException($e);
    }
```

## Fatal Exception Handler

`Registry` provides the method `Registry::setFatalHandler($callback)` to handle fatal exceptions.

Handler prototype:

```php
    function(BaseExceptionI $exception)
    {
        // If this is a container, use its content
        if($exception->isContainer())
        {
            $real_exception = $exception->getPrevious();
        }
        else
        {
            $real_exception = $exception;
        }
        ...
    }
```

The fatal exception handler is called when an exception becomes fatal, specifically:

1. At the end of the `BaseException` constructor.
2. When calling the `setFatal()` method.

The fatal exception handler is not intended for logging. It is needed to implement a specific algorithm under resource-limited conditions. Possible tasks of the handler include:

- Stopping the program or service;
- Preventing worker restarts until the issue is resolved;
- Initiating a failure analysis process.

## Logging Specifics for Fatal Exceptions

The logger may also handle a fatal exception differently:

1. Check the ability to write to disk or log file.
2. If the log file is unavailable, attempt to use the system log.
3. Use EMAIL to send a notification.
4. If EMAIL fails, use alternative channels.
