<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestLogController extends Controller
{
    /**
     * Trigger various log messages for testing.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function triggerLogs(Request $request)
    {
        Log::debug('This is a debug message.', ['extra_info' => 'debug_details', 'user_id' => 123]);
        Log::info('This is an info message.', ['source' => 'test_endpoint']);
        Log::warning('This is a warning message.', ['ip_address' => $request->ip()]);
        Log::error('This is an error message.', ['error_code' => 5001]);
        Log::critical('This is a critical message.', ['component' => 'payment_gateway']);
        Log::alert('This is an alert message.', ['system_status' => 'unstable']);
        Log::emergency('This is an emergency message.', ['message' => 'System is down!']);

        // Test exception logging
        try {
            throw new \Exception('This is a test exception from TestLogController.');
        } catch (\Exception $e) {
            Log::error('An exception was caught and logged.', [
                'exception_message' => $e->getMessage(),
                'exception_trace' => $e->getTraceAsString(), // Explicitly add trace for clarity in this example
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        return response()->json([
            'message' => 'Test log messages have been triggered, including an exception.',
            'log_levels_tested' => ['debug', 'info', 'warning', 'error', 'critical', 'alert', 'emergency'],
            'exception_tested' => true
        ]);
    }
} 