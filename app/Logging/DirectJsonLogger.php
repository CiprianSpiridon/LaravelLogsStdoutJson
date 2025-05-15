<?php

namespace App\Logging;

use Illuminate\Support\Facades\Facade;

/**
 * A direct JSON logger that bypasses much of Laravel's logging complexity
 * to ensure compatibility with FrankenPHP.
 */
class DirectJsonLogger extends Facade
{
    /**
     * Log a debug message directly as JSON to STDERR
     */
    public static function debug($message, array $context = [])
    {
        static::log('DEBUG', $message, $context);
    }

    /**
     * Log an info message directly as JSON to STDERR
     */
    public static function info($message, array $context = [])
    {
        static::log('INFO', $message, $context);
    }

    /**
     * Log a warning message directly as JSON to STDERR
     */
    public static function warning($message, array $context = [])
    {
        static::log('WARNING', $message, $context);
    }

    /**
     * Log an error message directly as JSON to STDERR
     */
    public static function error($message, array $context = [])
    {
        static::log('ERROR', $message, $context);
    }

    /**
     * Log a critical message directly as JSON to STDERR
     */
    public static function critical($message, array $context = [])
    {
        static::log('CRITICAL', $message, $context);
    }

    /**
     * Log an alert message directly as JSON to STDERR
     */
    public static function alert($message, array $context = [])
    {
        static::log('ALERT', $message, $context);
    }

    /**
     * Log an emergency message directly as JSON to STDERR
     */
    public static function emergency($message, array $context = [])
    {
        static::log('EMERGENCY', $message, $context);
    }

    /**
     * Write a log message directly as JSON to STDERR
     */
    protected static function log($level, $message, array $context = [])
    {
        $logData = [
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'datetime' => date('c'),
            'channel' => 'direct_json'
        ];

        // Add exception data if present
        if (isset($context['exception']) && $context['exception'] instanceof \Throwable) {
            $exception = $context['exception'];
            $logData['context']['exception_class'] = get_class($exception);
            $logData['context']['exception_message'] = $exception->getMessage();
            $logData['context']['exception_file'] = $exception->getFile();
            $logData['context']['exception_line'] = $exception->getLine();
            $logData['context']['exception_trace'] = $exception->getTraceAsString();
            unset($logData['context']['exception']); // Remove the actual exception object
        }

        // Encode as JSON and write directly to STDERR
        file_put_contents('php://stderr', json_encode($logData) . PHP_EOL);
    }
} 