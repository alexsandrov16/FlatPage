<?php
defined('FLATPAGE') || die;

namespace FlatPage\Core;

use FlatPage\Core\Config\Config;
use FlatPage\Core\Debug\ErrorHandler;
use FlatPage\Core\Html\Html;
use FlatPage\Core\Router\Router;

/**
 * @package FlatPage
 * @version 0.2.3a
 * @author alexsandrov16
 * @license MIT
 */

class App
{
    static $name = "FlatPage";
    static $version = "0.2.3a";
    static $php_version = 7.3;
    static $modules = ['mbstring', 'json'];

    static $instance;
    static $microtime;

    protected $config;
    protected $uri;
    protected $router;

    private function __construct()
    {
        self::$microtime = microtime(true);

        $this->config = new Config;
        $this->config($this->config);

        $this->router = new Router($this->config->load('routes'));


    }
    static function init()
    {
        return (static::$instance instanceof self)? : new self;
    }

    public function execute()
    {
        $this->router->dispatch();
    }

    private function config()
    {
        $this->config->setEnv();

        //Zona Horaria
        date_default_timezone_set(env('timezone'));

        //Set internal encoding.
        ini_set('default_charset', env('charset'));
        mb_internal_encoding(env('charset'));

        (new ErrorHandler(env('debug')))->start();
    }

    static function themes()
    {
        return new Html();
    }
}
