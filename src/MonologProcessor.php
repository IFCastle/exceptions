<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

final class MonologProcessor implements ProcessorInterface
{
    #[\Override]
    public function __invoke(LogRecord $record)
    {
        if (false === $record->context['exception'] instanceof BaseExceptionInterface) {
            return $record;
        }

        $exception                  = $record->context['exception'];

        $record->extra['exception_template']    = $exception->getTemplate();
        $record->extra['exception_data']        = $exception->getExceptionData();
        $record->extra['exception_debug']       = $exception->getDebugData();
        $record->extra['tags']                  = \array_merge($record->extra['tags'] ?? [], $exception->getTags());

        return $record;
    }
}
