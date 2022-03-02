<?php

/**
 * @package FlatPage
 * @author alexsandrov16
 * @copyright Copyright (c) 2022 alexsandrov16
 * @license MIT License, see LICENSE
 */

define('FLATPAGE', true);

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('ABS_PATH') || define('ABS_PATH', __DIR__ . DS);
defined('FP_PATH') || define('FP_PATH', ABS_PATH . 'flatpage' . DS);

require_once FP_PATH . 'bootloader.php';