<?php
namespace FlatPage\App\Controller;

defined('FLATPAGE') || die;

use FlatPage\Core\App;
use FlatPage\Core\Config\Config;
use FlatPage\Core\File\Json;

/**
 * Dashboard Controller class
 */
class Dashboard
{
    public function __construct()
    {
        session_start(['name' => env('session_name'),]);
        if (env('ROOT')) {
            $_SESSION['fp_login_access'] = true;
            $_SESSION['user'] = 'root';
        }
        if (!key_exists('fp_login_access', $_SESSION)) {
            die($this->login());
        }
    }

    public function index()
    {
        view(__FUNCTION__, [
            'title' => env('title') . ' | ' . App::$name,
            'user' => $_SESSION['user'],
            'page' => 'home',
            'content' => file_get_contents(FP_PAGES . "home.json"),
            'menus' => Json::get(FP_CFG . 'data')['menu'],
        ], true);
    }

    public function page()
    {
        if ($_POST) {
            file_put_contents(FP_PAGES . 'home.json', $_POST['page']);
            echo 'Documento salvado';
            return;
        }

        if ($_FILES) {
            foreach ($_FILES['image'] as $key => $value) {
                $$key = $value;
            }

            if (move_uploaded_file($tmp_name, FP_UPLOAD . $name)) {
                echo json_encode([
                    'success' => 1,
                    'file' => [
                        'url' => env('base_url') . "/contents/upload/$name",
                    ]
                ]);
            } else {
                echo json_encode(['success' => 0]);
            }
            return;
        }
        return redirect('/admin');
    }

    public function navigation()
    {
        if ($_POST) {
            if ($_POST['action'] == 'create' || $_POST['action'] == 'update') {

                $data[$_POST['slug']] = array($_POST['name'] => $_POST['link']);
                $save['menu'] = array_merge(Json::get(FP_CFG . 'data')['menu'], $data);
                Config::save('data', $save);
            }

            if ($_POST['action'] == 'delete') {
                if (key_exists($_POST['del'], Json::get(FP_CFG . 'data')['menu'])) {
                    $del = Json::get(FP_CFG . 'data')['menu'];
                    unset($del[$_POST['del']]);
                    $data['menu'] = $del;
                    Config::save('data', $data);
                }
            }
        }
        return redirect('/admin');
    }

    public function user()
    {
        if ($_POST) {
            $change = Config::save('admin', [
                'hash' => password_hash($_POST['pass'], PASSWORD_BCRYPT)
            ]);

            if ($change) {
                if (env('root')) {
                    Config::save('site', ['root' => false]);
                }
                echo 1;
                return;
            }
        }
        return redirect('/admin');
    }

    public function setting()
    {
        if ($_POST) {
            $data = [
                'title'        => $_POST['title'],
                'description'  => $_POST['description'],
                'base_url'     => $_POST['base_url'],
                'template'     => $_POST['template']
            ];

            if (Config::save('site', $data)) {
                echo 1;
                return;
            }
        }
        return redirect('/admin');
    }

    public function login()
    {
        $message = null;
        if ($_POST) {
            if (!empty($_POST['user']) && file_exists(FP_CFG . $_POST['user'] . '.json')) {
                if (password_verify($_POST['password'], Json::get(FP_CFG . $_POST['user'])['hash'])) {
                    #Session
                    $_SESSION['fp_login_access'] = true;
                    $_SESSION['user'] = $_POST['user'];
                    return redirect('/admin');
                }
                $message = 'Contraseña incorrecta. Vuelva a intentarlo.';
            } else {
                $message = 'Nombre de usuario incorrecto. Vuelva a intentarlo.';
            }
        }

        view('login', [
            'title' => 'Iniciar Sesión | ' . App::$name,
            'message' => $message,
        ], true);
    }

    public function logout()
    {
        session_destroy();
        return redirect('/');
    }
}
