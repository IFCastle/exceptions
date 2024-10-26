<?php

declare(strict_types=1);

$config = new IfCastle\CodeStyle\Config;
$config->getFinder()
    ->in(__DIR__ . "/src")
    ->in(__DIR__ . "/tests");

$config->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');

return $config;
