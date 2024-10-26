
# Registry

> The `Registry` and `StorageInterface` functionality is outdated and not recommended for use.

The `Registry` class is static. Although PHP does not yet support static classes like C#, the constructor call in `Registry` is prohibited.

`Registry` registers exceptions before they are logged. For this, `Registry` uses Storage, which can be overridden by the developer:

```php
    static public function set_registry_storage(StorageI $storage)
```

The `StorageInterface` interface contains only a few methods and allows influence over the exception registration process.

Additional functionality extension in `Registry` can be obtained using methods:

- `set_unhandled_handler` - unhandled exceptions.
- `set_fatal_handler` - error with `is_fatal` flag.

The `set_unhandled_handler` method is used when `Registry` is allowed to handle PHP error and exception streams. To enable this, call the `Registry::install_global_handlers()` method, after which all unhandled exceptions and errors will begin to enter the registry.

The `set_unhandled_handler` will be called after the main handler `Registry::exception_handler(\Exception $exception)`.

The `set_fatal_handler` method will be discussed in detail in "Fatal Exception."

## PHP Error Logging

`Registry` supports logging not only exceptions but also PHP error streams. For this, it uses the `Exceptions\Errors\Error` class, which, although not an exception, implements the `BaseExceptionI` interface.

All errors except `E_USER_*` are considered programmer errors and enter the general log. This also applies to `E_NOTICE` and `E_DEPRECATED`.

Thus, `Registry` requires PHP code to be completely free of any warnings.

## Unhandled Exceptions

What happens if an exception goes unhandled and enters `Registry::exception_handler()`?

```php
        if($exception instanceof BaseExceptionI)
        {
```

