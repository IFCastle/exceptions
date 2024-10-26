
# Overview

# BaseException::__construct()

Основное использование:

```php
    throw new BaseException('message', 0, $previous);
```

Список параметров:

```php
    // использование array()
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

Исключение-контейнер:

```php
    try
    {
        try
        {
            throw new \Exception('test');
        }
        catch(\Exception $e)
        {
            // наследует данные исключения
            throw new BaseException($e);
        }
    }
    catch(BaseException $exception)
    {
        // вывод "test"
        echo $exception->getMessage();
    }
```

Контейнер используется для изменения флага `is_loggable`:

```php
    try
    {
        try
        {
            // исключение, не подлежащее журналированию!
            throw new BaseException('test');
        }
        catch(\Exception $e)
        {
            // журналируем BaseException, но не LoggableException
            throw new LoggableException($e);
        }
    }
    catch(LoggableException $exception)
    {
        // вывод: "true"
        if($exception->getPrevious() === $e)
        {
            echo 'true';
        }
    }
```

## Наследование от BaseException

```php
class ClassNotExist extends BaseException
{
    // Это исключение будет журналироваться
    protected $isLoggable = true;

    /**
     * ClassNotExist
     *
     * @param string $class Имя класса
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
class MyFatalException extends BaseException
{
    // Это исключение имеет аспект "fatal"
    protected $isFatal = true;
