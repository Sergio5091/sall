<?php

$logFile = __DIR__ . '/storage/logs/laravel.log';
$lines = file($logFile);
$lastLines = array_slice($lines, -50);
echo implode('', $lastLines);
