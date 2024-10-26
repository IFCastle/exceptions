<?php
declare(strict_types=1);

namespace IfCastle\Exceptions;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

final readonly class PsrLogAdapter implements LoggerInterface
{
    use LoggerTrait;
    
    public function __construct(private LoggerInterface $logger) {}
    
    public function log(mixed $level, \Stringable|string $message, array $context = []): void
    {
        if(false === $message instanceof BaseExceptionInterface || empty($context['exception'])) {
            $this->logger->log($level, $message, $context);
            return;
        }
        
        if(false === $message->isLoggable()) {
            return;
        }
        
        if($message->isContainer()) {
            $message                = $message->getPreviousException();
        }
        
        if(false === $message instanceof BaseExceptionInterface) {
            $context['exception']   = $message;
            $this->logger->log($level, $message->getMessage(), $context);
            return;
        }
        
        // Integration with Monolog
        $context['exception']       = $message;
        $context['tags']            = array_merge($context['tags'] ?? [], $message->getTags());
        $context['exception_data']  = $message->getExceptionData();
        $context['exception_debug'] = $message->getDebugData();
        $context['exception_template'] = $message->template();
        
        $this->logger->log($level, $message->getMessage(), $context);
    }
}