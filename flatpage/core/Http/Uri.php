<?php
namespace FlatPage\Core\Http;

defined('FLATPAGE') || die;

/**
 * @subpackage HTTP
 * 
 * Objeto de valor que representa un URI.
 * 
 * Esta interfaz está destinada a representar URI según RFC 3986 y proporcionar métodos para las
 * operaciones más comunes. Se puede proporcionar funcionalidad adicional para trabajar con URI en la
 * parte superior de la interfaz o externamente.
 * 
 * Su uso principal es para solicitudes HTTP, pero también se puede usar en otros contextos.
 * 
 * Las instancias de esta interfaz se consideran inmutables; todos los métodos que 
 * podría cambiar de estado DEBE implementarse de manera que conserven el estado interno de la instancia actual
 * y devuelvan una instancia que contenga el estado cambiado.
 * 
 * Normalmente, el encabezado del Host también estará presente en el mensaje de solicitud.
 * Para las solicitudes del lado del servidor, el esquema normalmente será detectable en los parámetros del servidor.
 * @link http://tools.ietf.org/html/rfc3986 (la especificación de URI) 
 **/
class Uri // implements UriInterface
{
    protected $scheme;
    protected $host;
    protected $port = NULL;
    protected $path;
    protected $query;
    protected $fragment;

    protected $standar_ports = [
        'http'  => 80,
        'https' => 443,
    ];

    public function __construct(String $uri = NULL)
    {
        if (!is_null($uri)) {
            $parts = parse_url($uri);
            if ($parts === false) {
                throw HTTPException::forUnableToParseURI($uri);
            }
            $this->setUri($parts);
        }
    }

    /**
     * Recupere el componente de esquema del URI.
     * 
     * Si no hay ningún esquema, este método DEBE devolver una cadena vacía.
     * 
     * El valor devuelto DEBE estar normalizado a minúsculas, según RFC 3986 Sección 3.1.
     * El carácter ":" final no es parte del esquema y NO DEBE ser adicional.
     * 
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string El esquema de URI.
     **/
    public function getScheme(): String
    {
        return $this->scheme;
    }

    /**
     * Recupere el componente de autoridad del URI.
     * 
     * Si no hay información de autoridad presente, este método DEBE devolver un cuerda.
     * 
     * La sintaxis de autoridad del URI es: host [: puerto]
     * 
     * Si el componente del puerto no está configurado o es el puerto estándar para el esquema, NO DEBE incluirse.
     * 
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string La autoridad de URI, en formato "host [: puerto]".
     **/
    public function getAuthority(): String
    {
        if (empty($this->host)) {
            return '';
        }

        $authority = !empty($this->getPort()) ? $this->host . ':' . $this->getPort() : "$this->host";

        return $authority;
    }

    /**
     * Recupere el componente de host del URI.
     * 
     * Si no hay ningún host, este método DEBE devolver una cadena vacía.
     * El valor devuelto DEBE estar normalizado a minúsculas, según RFC 3986 Sección 3.2.2.
     * 
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string El host de URI.
     **/
    public function getHost(): String
    {
        return $this->host;
    }

    /**
     * Recupere el componente de puerto del URI.
     * 
     * Si hay un puerto presente y no es estándar para el esquema actual, este método DEBE devolverlo
     * como un número entero. Si el puerto es el estándar utilizado con el esquema actual,
     * este método DEBERÍA devolver nulo.
     * 
     * Si no hay ningún puerto y no hay ningún esquema, este método DEBE devolver un valor nulo.
     * 
     * Si no hay ningún puerto, pero hay un esquema, este método PUEDE devolver el puerto estándar para
     * ese esquema, pero DEBERÍA devolver nulo.
     * 
     * @return null|int El puerto URI. 
     **/
    public function getPort(): ?Int
    {
        if ($this->getScheme() == '') {
            return null;
        }
        foreach ($this->standar_ports as $key => $value) {
            if ($key === $this->getScheme() && $value === $this->port) {
                return null;
            }
        }
        return $this->port;
    }

    /**
     * Recupere el componente de ruta del URI.
     * 
     * La ruta puede ser vacía o absoluta (comenzando con una barra) o sin raíces (sin comenzar con una barra).
     * Las implementaciones DEBEN soportar las tres sintaxis.
     * 
     * Normalmente, la ruta vacía "" y la ruta absoluta "/" se consideran iguales según se define
     * en RFC 7230 Sección 2.7.3. Pero este método NO DEBE realizar automáticamente esta normalización porque
     * en contextos con una ruta base recortada, p. Ej. el controlador frontal, esta diferencia se vuelve significativa.
     * Es tarea del usuario manejar tanto "" como "/".
     * 
     * El valor devuelto DEBE estar codificado en porcentaje, pero NO DEBE codificar dos veces ningún carácter. 
     * Para determinar qué caracteres codificar, consulte RFC 3986, Secciones 2 y 3.3.
     * Por ejemplo, si el valor debe incluir una barra inclinada ("/") que no pretende delimitador entre los segmentos
     * de ruta, ese valor DEBE pasarse en forma codificada (por ejemplo, "% 2F") a la instancia.
     * 
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @return string La ruta de URI.
     **/
    public function getPath(): String
    {
        return $this->path;
    }

