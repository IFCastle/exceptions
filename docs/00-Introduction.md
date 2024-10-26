
# Introduction

**BaseException** is a library for exception unification in *PHP*.

**BaseException** addresses the following tasks:

1. Exception metadata.
2. Message templating.
3. Exception aspectization.
4. Exception normalization using "containers."
5. Exception registration in a global registry.

## Exception Metadata and Templating

When analyzing exceptions, it is important to have a clear understanding of what happened. For this, it is necessary to store exception metadata to assist in further analysis. This library enables the creation of exceptions with context that includes exception metadata.

Exception metadata is an associative array containing only simple data types.

## Support for Message Templates

**BaseException** supports the concept of *message templates* (starting from version 2.0). A message template is a string containing *placeholders*, which are replaced with context data values. The template format complies with [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md).

When an exception is created, data is passed to the constructor to replace *placeholders* in the message template. The constructor uses the template and generates a description of the exception based on the template and metadata.

The exception template provides several important benefits:
- Ability to translate error messages into different languages based on the template.
- Ability to group exceptions by template.

Message templating allows logging not only the resulting message but also the data used to form it:

```php
    $exception = new BaseException(['template' => 'User {user} from {ip} is not allowed', 'user' => $user, 'ip' => $ip]);
    $logger->error($exception);
```

This makes it possible to analyze the log later and understand what data was used to form the error message.
