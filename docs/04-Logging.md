
# Logging

## Logging Process

> The `Registry` and `StorageInterface` functionality is outdated and not recommended for use.

Logging occurs in two stages:

1. The exception is stored in the registry (`Registry`).
2. The registry forwards the exceptions to the log.

To achieve this, the `BaseException` code calls `Registry::register_exception(…)`, which, in turn, sends the exception to the global exception storage:

```php
    static public function register_exception($exception)
    {
        ...
        self::$exceptions->add_exception($exception);
        ...
    }
```

This can be represented in the scheme:

    __construct -> Registry::register_exception -> StorageI

where:

- `__construct` - the exception constructor (regardless of type).
- `Registry` - static class.
- `StorageInterface` - the object that actually stores exceptions.

Where is the logger here? That's right - it isn't here, and it shouldn't be. Logging functionality itself is not related to the `BaseException` library.

The only integration point is the method: `Registry::save_exception_log()`.

This method is called when the exception registry can be saved. When exactly? It depends on the situation, such as when the program completes. This complex method does the following:

```php
    static public function save_exception_log()
    {
        if(is_callable(self::$save_handler))
        {
            call_user_func(self::$save_handler, …);
        }
    }
```

The method calls the registered handler `$save_handler`, which is set by the `Registry::set_save_handler($callback)` method. This handler is responsible for the actual logging of exceptions.

## Proposed Logger Algorithm

Although `BaseException` cannot influence the logger's implementation, there is some expected behavior the logger should follow.