    /**
     * Recupere la cadena de consulta del URI.
     * 
     * Si no hay una cadena de consulta, este método DEBE devolver una cadena vacía.
     * El carácter "?" no es parte de la consulta y NO DEBE ser adicional.
     * 
     * El valor devuelto DEBE estar codificado en porcentaje, pero NO DEBE codificar dos veces ningún carácter.
     * Para determinar qué caracteres codificar, consulte RFC 3986, Secciones 2 y 3.4.
     * 
     * Como ejemplo, si un valor en un par clave / valor de la cadena de consulta debe incluyen un ampersand ("&")
     * que no pretende ser un delimitador entre valores, ese valor DEBE pasarse en forma codificada (por ejemplo, "% 26")
     * a la instancia.
     * 
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @return string La cadena de consulta de URI.
     **/
    public function getQuery(): String
    {
        return $this->query;
    }

    /**
     * Recupere el componente de fragmento del URI.
     * 
     * Si no hay ningún fragmento presente, este método DEBE devolver una cadena vacía.
     * El carácter "#" inicial no es parte del fragmento y NO DEBE agregarse.
     * 
     * El valor devuelto DEBE estar codificado en porcentaje, pero NO DEBE codificar dos veces ningún carácter.
     * Para determinar qué caracteres codificar, consulte RFC 3986, Secciones 2 y 3.5.
     * 
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     * @return string El fragmento de URI. 
     **/
    public function getFragment(): String
    {
        return $this->fragment;
    }

    /**
     * Devuelve una instancia con el esquema especificado.
     *
     * Este método DEBE conservar el estado de la instancia actual y devolver una
     * instancia que contiene el esquema especificado.
     *
     * Las implementaciones DEBEN admitir los esquemas "http" y "https" caso insensiblemente,
     * y PUEDE adaptarse a otros esquemas si es necesario.
     *
     * Un esquema vacío equivale a eliminar el esquema.
     *
     * @param string $scheme El esquema a usar con la nueva instancia.
     * @return static Una nueva instancia con el esquema especificado.
     * @throws \InvalidArgumentException para esquemas no válidos o no admitidos.
     **/
    public function setScheme($scheme = NULL): Uri
    {
        if (isset($scheme)) {
            $this->scheme = strtolower($scheme);
        } else {
            $this->scheme = '';
        }
        return $this;
    }

    /**
     * Devuelve una instancia con el host especificado.
     *
     * Este método DEBE conservar el estado de la instancia actual y devolver una instancia
     * que contiene el host especificado.
     *
     * Un valor de host vacío equivale a eliminar el host.
     *
     * @param string $host El nombre de host que se usará con la nueva instancia.
     * @return static Una nueva instancia con el host especificado.
     * @throws \InvalidArgumentException para nombres de host no válidos.
     **/
    public function setHost($host = NULL): Uri
    {
        if (isset($host)) {
            $this->host = $host;
        } else {
            $this->host = '';
        }
        return $this;
    }

    /**
     * Devuelve una instancia con el puerto especificado.
     *
     * Este método DEBE conservar el estado de la instancia actual y devolver
     * una instancia que contiene el puerto especificado.
     *
     * Las implementaciones DEBEN generar una excepción para los puertos fuera del
     * rangos de puertos TCP y UDP establecidos.
     *
     * Un valor nulo proporcionado para el puerto equivale a eliminar el puerto
     * información.
     *
     * @param null|int $port El puerto a usar con la nueva instancia; un valor nulo
     * elimina la información del puerto.
     * @return static Una nueva instancia con el puerto especificado.
     * @throws \InvalidArgumentException para puertos no válidos.
     **/
    public function setPort($port = NULL): Uri
    {
        try {
            if (is_null($port)) {
                return $this;
            }

            if ($port > 0 || $port < 65535) {
                $this->port = (int) $port;
                return $this;
            } else {
                throw new \InvalidArgumentException("Error Processing Port", 1);
            }
        } catch (\InvalidArgumentException $th) {
            die($th->getMessage());
        }
    }

    /**
     * Devuelve una instancia con la ruta especificada.
     *
     * Este método DEBE conservar el estado de la instancia actual y devolver
     * una instancia que contiene la ruta especificada.
     *
     * La ruta puede ser vacía o absoluta (comenzando con una barra) o
     * sin raíces (no comienza con una barra). Las implementaciones DEBEN apoyar a todos
     * tres sintaxis.
     *
     * Si la ruta está destinada a ser relativa al dominio en lugar de a la ruta, entonces
     * debe comenzar con una barra ("/"). Rutas que no comienzan con una barra ("/")
     * se supone que son relativos a alguna ruta base conocida por la aplicación o
     * consumidor.
     *
     * Los usuarios pueden proporcionar caracteres de ruta codificados y decodificados.
     * Las implementaciones aseguran la codificación correcta como se describe en getPath ().
     *
     * @param string $path La ruta a usar con la nueva instancia.
     * @return static Una nueva instancia con la ruta especificada.
     * @throws \InvalidArgumentException para rutas no válidas.
     **/
    public function setPath($path = NULL): Uri
    {
        if (isset($path)) {
            $this->path = strtok($path, '?');
        } else {
            $this->path = '/';
        }

        return $this;
    }

