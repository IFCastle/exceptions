<?php

declare(strict_types=1);

namespace IfCastle\Exceptions;

/**
 * Base class for exception with an aspect: System.
 */
class SystemException extends LoggableException implements SystemExceptionInterface {}
