
# Paradigm

## Exceptions and Logging

Creating exception classes is closely related to the logging methodology.

Exceptions are created with two goals:

1. To convey information about the error to the code.
2. To provide sufficient information for logging.

To accomplish the first task, classes and interfaces are used, allowing the code to determine the error type. To achieve the second goal, exception metadata is used, allowing exceptions to be logged with additional information.

Therefore, when creating a new exception class, it is essential to consider that it should contain metadata used for logging.

```php
final class UserNotAllowed extends \IfCastle\Exceptions\BaseException
{
    protected string $template      = 'User {user} from {ip} is not allowed';
    protected array $tags           = ['user', 'auth'];

    public function __construct(string $user, string $ip)
    {
        parent::__construct([
            'user' => $user,
            'ip'   => $ip
        ]);
    }
}
```

In this example, the `UserNotAllowed` class contains a message template and tags used for logging. When this exception is logged, the logger can use the message template and tags to form an error message.

```php
    $exception = new UserNotAllowed('admin', '127.0.0.1');
    $logger->error($exception);
```

If you use OpenTelemetry as your logging system, the exception metadata will be used to form attributes that will be included in the tracer along with tags and the message template.

## Exceptions and Tags

Tags allow grouping exceptions in an arbitrary manner.

When creating an exception class, you can define tags that will later be included in the logger.

```php
final class MyException extends \IfCastle\Exceptions\BaseException
{
    protected array $tags = ['system', 'service'];
}
```

You can also pass tags in the exception constructor.

```php
    $exception = new MyException();
```
