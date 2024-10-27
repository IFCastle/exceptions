# BaseException

## Constructor

The `BaseException` constructor has three operating modes:

1. Standard (as defined in `\Exception`).
2. Structured data mode.
3. Container mode.

Inherited exceptions can either transparently support these modes or override them with their own constructor.

For example, the `UnexpectedValue` class can behave like `BaseException` 
if the `$name` parameter is an array.

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

Each exception can have its unique message template, which can be defined in the `template` property:

```php
class UnexpectedValueType   extends LoggableException
{
    protected $template         = 'Unexpected type occurred for the value {name} and type {type}. Expected {expected}';
```

The message template can also be passed in the exception context using the special key `template`:

```php
    $exception = new UnexpectedValueType
    ([
        'template'  => 'Custom template {name} {type} {expected}',
        'name'      => 'test',
        'type'      => 'string',
        'expected'  => 'integer'
    ]);
```

The template string format follows the rules of [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md).

If the exception defines a template, then the `BaseException` constructor works as follows:

1. Template *placeholders* are replaced with data from the context and passed to the `\Exception` constructor.
Thus, the `getMessage()` method will return the full error text.
2. If the `message` property is passed to the `BaseException` constructor,
it is treated as an additional message and is appended to the resulting message with a period. For example:

```php
        $exception                  = new BaseException
        ([
            'template'              => 'This is the template',
            'message'               => 'This is a message'
        ]);
        
        echo $exception->getMessage();

// Result:
// This is the template. This is a message
```

## Logging Control

The `BaseException` class has two flags that control logging:
* `isLoggable` - flag indicating that the exception should be written to the log.
* `isFatal` - flag indicating that the exception is fatal.

How these flags will be used depends on the logging implementation, but the general rules are:
1. If the `isLoggable` flag is not set, the exception will not be written to the log.
2. If the exception is fatal, it will be written to the log.
3. If the exception is not fatal but the `isLoggable` flag is set, it will be written to the log.

Fatal exceptions can be handled in a special way, but this depends on the implementation.

## Inheritance

In most cases, a child class only overrides the constructor because it needs to form the exception data.

If a child class needs to change the exception behavior, it can modify inherited properties.
In this case - this behavior is normal.

Simplified algorithm of `BaseException::__construct`:

1. Analyze input data.
2. Call parent constructor `\Exception`.
3. If `BaseException::isLoggable` finalize the exception.
4. If `BaseException::isFatal` call fatal exception handler.

The constructor can modify any properties after its call.
Therefore, if you need to modify class properties finally, do it after calling the base constructor.

Changing flag properties (with prefix `is`) causes changes in base constructor behavior.

For example, this is how you can enable logging:

```php
    class LoggableException extends BaseException
    {
        /**
         * Logging flag.
         * If the flag is true - then the exception
         * is going to be written to the log.
         *
         * @var         boolean
         */
        protected $isLoggable  = true;
    }
```

And this exception is fatal:

```php
    class MyException extends BaseException
    {
        protected $isFatal  = true;
    }
```

The `BaseException` class is not logged and has no aspects.
Therefore, for inheritance, it's convenient to have additional "base exceptions":

1. `LoggableException` - for exceptions that are logged.
2. `SystemException` - for exceptions with the "system" aspect.
3. `RuntimeException` - for exceptions with the "runtime" aspect.
4. `FatalException`, `FatalRuntimeException`, `FatalSystemException` - for fatal exceptions.

(more about moralization in the section: [Logging][1])

## Data Formation Recommendations

Note that the `BaseException` constructor does not modify the data you pass to it.
Child exceptions must do this work.

It's good to follow these recommendations:

1. Don't store objects in the exception.
2. When forming `data`, truncate the data.
3. Save only the most important and necessary.
4. Use only basic types in the `data` array: scalars and arrays.

### Don't Store Objects in the Exception

Exceptions can end up in the log and stay in memory much longer than objects passed to the exception.
Thus, you will be inflating the application as the garbage collector won't be able to free memory.
While this may not be relevant for Web scripts, it probably is for background tasks.

### Data Truncation

The `BaseException::data` property goes into the log.
Although data that goes into the log maintains structure and can be deserialized again,
you shouldn't use large data arrays.
This not only slows down the log processing but also creates unnecessary redundancy.
If you really need to save large data arrays, it's better to use debug mode.

For data truncation, you can use methods:

- `BaseException::getType()`;
- `BaseException::truncate()`.

### Using Basic Types in data

This requirement has, on one hand, the same meaning as "Don't store objects in the exception".
But the main reason is different. The `data` property may participate in the exception serialization process.
And objects can't always be properly serialized/deserialized.
Although you can use the `Serializable` interface - it's not the best idea,
as the serialization target might be other formats, different in essence from `Serializable`.

