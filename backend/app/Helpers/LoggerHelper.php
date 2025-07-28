<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('logInfo')) {
    function logInfo(string $message, array $context = []): void
    {
        Log::channel('daily')->info($message, $context);
    }
}

if (!function_exists('logError')) {
    function logError(string $message, array $context = []): void
    {
        Log::channel('daily')->error($message, $context);
    }
}
