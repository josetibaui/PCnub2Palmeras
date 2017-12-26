<?php

// [Path}/datosDba

namespace PCnub2Palmeras\Modelo\Dba;

/**
 * Descripcion de datosDba
 *
 * @author José Tibau <uabitesoj at gmail.com>
 */
use PCnub2Palmeras\Modelo\Entidades\datos;

class datosDba extends Dba {

    const NOMBRE_TABLA = 'datos_ts00';

    public static function getDatosById($idDatos, PdoConnection $db) {
        return getEntidadById(
                ['db' => $db,
                    'tabla' => self::NOMBRE_TABLA,
                    'entidad' => 'PCnub2Palmeras\Modelo\Entidades\datos',
                    'id' => array('idDatos' => $idDatos)]
        );
    }

    public static function insertDatos(datos $datos, PdoConnection $db) {
        $datosInsertados = 0;
        $dbLocal = false;
        if (!$db) {
            $db = new PdoConnection();
            $dbLocal = true;
        }
        try {
            $sql = $datos->constructSqlInsert();
            $stm = $db->prepare($sql);
            $stm->execute($datos->constructFieldsArray());
            $datosInsertados = $stm->row_count();
            if ($datosInsertados != 0) {
                $lote->setIdDatos($db->lastInsertId());
            }
            $stm->closeCursor();
        } catch (\PDOException $ex) {
            Error::displayStop([
                'mensaje' => 'Error: ' . $ex->getMessage() . '. (' . $ex->getCode() . ')',
                'clase' => __CLASS__,
                'funcion' => __FUNCTION__,
                'accion' => 'Insert ' . self::nombreTabla .
                '\nLote ' . $datos->getLote()
            ]);
        }
        if ($dbLocal) {
            unset($db);
        }
        return $datosInsertados;
    }

    public static function updateDatos(datos $datosNuevo, datos $datosActual, PdoConnection $db) {
        $datosModificados = 0;
        $dbLocal = false;
        if (!$db) {
            $db = new PdoConnection();
            $dbLocal = TRUE;
        }
        if (isset($loteNuevo) && ($loteNuevo != $loteActual)) {
            if ($db) {
                try {
                    $sql = $datosNuevo->constructSqlUpdate('idDatos');
                    $stm = $db->prepare($sql);
                    $stm->execute($datosNuevo->constructFieldsArray());
                    $datosModificados = $stm->row_count();
                    $stm->closeCursor();
                } catch (\PDOException $ex) {
                    Error::displayStop([
                        'mensaje' => 'Error: ' . $ex->getMessage() . '. (' . $ex->getCode() . ')',
                        'clase' => __CLASS__,
                        'funcion' => __FUNCTION__,
                        'accion' => 'Update ' . self::nombreTabla .
                        "\niD=" . $datosActual->getIdDatos() .
                        "\nLote=" . $datosActual->getIdLote()
                    ]);
                }
            }
        }
        if ($dbLocal) {
            unset($db);
        }
        return $datosModificados;
    }

    public static function deleteDatos($idDatos, PdoConnection $db) {
        $datosEliminados = 0;
        $dbLocal = false;
        if (!$db) {
            $db = new PdoConnection();
            $dbLocal = TRUE;
        }
        if ($db) {
            try {
                $sql = 'DELETE ' . self::NOMBRE_TABLA .
                        ' WHERE idDatos = :idDatos';
                $stm->execute(['idDatos' => $idDatos]);
                $datosEliminados = $stm->row_count();
                $stm->closeCursor();
            } catch (\PDOException $ex) {
                Error::displayStop([
                    'mensaje' => 'Error: ' . $ex->getMessage() . '. (' . $ex->getCode() . ')',
                    'clase' => __CLASS__,
                    'funcion' => __FUNCTION__,
                    'accion' => 'Delete ' . self::nombreTabla .
                    "\niD=" . $idDatos
                ]);
            }
        }
        if ($dbLocal) {
            unset($db);
        }
        return $datosEliminados;
    }

}
