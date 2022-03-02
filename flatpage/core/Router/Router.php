<?php
defined('FLATPAGE') || die;

namespace FlatPage\Core\Router;

use Exception;
use FlatPage\Core\Http\Uri;

/**
 * @subpackage Router
 * 
 * undocumented class
 */
class Router
{
    /** @var array $rutes tabla de rutas */
    protected $routes;

    /** @var Request $request description */
    public $headers;

    public $path;

    public $regex = [
        '(:any)'      => '(.*)',
        '(:segment)'  => '([^/]+)',
        '(:alphanum)' => '([a-zA-Z0-9]+)',
        '(:num)'      => '([0-9]+)',
        '(:alpha)'    => '([a-zA-Z]+)',
        '(:hash)'     => '([^/]+)',
    ];

    public $namespaces = "FlatPage\\App\\Controller\\";

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function __construct(array $routes = [])
    {
        $this->path = $_SERVER['REQUEST_URI'];
        $this->routes = $routes;
    }

    /**
     * Redirige a una nueva ruta
     * 
     * este metodo se establece en el index del controlador seleccionado
     */
    static public function redirect(String $path = '/')
    {
        return header('Location: ' . env('base_url') . $path);
    }

    /**
     * Disparador de rutas
     */
    public function dispatch()
    {
        foreach ($this->routes as $key => $value) {

            $uri = new Uri(preg_replace(array_keys($this->regex), array_values($this->regex), env('base_url') . $key));

            $path_parsed = $uri->getPath();
            
            if (preg_match("~^/?$path_parsed/?$~", $this->path)) {
                $array = explode('::', $value);

                $controller = $this->namespaces . $array[0];
                $method = $array[1];
                $param = explode('/', substr($this->path, strrpos($this->path, $method)));
                array_shift($param);

                if (class_exists($controller)) {
                    $obj = new $controller;

                    if (method_exists($obj, $method)) {
                        $reflection = new \ReflectionMethod($obj, $method);

                        if ($reflection->isPublic()) {

                            if (empty($reflection->getParameters())) {
                                return $obj->$method();
                            }

                            //if (count($param) === $reflection->getNumberOfRequiredParameters()) {
                            return call_user_func_array(array($obj, $method), $param);
                            //} 
                        }
                    }
                    throw new Exception("Not Found Method $controller::$method");
                    
                }
                throw new Exception("Not Found Controller $controller");
                
            }
        }

        throw new Exception('', 404);
        
    }
}