If you want to get the ability to easily serialize exceptions - try to strictly follow this rule.

## Debug Data

For saving debug data,
`BaseException` provides the `set_debug_data()` method.
This method has a `protected` attribute, meaning it's intended for internal use only.

Algorithm of `BaseException::setDebugData()`:

1. Check if debug mode is active.
2. If yes, convert data to string and save it.

Usage example:

```php
    class UnexpectedValue   extends LoggableException
    {
        public function __construct($name, $value = null)
        {
            // For debug log we'll save full data:
            $this->setDebugData(['value' => $value]);

            parent::__construct
            ([
                    'message'     => 'Unexpected value',
                    'name'        => $name,
                    'type'        => self::getValueType($value)
            ]);
        }
    }
```

Using the `isDebug` property, you can deliberately write data regardless of whether debug mode is enabled or not:

```php
    class UnexpectedValue   extends LoggableException
    {
        public function __construct($name, $value = null)
        {
            // Force enable debug mode
            $this->isDebug      = true;
            // For debugger we'll save full data:
            $this->setDebugData(['value' => $value]);

            parent::__construct
            ([
                    'message'     => 'Unexpected value',
                    'name'        => $name,
                    'type'        => self::get_value_type($value)
            ]);            
        }
    }
```

In this case, debug data will be written only for your exception.
This allows you to quickly introduce debug mode even on a production system, only where necessary.

Similarly, you can disable debug mode:

```php
    // All exceptions that inherit from this
    // will get debug mode disabled
    // (unless they explicitly override it)
    class NoDebugException   extends LoggableException
    {
        protected $isDebug = false;
    }
```

## Serialization

Besides the main methods, `BaseException` supports serialization/deserialization.
This can be useful for marshaling exceptions through remote services,
saving exceptions to disk, and other cases where serialization is typically used.

The target serialization format is an associative array.
An array is chosen because it's easy to convert to any other format: `json`, `xml`, `phpserialize`, etc.

Array format:

```php
    [
        'type'      => get_class($exception),
        'source'    => ['source' => '', 'type' => '', 'function' => ''],
        'message'   => $exception->getMessage(),
        'template'	=> $exception->template(),
        'code'      => $exception->getCode(),
        'data'      => $exception->get_data(),
        'container' => 'if this is a container exception'
    ];
```

Container exceptions return serialization not of themselves but of the `previous` exception.

General serialization/deserialization is handled by methods:

- `BaseException::toArray()`;
- `BaseException::arrayToErrors()`.

The arrayToErrors method is protected and intended for use in child classes only in case
if the exception can be restored from serialized data.
Usually such exceptions are DTO objects, which is not a common case.

## Features

The following `BaseException` methods exist because standard `PHP` `\Exception` methods are declared as `final`,
but their behavior does not correspond to the general `BaseException` paradigm.

### Determining Exception Source

To determine the exception source, a special method `BaseException::get_source()` is used. It returns an associative array of the form:

```php
    [
        'source'    => 'class or file:line where the exception occurred',
        'type'      => 'call type',
        'function'  => 'function or method name where the exception occurred'
    ];
```

The `BaseException::getSource()` method is used during logging instead of the method
`BaseException::getFile()`,
because determining the source by class name is a more natural method relative to language design.
After introducing `namespace` and `autoload` the real file structure no longer has much significance,
and namespace information is preserved even when all code is combined into one file.

Note that the order of keys in the `source` array matters, and it is created in such a way that if you merge the array into a string, you get a string source indication:

```php
    namespace Test;
        
    class ZClass
    {
        function zfun()
        {
            throw new BaseException('...');
        }
    }

    try
    {
        $Test = new ZClass();
        $Test->zfun(); 
    }
    catch(BaseException $e)
    {
        // out: Test\ZClass->zfun
        echo implode('', $exception->getSource());        
    }
```

To calculate the source of `PHP` `\Exception` classes, use the `HelperTrait::getSourceFor()` method.

### Determining Nested Exception

`BaseException` creates containers for any objects that support the `BaseExceptionInterface` interface.
This technique allows wrapping other objects in exceptions that have similar behavior but are not `PHP` exceptions.

However, `\Exception::$previous` can only be a child object of `\Exception`. To bypass this limitation, `BaseException` places the `BaseExceptionI` object in the `data` property (in the container this property is not used for data).

To access the nested `BaseExceptionI` object, use the `BaseExceptionI::get_previous()` method, which will always return the correct object:

- `\Exception`;
- or `BaseExceptionI`;
- or `null` if there is no object.

[1]: 04-Logging.md "Logging"
