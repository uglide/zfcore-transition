<?php
/**
 * Created by Igor Malinovskiy <u.glide@gmail.com>.
 * ErrorHandler.php
 * Date: 20.08.12
 */
class Core_ErrorHandler
{
    const ERROR = 'error';
    const EXCEPTION = 'exception';
    const PATH_TO_ERROR_LOG = '/data/logs/errors.log';

    public static function handler()
    {
        $error = error_get_last();

        if (is_array($error)) {
            self::saveErrorInLog($error, self::ERROR);
        } elseif (isset($exception)) {
            self::saveErrorInLog($exception, self::EXCEPTION);
        }

        if (!is_array($error)
            || !in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR))
        ) {
            return;
        }

        $trace = '';

        if (APPLICATION_ENV == 'development' && isset($exception)) {
            $trace = '<br /><br /><pre>' . $exception->getMessage() . '</pre><br />'
                . '<div align="left">Stack Trace:'
                . '<pre>' . $exception->getTraceAsString() . '</pre></div>';
        }

        return self::exitWithMessage($trace);
    }

    /**
     * @static
     * @param $error
     * @param string $type
     * @return int
     */
    public static function saveErrorInLog($error, $type = Buyam_ErrorHandler::ERROR)
    {
        $trace = '';
        $file = '';
        $message = '';

        if ($type == self::ERROR && null != $error) {
            ob_start();
            debug_print_backtrace();
            $trace = ob_get_contents();
            ob_end_clean();
            $message = $error['message'];
            $file = $error['file'] . ':' . $error['line'];

        } elseif ($type == self::EXCEPTION) {
            $trace = $error->getTraceAsString();
            $message = $error->getMessage();
            $file = $error->getFile() . ':' . $error->getLine();
        }

        if (isset($_SESSION)) {
            ob_start();
            var_dump($_SESSION);
            $session = ob_get_clean();
        }

        $errorLogMessage = PHP_EOL . '---------------------------' . PHP_EOL
            . date('d-m-Y H:i:s') . PHP_EOL . 'Error in ' . $file . PHP_EOL . PHP_EOL
            . 'Error Message :' . PHP_EOL . $message . PHP_EOL . PHP_EOL
            . 'Trace :' . PHP_EOL . $trace . PHP_EOL . PHP_EOL
            . 'SERVER :' . PHP_EOL . var_export($_SERVER, true) . PHP_EOL
            . 'POST :' . PHP_EOL . var_export($_POST, true) . PHP_EOL
            . 'GET :' . PHP_EOL . var_export($_GET, true) . PHP_EOL
            . 'COOKIE :' . PHP_EOL . var_export($_COOKIE, true) . PHP_EOL
            . 'SESSION :' . PHP_EOL . ((isset($session)) ? $session : 'Session not started!') . PHP_EOL
            . '---------------------------';

        $logPath = realpath(APPLICATION_PATH . '/../') . self::PATH_TO_ERROR_LOG;

        if (APPLICATION_ENV == 'production') {
            mail(ERROR_REPORT_MAIL, ERROR_REPORT_SUBJECT, $errorLogMessage);
        }

        return file_put_contents($logPath, $errorLogMessage, FILE_APPEND | LOCK_EX);
    }

    /**
     * @static
     * @param $message
     */
    public static function exitWithMessage($message)
    {
        $errorResponse = <<< HTML
    <!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Critical Error</title>
</head>
<body>
    <h2>An exception occured while bootstrapping the application</h2>
    <p>Contact us information...</p>
    {$message}
</body>
</html>
HTML;

        return exit($errorResponse);
    }
}
