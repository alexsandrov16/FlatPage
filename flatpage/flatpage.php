<?php
defined('FLATPAGE') || die;

/**
 * @package FlatPage
 * @author alexsandrov16
 * @license MIT
 */

use FlatPage\Core\Config\Config;
use FlatPage\Core\Router\Router;

//Environment
function env(String $envi)
{
    return (new Config)->getEnv($envi);
}

//View Template
function view(String $filename, array $data = [], Bool $pannel = false)
{
    foreach ($data as $key => $value) {
        $$key = $value;
    }

    $file = $pannel ? FP_PATH . "admin/$filename.php" : FP_THEMES . env('template') . "/$filename.php";

    if (file_exists($file)) {
        ob_start();
        include $file;
        return ob_flush();
    }
    throw new Exception("Not Found file <b>$file</b>");
}

//Message logs
function logs()
{
    # code...
}

function redirect(String $path = '/')
{
    return Router::redirect($path);
}
