<?php
declare(strict_types=1);

use Rector\Config\RectorConfig;
use IfCastle\CodeStyle\Rector\RectorConfigurator;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);
    
    (new RectorConfigurator)->configureSets($rectorConfig);
};