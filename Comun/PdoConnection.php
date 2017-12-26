<?php

// Comun/PdoConnection.php

/**
 * Description of PdoConnection
 *
 * @author jtibau
 * 
 * Esta versión utiliza namespaces
 * Creado 3-Dic-2015
 */

namespace PCnub2Palmeras\Comun;

class PdoConnection extends \PDO {

    protected $driver;
    protected $dbname;
    protected $user;
    protected $password;
    protected $host;
    protected $timezone;
    protected $options;

    public function __construct($dbOptions = array()) {

        /*
         * Primero lee los valores default en config/database.yml
         */
        $this->readConfig();
        /*
         * Y luego pone los valores especiales que puedan venir al crear el enlace
         */
        $this->setValores($dbOptions);

        $dsn = $this->getDriver() . ':' .
                'dbname=' . $this->getDbname() . ';' .
                'host=' . $this->getHost();
        try {
            parent::__construct($dsn, $this->getUser(), $this->getPassword(), $this->getOptions());
            parent::setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            Error::displayStop(
                    array(
                        'mensaje' => 'Error ' . $e->getMessage() . '(' . $e->getCode() . ')',
                        'clase' => __CLASS__,
                        'funcion' => __FUNCTION__,
                        'accion' => 'Open Database'
            ));
        }
    }

    protected function readConfig() {
        $config = (new CargaConfig)->leerConfig(__DIR__. '/../Config/database.yml')->getConfig();
        if (!$config){
            Error::displayStop(array('mensaje' => 'No se encuentra el archivo con la configuración de la base de datos'));
        }
        $this->setValores($config['basico']);
    }

    protected function setValores($config = array()) {
        foreach ($config as $clave => $valor) {
            if ($clave == 'driver') {
                $this->setDriver($valor);
            } elseif ($clave == 'dbname') {
                $this->setDbname($valor);
            } elseif ($clave == 'user' or $clave == 'dbuser') {
                $this->setUser($valor);
            } elseif ($clave == 'password' or $clave == 'dbpass') {
                $this->setPassword($valor);
            } elseif ($clave == 'host' or $clave == 'dbhost') {
                $this->setHost($valor);
            } elseif ($clave == 'timezone' or $clave == 'tz') {
                $this->setTimezone($valor);
            } elseif ($clave == 'options' or $clave == 'dboptions') {
                $this->setOptions($valor);
            }
        }
    }

    protected function setDriver($driver) {
        $this->driver = $driver;
    }

    protected function setDbname($dbname) {
        $this->dbname = $dbname;
    }

    protected function setUser($user) {
        $this->user = $user;
    }

    protected function setPassword($password) {
        $this->password = $password;
    }

    protected function setHost($host) {
        $this->host = $host;
    }

    protected function setTimezone($timezone) {
        $this->timezone = $timezone;
    }

    protected function setOptions($options) {
        $this->options = $options;
    }

    protected function getDriver() {
        if (!isset($this->driver)) {
            return NULL;
        }
        return $this->driver;
    }

    protected function getDbname() {
        if (!isset($this->dbname)) {
            return NULL;
        }
        return $this->dbname;
    }

    protected function getUser() {
        if (!isset($this->user)) {
            return NULL;
        }
        return $this->user;
    }

    protected function getPassword() {
        if (!isset($this->password)) {
            return NULL;
        }
        return $this->password;
    }

    protected function getHost() {
        if (!isset($this->host)) {
            return NULL;
        }
        return $this->host;
    }

    protected function getTimezone() {
        if (!isset($this->timezone)) {
            return NULL;
        }
        return $this->timezone;
    }

    protected function getOptions() {
        if (!isset($this->optiones)) {
            return array();
        }
        return $this->options;
    }

}

?>
