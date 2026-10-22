
<?php

class Logger
{
    public static function error(Throwable $e): void
    {
        $logDirectory = dirname(__DIR__, 2) . '/storage/logs';
        $logFile = $logDirectory . '/app.log';

        if (!is_dir($logDirectory)) {
            mkdir($logDirectory, 0755, true);
        }

        $message = sprintf(
            "[%s] ERROR: %s in %s:%d%s",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            PHP_EOL
        );

        error_log($message, 3, $logFile);
    }
}
