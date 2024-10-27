
# Overview

# BaseException::__construct()

Основное использование:

```php
    throw new BaseException('message', 0, $previous);
```

Конструктор со списком метаданных:

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

Контейнер используется для изменения флага `isLoggable`:

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
    // Шаблон исключения
    protected $template = 'Class {class} does not exist';
    // Теги для поиска исключения в журнале
    protected array $args = ['class'];

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

Исключение можно отметить как фатальное, а после использовать это свойство в обработчике исключений.

```php
class MyFatalException  extends BaseException
{
    // This exception has an aspect: "fatal"
    protected $isFatal    = true;
}
```

## Debug data

Отладочные данные могут быть добавлены в исключение, и стать доступными для анализа в журнале, 
если отладочный режим активирован.

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