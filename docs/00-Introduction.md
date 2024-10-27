
# Introduction

**BaseException** - a library for exception unification in *PHP*.

**BaseException** is designed to address the following tasks:

1. Exception metadata.
2. Message templating.
3. Exception aspecting.
4. Exception normalization using "containers."
5. Registration of exceptions in a global registry.

## Exception Metadata and Templating

When analyzing exceptions, it is important to have a clear understanding of what happened.
To support this, it is necessary to store exception metadata that can assist in further analysis.
This library enables the creation of exceptions with a context that includes metadata.

Exception metadata is an associative array,
with elements that can only be simple data types.

Example of metadata in an exception:
```php
throw new BaseException('Error occurred', [
    'code' => 123,
    'data' => [
        'userId' => 42,
        'action' => 'update'
    ]
]);
```

## Message Template Support

**BaseException** includes support for *message templates* (starting from version 2.0).
A message template is a string with *placeholders* that are replaced by context data values from the exception.
The template format adheres to the [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) 
specification.

Example of message templating in use:
```php
throw new BaseException('User {userId} failed to {action}', [
    'userId' => 42,
    'action' => 'update'
]);
```

## Exception Aspecting

**Aspecting** provides a mechanism to add additional processing or behavior to exceptions without altering core exception handling logic.
This feature allows you to define how exceptions are logged, processed, or displayed with more flexibility.

For example, using aspects, you could log exceptions to different logging services or apply custom processing:
```php
$aspect = new LoggingAspect();
$aspect->process($exception);
```

## Exception Containers and Normalization

**BaseException** introduces the concept of "containers" for ensuring uniform structure.
Containers help to normalize exceptions, creating a standard format that supports centralized handling.
With containers, you can wrap exceptions into a predictable format.

Example with containers:
```php
$container = new ExceptionContainer($exception);
echo $container->toJson();
```

## Global Exception Registry

A global registry keeps track of registered exceptions throughout the system.
This facilitates consistent exception handling and helps in maintaining a standard for all exceptions across the application.

Example of registering an exception globally:
```php
ExceptionRegistry::register(new CustomException());
```