    /**
     * Devuelve una instancia con la cadena de consulta especificada.
     *
     * Este método DEBE conservar el estado de la instancia actual y devolver
     * una instancia que contiene la cadena de consulta especificada.
     *
     * Los usuarios pueden proporcionar caracteres de consulta codificados y decodificados.
     * Las implementaciones aseguran la codificación correcta como se describe en getQuery ().
     *
     * Un valor de cadena de consulta vacío equivale a eliminar la cadena de consulta.
     *
     * @param string $query La cadena de consulta que se utilizará con la nueva instancia.
     * @return static Una nueva instancia con la cadena de consulta especificada.
     * @throws \InvalidArgumentException para cadenas de consulta no válidas.
     **/
    public function setQuery($query = NULL): Uri
    {

        if (isset($query)) {
            if ($start = mb_stripos($query, '?')) {
                $leng = mb_stripos($query, "#") ? mb_stripos($query, "#") - $start : null;
                $this->query =  mb_substr($query, $start, $leng);
            }
            $this->query = '';
        } else {
            $this->query = '';
        }
        return $this;
    }

    /**
     * Devuelve una instancia con el fragmento de URI especificado.
     *
     * Este método DEBE conservar el estado de la instancia actual y devolver
     * una instancia que contiene el fragmento de URI especificado.
     *
     * Los usuarios pueden proporcionar fragmentos de caracteres codificados y decodificados.
     * Las implementaciones aseguran la codificación correcta como se describe en getFragment ().
     *
     * Un valor de fragmento vacío equivale a eliminar el fragmento.
     *
     * @param string $fragment El fragmento que se usará con la nueva instancia.
     * @return static Una nueva instancia con el fragmento especificado.
     **/
    public function setFragment($fragment = NULL): Uri
    {
        if (isset($fragment)) {
            if ($start = mb_stripos($fragment, "#")) {
                $this->fragment = mb_substr($fragment, $start);
            } else {
                $this->fragment = '';
            }
        } else {
            $this->fragment = '';
        }
        return $this;
    }

    /**
     * Devuelve la representación de la cadena como una referencia URI.
     *
     * Dependiendo de los componentes del URI presentes, el resultado
     * la cadena es un URI completo o una referencia relativa de acuerdo con RFC 3986,
     * Sección 4.1. El método concatena los diversos componentes de la URI,
     * usando los delimitadores apropiados:
     *
     * - Si hay un esquema, DEBE tener el sufijo ":".
     * - Si una autoridad está presente, DEBE tener el prefijo "//".
     * - La ruta se puede concatenar sin delimitadores. Pero hay dos
     * casos en los que la ruta debe ajustarse para hacer la referencia URI
     * válido ya que PHP no permite lanzar una excepción en __toString ():
     * - Si la ruta no tiene raíces y hay una autoridad presente, la ruta DEBE
     * tener el prefijo "/".
     * - Si la ruta comienza con más de un "/" y no hay autoridad
     * presente, las barras iniciales DEBEN reducirse a una.
     * - Si hay una consulta, DEBE tener el prefijo "?".
     * - Si hay un fragmento, DEBE tener el prefijo "#".
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.1
     * @return string 
     **/
    public function __toString()
    {
        return static::strUri(
            $this->getScheme(),
            $this->getAuthority(),
            $this->getPath(),
            $this->getQuery(),
            $this->getFragment()
        );
    }

    /**
     * Convertir URI en string.
     * 
     * Devuelve todos los componentes de la URI en un string.
     * 
     * @param string $scheme
     * @param string $authority 
     * @param string $path
     * @param string $query
     * @param string $fragment
     * @return string
     **/
    static function strUri(String $scheme = null, String $authority = null, String $path = null, String $query = null, String $fragment = null): String
    {
        $uri = '';
        if (!empty($scheme)) {
            $uri .= "$scheme:";
        }

        if (!empty($authority)) {
            $uri .= "//$authority";
        }

        $uri .= (!empty($path)) ? $path : '/';

        if (!empty($query)) {
            $uri .= "?$query";
        }

        if (!empty($fragment)) {
            $uri .= "#$fragment";
        }

        return $uri;
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param array $parsed Description
     * @return type
     * @throws conditon
     **/
    public function setUri(array $parts)
    {
        $scheme   = isset($parts['scheme'])   ? $parts['scheme'] :   NULL;
        $host     = isset($parts['host'])     ? $parts['host'] :     NULL;
        $port     = isset($parts['port'])     ? $parts['port'] :     NULL;
        $path     = isset($parts['path'])     ? $parts['path'] :     NULL;
        $query    = isset($parts['query'])    ? $parts['query'] :    NULL;
        $fragment = isset($parts['fragment']) ? $parts['fragment'] : NULL;

        return $this->setScheme($scheme)
            ->setHost($host)
            ->setPort($port)
            ->setPath($path)
            ->setQuery($query)
            ->setFragment($fragment);
    }
}
