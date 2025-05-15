<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;

class CreateJsonStdoutLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $logger = new Logger(env('LOG_CHANNEL', 'stdout_json'));

        // Create a StreamHandler for php://stdout
        $handler = new StreamHandler('php://stdout', $config['level'] ?? Logger::DEBUG);

        // Set the JsonFormatter for the handler
        // The default JsonFormatter::BATCH_MODE_JSON formats each record as a JSON object on a new line.
        $handler->setFormatter(new JsonFormatter());

        // Optional: Add a processor to handle placeholders in log messages
        $logger->pushProcessor(new PsrLogMessageProcessor());

        $logger->pushHandler($handler);

        return $logger;
    }
} 