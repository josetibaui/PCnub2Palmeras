<?php

// Modelo\Dba.php

namespace PCnub2Palmeras\Modelo;

/**
 * Description of dba
 *
 * @author jtibau
 */
use PCnub2Palmeras\Comun\PdoConnection;
use PCnub2Palmeras\Comun\Error;

class Dba {

    public function getEntidadById(array $params = array()) {
        if (!isset($params)) {
            Error::displayStop(array(
                'clase' => __CLASS__,
                'funcion' => __FUNCTION__,
                'mensaje' => 'No hay parámetros para el método'));
        }
        if (!is_array($params)) {
            Error::displayStop(array(
                'clase' => __CLASS__,
                'funcion' => __FUNCTION__,
                'mensaje' => 'Los parámetros para el método no están en un arreglo'));
        }
        if (!array_key_exists('id', $params)) {
            Error::displayStop(array(
                'clase' => __CLASS__,
                'funcion' => __FUNCTION__,
                'mensaje' => 'No hay un arreglo con los parámetros de la clave primaria'));
        }
        $id = $params['id'];
        if (!is_array($id)) {
            Error::displayStop(array(
                'clase' => __CLASS__,
                'funcion' => __FUNCTION__,
                'mensaje' => 'Los parámetros de la clave primaria no están en un arreglo'));
        }
        $idColumna = key($id);
        $idValor = current($id);

        if (!array_key_exists('db', $params)) {
            $db = false;
        } else {
            $db = $params['db'];
        }

        if (!array_key_exists('tabla', $params)) {
            Error::displayStop(array(
                'clase' => __CLASS__,
                'funcion' => __FUNCTION__,
                'mensaje' => 'No hay el nombre de la tabla en la base de datos'));
        }
        $tabla = $params['tabla'];

        if (!array_key_exists('entidad', $params)) {
            Error::displayStop(array(
                'clase' => __CLASS__,
                'funcion' => __FUNCTION__,
                'mensaje' => 'No hay el nombre de la clase de entidad'));
        }
        $claseEntidad = $params['entidad'];
        if (!class_exists($claseEntidad)) {
            Error::displayStop(array(
                'clase' => __CLASS__,
                'funcion' => __FUNCTION__,
                'mensaje' => $claseEntidad . ' no es una clase'));
        }

        $objeto = false;
        $dbLocal = false;
        if (!$db) {
            $db = new PdoConnection();
            $dbLocal = true;
        }
        if ($db) {
            try {
                $sql = 'SELECT * FROM ' . $tabla . ' WHERE ' . $idColumna . ' = :idValor';
                $stm = $db->prepare($sql);
                $stm->execute(array('idValor' => $idValor));
                $resultado = $stm->fetch(\PDO::FETCH_ASSOC);
                $objeto = new $claseEntidad($resultado);
                $stm->closeCursor();
            } catch (\PDOException $e) {
                Error::displayStop(
                        array(
                            'mensaje' => 'Error ' . $e->getMessage() . '(' . $e->getCode() . ')',
                            'clase' => __CLASS__,
                            'funcion' => __FUNCTION__,
                            'accion' => 'Select ' . $tabla . ' By Id' . PHP_EOL .
                            'Columna ID: ' . $idColumna . PHP_EOL .
                            'Valor ID: ' . $idValor . PHP_EOL
                ));
            }
        }
        if ($dbLocal) {
            unset($db);
        }
        return $objeto;
    }

    

}
