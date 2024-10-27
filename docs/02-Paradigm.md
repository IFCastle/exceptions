# Paradigm

## Exceptions and Logging

Creating exception classes is closely related to logging methodology.

Exceptions are created for two purposes:

1. To transmit information about an error that occurred for the code.
2. To provide sufficient information for logging.

To solve the first task, classes and interfaces are used, allowing the code to determine what happened based on the exception type.
To solve the second task, exception metadata is used, which allows logging exceptions with additional information.

Therefore, when creating a new exception class, it is necessary to consider 
that it should contain metadata that will be used for logging.

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
```

In this example, the `UserNotAllowed` class contains a message template and tags that will be used for logging.
When this exception reaches the logger, it can use the message template and tags to form an error message.

```php
    $exception = new UserNotAllowed('admin', '127.0.0.1');
    $logger->error($exception);
```

If you use OpenTelemetry as your logging system, 
in this case, the exception metadata will be used to form attributes
that will go to the tracer along with tags and message template.

## Exceptions and Tags

Tags allow you to group exceptions in an arbitrary way.

When creating an exception class, you can define tags that will later go to the logger.

```php
final class MyException extends \IfCastle\Exceptions\BaseException
{
    protected array $tags = ['system', 'service'];
}
```

You can also pass tags in the exception constructor.

```php
    $exception = new MyException(['tags' => ['custom', 'tag']]);
    $logger->error($exception);
```

Tags added in the constructor will be merged with tags defined in the exception class.

## Exceptions and Aspects

Sometimes it's necessary to separate different exceptions by special handling types. 
For example, some exceptions can be shown to the user, while others cannot.

`BaseException` offers a special interface for this: `ClientAvailableInterface`, 
which indicates that the exception can be shown to the user.

The interface also contains additional methods:
* `getClientMessage`
* `clientSerialize`

Which allow getting a message for the user and serializing the exception for the client in a special way, 
while the exception metadata will be written to the log.

In addition to the `ClientAvailableInterface` aspect, `BaseException` also offers such aspects:
* `SystemExceptionInterface` - an exception that occurred due to a system error, for example, disk is full.
* `RuntimeExceptionInterface` - an exception that happened during program execution but is not a programmer's error.

All other exceptions are considered programmer errors and should not be shown to the user.
