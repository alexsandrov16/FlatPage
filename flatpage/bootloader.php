<?php
defined('FLATPAGE') || die;

/**
 * @package FlatPage
 * @subpackage Gestor de arranque
 * @version 0.6.3-alpha
 * @author Alejandro Moreno
 * @license MIT
 */

use FlatPage\Core\App;
use FlatPage\Core\Autoload\Psr4;

//Definir rutas del proyecto
require_once 'defines.php';

//Autocargar las clases PSR4
require_once FP_CORE.'Autoload/Psr4.php';
Psr4::loader();

if (file_exists('install.php')) {
    require 'install.php';
}

//Helper
require_once 'flatpage.php';

$app = App::init();
return $app->execute();