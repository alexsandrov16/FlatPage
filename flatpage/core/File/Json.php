<?php
defined('FLATPAGE') || die;

namespace FlatPage\Core\File;

use Exception;

/**
 * Clase de Configuracion
 */
class Json
{
    static $ext = '.json';

    /**
     * Decodificar JSON
     *
     * Convierte un json pasado en un array 
     *
     * @param string $filename nombre del fichero Json
     * @return array devuelve un array con los parametros del Json
     * @throws catch 
     **/
    static public function get(String $filename)
    {
        try {
            if (file_exists($file = $filename . self::$ext)) {
                return json_decode(file_get_contents($file), true);
            }
            throw new Exception("No se localizó el fichero de configuración $file");
        } catch (\Throwable $th) {
            //log_message('[Error] - ' . $th->getMessage());
            die($th->getMessage());
        }
    }

    /**
     * Crea o actualiza archivos JSON
     *
     * Crea o actualiza archivos Json con los valores pasados
     *
     * @param string $name nombre del archivo
     * @param array $data datos a codificar
     * @return mixed devuelve el número de bytes que fueron escritos en el fichero, o FALSE en caso de error. 
     **/
    static function set(String $name, array $data)
    {
        if (file_exists($file = $name . self::$ext)) {

            $config_file = json_decode(file_get_contents($file), true);

            foreach ($data as $key => $value) {
                if (array_key_exists($key, $config_file)) {
                    $config_file[$key] = $value;
                }
            }
            return file_put_contents($file, json_encode($config_file, JSON_PRETTY_PRINT), LOCK_EX);
        }

        return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    }
}
