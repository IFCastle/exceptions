# Overview

# BaseException::__construct()

The basic use:

```php
    throw new BaseException('message', 0, $previous);
```

List of parameters:

```php
    // use array()
    $exception = new BaseException
    ([
        'message'     => 'message',
        'code'        => 0,
        'previous'    => $previous,
        'mydata'      => [1,2,3]
    ]);

    ...

    // print_r([1,2,3]);
    print_r($exception->get_data());

```

Container-Exception:

```php

    try
    {
        try
        {
            throw new \Exception('test');
        }
        catch(\Exception $e)
        {
            // inherits data Exception
            throw new BaseException($e);
        }
    }
    catch(BaseException $exception)
    {
        // out "test"
        echo $exception->getMessage();
    }

```

The container is used to change the flag `is_loggable`:

```php

    try
    {
        try
        {
            // not loggable exception!
            throw new BaseException('test');
        }
        catch(\Exception $e)
        {
            // log BaseException, but don't log LoggableException
            throw new LoggableException($e);
        }
    }
    catch(LoggableException $exception)
    {
        // echo: "true"
        if($exception->getPrevious() === $e)
        {
            echo 'true';
        }
    }

```

## Inheriting from the BaseException

```php
class ClassNotExist  extends BaseException
{
    // This exception will be logged
    protected $isLoggable = true;

    /**
     * ClassNotExist
     *
     * @param       string      $class         Class name
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

```php
class MyFatalException  extends BaseException
{
    // This exception has an aspect: "fatal"
    protected $isFatal    = true;
}
```

## Debug data

```php
class MyException  extends BaseException
{
    public function __construct($object)
    {
        $this->setDebugData($object->toArray());
        parent::__construct('its too bad!');
    }
}
```