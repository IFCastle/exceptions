
# BaseException

## Constructor

The `BaseException` constructor has three modes of operation:

1. Standard (as defined in `\Exception`).
2. Structured data mode.
3. Container mode.

Inherited exceptions can transparently support these modes or override them with their own constructor.

For example, the `UnexpectedValue` class can behave like `BaseException` if the `$name` parameter is an array.

```php
/**
 * The variable has an unexpected value!
 */
class UnexpectedValue extends LoggableException
{
    protected string $template      = 'Unexpected value {value} occurred in the variable {name}';

    /**
     * The variable has an unexpected value!
     *
     * @param string|array $name                        Variable name
     *                                                  or list of parameters for exception
     * @param mixed        $value                       Value
     * @param string|class-string|null  $rules          Rules description
     */
    public function __construct(array|string $name, mixed $value = null, string|null $rules = null)
    {
        if (!\is_scalar($name)) {
            parent::__construct($name);
            return;
        }

        parent::__construct([
            'name'        => $name,
            'value'       => $this->toString($value),
            'message'     => $rules,
            'type'        => $this->typeInfo($value),
        ]);
    }
}
```

## Message Templates

Each exception can have its own unique message template, which can be defined in the `template` property:

```php
class UnexpectedValueType extends LoggableException
{
    protected $template = 'Unexpected type occurred for the value {name} and type {type}. Expected {expected}';
```

A message template can also be passed in the exception context using the special key `template`:

```php
    $exception = new UnexpectedValueType
    ([
        'template'  => 'Custom template {name} {type} {expected}',
    ]);
```

