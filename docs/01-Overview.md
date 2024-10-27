# Overview

# BaseException::__construct()

Basic usage:

```php
    throw new BaseException('message', 0, $previous);
```

Constructor with metadata list:

```php
    $exception = new BaseException
    ([
        'message'     => 'message',
        'code'        => 0,
        'previous'    => $previous,
        'myData'      => [1,2,3]
    ]);

    ...

    // print_r(['myData' => 1,2,3]);
    print_r($exception->getExceptionData());
```

Container exception:

```php
    try
    {
        try
        {
            throw new \Exception('test');
        }
        catch(\Exception $e)
        {
            // inherits exception data
            throw new BaseException($e);
        }
    }
    catch(BaseException $exception)
    {
        // outputs "test"
        echo $exception->getMessage();
    }
```

Container is used to change the `isLoggable` flag:

```php
    try
    {
        try
        {
            // exception that should not be logged!
            throw new BaseException('test');
        }
        catch(\Exception $e)
        {
            // log BaseException, but not LoggableException
            throw new LoggableException($e);
        }
    }
    catch(LoggableException $exception)
    {
        // output: "true"
        if($exception->getPrevious() === $e)
        {
            echo 'true';
        }
    }
```

## Inheriting from BaseException

```php
class ClassNotExist extends BaseException
{
    // This exception will be logged
    protected $isLoggable = true;
    // Exception template
    protected $template = 'Class {class} does not exist';
    // Tags for finding the exception in the log
    protected array $args = ['class'];

    /**
     * ClassNotExist
     *
     * @param string $class Class name
     */
    public function __construct($class)
    {
        parent::__construct
        (
            array
            (
                'message' => "Class '$class' does not exist",
                'class'   => $class
            )
        );
    }
}
```

## FatalException

An exception can be marked as fatal, and then this property can be used in the exception handler.

```php
class MyFatalException  extends BaseException
{
    // This exception has an aspect: "fatal"
    protected $isFatal    = true;
}
```

## Debug data

Debug data can be added to the exception and become available for analysis in the log 
if debug mode is activated.

```php
class MyException  extends BaseException
{
    public function __construct(object $object)
    {
        $this->setDebugData($object->toArray());
        
        parent::__construct('some message');
    }
}
```
