<?php
defined('FLATPAGE') || die;

use FlatPage\Core\App;
use FlatPage\Core\Config\Config;
use FlatPage\Core\Http\Uri;

if (version_compare(PHP_VERSION, App::$php_version, '<')) {
    die(sprintf('Versión actual de PHP <u>%s</u>, actualice PHP a una versión superior a la <b>%s</b>', PHP_VERSION, App::$php_version));
}

if (!in_array('mod_rewrite', apache_get_modules())) {
    die("Active el módulo  <b>mod_rewrite</b> en su servidor Apache.");
}

foreach (App::$modules as $mod) {
    if (!extension_loaded($mod)) {
        die("Instale la extensión <b>$mod</b> en su servidor PHP.");
    }
}

if (is_writable(ABS_PATH)) {
    $folders = ['pages', 'upload'];
    foreach ($folders as $folder) {
        if (!mkdir(ABS_PATH . 'contents' . DS . $folder)) {
            die(sprintf("Ha ocurrido un error al crear el directorio %s", '..'.DS.'contents' . DS . $folder));
        }
    }
} else {
    die(sprintf('El usuario de Apache <b><u>%s</u></b> no posee permisos de escritura en el directorio <b>%s</b>', exec('whoami'), ABS_PATH));
}

$uri = new Uri($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI']);

$url = rtrim(
    $uri->strUri(
        $uri->getScheme(),
        $uri->getAuthority(),
        $uri->getPath(),
        $uri->getQuery(),
        $uri->getFragment(),
    ),
    '/'
);

setcookie('alert', true);

Config::save('site', [
    'title'          => 'Mi Sitio',
    'description'    => 'Sitio web creado exitosamente con ' . App::$name,
    'base_url'       => $url,
    'index'          => rtrim($uri->getPath(), '/'),
    'debug'          => false,
    'template'       => 'default',
    'timezone'       => 'America/Havana',
    'time_format'    => 'Y-m-d H:i:s',
    'language'       => 'es-ES',
    'charset'        => 'UTF-8',
    'session_name'   => 'fp-site',
    'root'           => false
]);

Config::save('data', [
    "menu" => [
        "github" => ["Github" => "https://github.com/alexsandrov16/flatpage"],
        "telegram" => ["Telegram" => "https://t.me/FlatPage"]
    ]
]);

Config::save('admin', [
    'hash' => password_hash('admin', PASSWORD_BCRYPT)
]);

$data = [
    "time" => time(),
    "blocks" => [
        [
            "id" => "dxcIB9gokL",
            "type" => "header",
            "data" => [
                "text" => "¡Hey 👋 bienvenido a FlatPage!",
                "level" => 1
            ]
        ],
        [
            "id" => "PHruUxuJ2i",
            "type" => "paragraph",
            "data" => [
                "text" => "Para comenzar a editar tu sitio acceda al <a href='$url/admin'>panel
                de administración</a>. Sus credenciales de inicio\nde sesión son <span class=\"spoiler\">admin</span>.",
            ]
        ]
    ],
    "version" => "2.22.2"
];

file_put_contents(FP_PAGES . 'home.json', json_encode($data));

unlink(__FILE__);