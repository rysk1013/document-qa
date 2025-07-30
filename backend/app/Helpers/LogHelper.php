<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('logInfo')) {
    /**
     * output info level log
     *
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    function logInfo(string $message, array $context = []): void
    {
        Log::channel('daily')->info($message, $context);
    }
}

if (!function_exists('logError')) {
    /**
     * output error level log
     *
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    function logError(string $message, array $context = []): void
    {
        Log::channel('daily')->error($message, $context);
    }
}
