<?php
namespace FlatPage\Core\Autoload;

defined('FLATPAGE') || die;


/**
 * @subpackage Autoload PSR 4
 * 
 * Autocargador de clases
 */
class Psr4
{
    private $prefix = [
        'FlatPage\\Core' => 'core',
        'FlatPage\\App' => 'src'
    ];

    private static $instance;

    private function __construct()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    static function loader()
    {
        return (static::$instance instanceof self) ?: new self;
    }

    private function loadClass($class)
    {
        foreach ($this->prefix as $key => $value) {
            $len = strlen($key);
            $path = str_replace(['\\', '/'], DS, FP_PATH . $value . substr($class, $len) . '.php');
            if (is_readable($path)) {
                $file = $path;
                break;
            }
        }

        if (!isset($file)) {
            throw new \Exception("<pre>Error: The class <b>{$class}</b> Not found</pre>");
        }
        require_once $file;
    }
}
