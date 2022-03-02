<?php
defined('FLATPAGE') || die;

/**
 * @package FlatPage
 * @author alexsandrov16
 * @license MIT
 */

//Flatpage
defined('FP_CORE') || define('FP_CORE', FP_PATH . 'core' . DS);
defined('FP_SRC') || define('FP_SRC', FP_PATH . 'src' . DS);
//Content
defined('FP_CFG') || define('FP_CFG', ABS_PATH . 'contents' . DS . 'config'.DS);
defined('FP_PAGES') || define('FP_PAGES', ABS_PATH . 'contents' . DS . 'pages'.DS);
defined('FP_LOGS') || define('FP_LOGS', ABS_PATH . 'contents' . DS . 'logs'.DS);
defined('FP_BACKUPS') || define('FP_BACKUPS', ABS_PATH . 'contents' . DS . 'backups'.DS);
defined('FP_UPLOAD') || define('FP_UPLOAD', ABS_PATH . 'contents' . DS . 'upload'.DS);
//Themes
defined('FP_THEMES') || define('FP_THEMES', ABS_PATH . 'template'.DS);