<?php
defined('FLATPAGE') || die;

namespace FlatPage\Core\Debug;

use DirectoryIterator;
use ErrorException;
use Throwable;

/**
 * Debug Class
 */
class ErrorHandler
{
    private $status = array(
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // RFC 2324
        419 => 'Authentication Timeout', // not in RFC 2616
        420 => 'Method Failure', // Spring Framework
        420 => 'Enhance Your Calm', // Twitter
        422 => 'Unprocessable Entity', // WebDAV; RFC 4918
        423 => 'Locked', // WebDAV; RFC 4918
        424 => 'Failed Dependency', // WebDAV; RFC 4918
        424 => 'Method Failure', // WebDAV)
        425 => 'Unordered Collection', // Internet draft
        426 => 'Upgrade Required', // RFC 2817
        428 => 'Precondition Required', // RFC 6585
        429 => 'Too Many Requests', // RFC 6585
        431 => 'Request Header Fields Too Large', // RFC 6585
        444 => 'No Response', // Nginx
        449 => 'Retry With', // Microsoft
        450 => 'Blocked by Windows Parental Controls', // Microsoft
        451 => 'Unavailable For Legal Reasons', // Internet draft
        451 => 'Redirect', // Microsoft
        494 => 'Request Header Too Large', // Nginx
        495 => 'Cert Error', // Nginx
        496 => 'No Cert', // Nginx
        497 => 'HTTP to HTTPS', // Nginx
        499 => 'Client Closed Request', // Nginx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates', // RFC 2295
        507 => 'Insufficient Storage', // WebDAV; RFC 4918
        508 => 'Loop Detected', // WebDAV; RFC 5842
        509 => 'Bandwidth Limit Exceeded', // Apache bw/limited extension
        510 => 'Not Extended', // RFC 2774
        511 => 'Network Authentication Required', // RFC 6585
        598 => 'Network read timeout error', // Unknown
        599 => 'Network connect timeout error', // Unknown
    );

    protected $config;

    protected $templ = true;

    public function __construct(Bool $config)
    {
        $this->ob_level = ob_get_level();
        $this->config = $config;

        if ($this->config) {
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', '0');
        }
    }

    public function start()
    {
        set_exception_handler([$this, 'exceptionHandler']);
        set_error_handler([$this, 'errorHandler']);
        register_shutdown_function([$this, 'shutdownHandler']);
    }

    public function exceptionHandler(Throwable $exception)
    {
        // Log 
        //$this->logs();

        $this->render($exception);
    }

    public function errorHandler(int $severity, string $message, string $file = null, int $line = null)
    {
        if (!(error_reporting() & $severity)) {
            return;
        }

        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    public function shutdownHandler()
    {
        $error = error_get_last();

        if (!is_null($error)) {
            // Fatal Error?
            if (in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE], true)) {
                $this->exceptionHandler(new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
            }
        }
    }

    protected function render(Throwable $e)
    {
        // Determine the views
        $view = $this->determineView($e->getCode(), $this->config);

        //implementar plantillas para los temas
        /*if (! isset($view)) {
            echo 'The error view files were not found. Cannot render exception trace.';
            exit(1);
        }*//*

        if (ob_get_level() > $this->ob_level + 1) {
            ob_clean();
        }

        //llamar a la vista del errorhandler
        /*echo(function () use ($e,$view): string {
            $vars = $this->collectVars($e);
            extract($vars);

            ob_start();
            include FP_PATH."admin/$view.php";
            return ob_get_clean();
        })();*/

        if (ob_get_length()) ob_end_clean();
        $vars = $this->collectVars($e);
        extract($vars);
        include FP_PATH . "admin/$view.php";
        ob_flush();
        return ob_end_clean();
    }

    protected function collectVars(Throwable $e): array
    {
        $data = [
            //'title'   => get_class($e),
            //'code'    => $e->getCode(),
            'message' => $e->getMessage() ?? '(null)',
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'a_trace'   => $e->getTrace(),
            's_trace'   => $e->getTraceAsString(),
        ];

        if ($e instanceof ErrorException) {
            switch ($e->getSeverity()) {
                case E_ERROR: // 1
                    $data['type'] = 'E_ERROR';
                    break;
                case E_WARNING: // 2
                    $data['type'] = 'E_WARNING';
                    break;
                case E_PARSE: // 4
                    $data['type'] = 'E_PARSE';
                    break;
                case E_NOTICE: // 8
                    $data['type'] = 'E_NOTICE';
                    break;
                case E_CORE_ERROR: // 16
                    $data['type'] = 'E_CORE_ERROR';
                    break;
                case E_CORE_WARNING: // 32
                    $data['type'] = 'E_CORE_WARNING';
                    break;
                case E_COMPILE_ERROR: // 64
                    $data['type'] = 'E_COMPILE_ERROR';
                    break;
                case E_COMPILE_WARNING: // 128
                    $data['type'] = 'E_COMPILE_WARNING';
                    break;
                case E_USER_ERROR: // 256
                    $data['type'] = 'E_USER_ERROR';
                case E_USER_WARNING: // 512
                    $data['type'] = 'E_USER_WARNING';
                    break;
                case E_USER_NOTICE: // 1024
                    $data['type'] = 'E_USER_NOTICE';
                    break;
                case E_STRICT: // 2048
                    $data['type'] = 'E_STRICT';
                    break;
                case E_RECOVERABLE_ERROR: // 4096
                    $data['type'] = 'E_RECOVERABLE_ERROR';
                    break;
                case E_DEPRECATED: // 8192
                    $data['type'] = 'E_DEPRECATED';
                    break;
                case E_USER_DEPRECATED: // 16384
                    $data['type'] = 'E_USER_DEPRECATED';
                    break;
            }
        } else {
            $data['type'] = get_class($e);
        }

        return $data;
    }

    protected function determineView(Int $code, bool $env = false)
    {
        foreach (new DirectoryIterator(FP_THEMES . env('template')) as $fileinfo) {
            if ($fileinfo->getFilename() == '404.php' && $fileinfo->getFilename() == 'error.php') {
                $this->templ = false;
            }
        }

        // Error 404
        if ($code == 404) return '404';

        return (!$env) ? 'error' : 'debug';
    }

    public function logs()
    {
                        // implementar para q no registre los errores 404
        if (env('logs')/* && !in_array($statusCode, $this->config->ignoreCodes, true)*/) {
            //implementar logs function
            /*log_message('critical', $exception->getMessage() . "\n{trace}", [
                'trace' => $exception->getTraceAsString(),
            ]);*/
        }
    }
}
