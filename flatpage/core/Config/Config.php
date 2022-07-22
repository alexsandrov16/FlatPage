<?php
namespace FlatPage\Core\Config;

defined('FLATPAGE') || die;

use FlatPage\Core\File\Json;

/**
 * Clase de Configuracion
 */
class Config
{
    protected $php_ini_disable;

    public function __construct()
    {
        $this->php_ini_disable = explode(', ', ini_get('disable_functions'));
    }

    /**
     * Verificar funciones
     * 
     * Verifica que funciones PHP estan desactivadas
     * 
     * @param string $fun funcion PHP a verificar
     * @return bool
     */
    public function hasDisableFunc(String $fun)
    {
        if (in_array($fun, $this->php_ini_disable)) {
            return true;
        }
        return false;
    }

    /**
     * Cargar configuracion
     *
     * @param string $cfg nombre del fichero de configuracion
     * @return array
     **/
    public function load(String $cfg)
    {
        return Json::get(FP_CFG . $cfg);
    }

    /**
     * Establecer variables de entorno
     *
     * @return $_ENV || getenv()
     **/
    public function setEnv()
    {
        foreach ($this->load('site') as $key => $value) {
            $key = strtoupper($key);

            if ($this->hasDisableFunc('putenv')) {
                $_ENV[$key] = $value;
            } else {
                putenv("$key=$value");
            }
        }
    }

    /**
     * Obtener Variable de netorno
     * 
     * @param string $name Nombre de la variable de entorno
     * @return mixed Devuelve el valor de la variable de entorno proporcionada
     */
    public function getEnv(String $name)
    {
        $name = strtoupper($name);
        return $this->hasDisableFunc('putenv') ? $_ENV[$name] : getenv($name);
    }

    /**
     * Salvar configuracion
     *
     * Crea o edita fichero json con los valores pasado
     *
     * @param string $name nombre del fichero a crear o editar
     * @param array $data valores a escribir en el fichero
     * @return Json Devuelve fichero Json con los valores 
     **/
    static function save(String $name, array $data)
    {
        return Json::set(FP_CFG . $name, $data);
    }
}
