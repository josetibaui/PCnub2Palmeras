<?php

// [Path}/loteDba

namespace PCnub2Palmeras\Modelo\Dba;

/**
 * Descripcion de loteDba
 *
 * @author José Tibau <uabitesoj at gmail.com>
 */
use PCnub2Palmeras\Modelo\Entidades\lote;

class loteDba extends Dba {

    const NOMBRE_TABLA = 'lote_ts00';

    public static function getLoteById($idLote, PdoConnection $db) {
        return getEntidadById(array(
            'db' => $db,
            'tabla' => self::NOMBRE_TABLA,
            'entidad' => 'PCnub2Palmeras\Modelo\Entidades\lote',
            'id' => array('idLote' => $idLote))
        );
    }

    public static function insertLote(lote $lote, PdoConnection $db = NULL) {
        $lotesInsertados = 0;
        $dbLocal = false;
        if (!$db) {
            $db = new PdoConnection();
            $dbLocal = true;
        }
        if ($db) {
            try {
                $sql = 'INSERT INTO ' . self::nombreTabla . ' (' .
                        ' idLote, ' .
                        ' desde, ' .
                        ' hasta, ' .
                        ' inicio, ' .
                        ' fin, ' .
                        ' estado ' .
                        ' ) VALUES (' .
                        ' :idLote, ' .
                        ' :desde, ' .
                        ' :hasta, ' .
                        ' :inicio, ' .
                        ' :fin, ' .
                        ' :estado ' .
                        ')';
                $stm = $db->prepare($sql);
                $stm->execute(array(
                    ':idLote' => $lote->getIdLote(),
                    ':desde' => $lote->getDesde(),
                    ':hasta' => $lote->getHasta(),
                    ':inicio' => $lote->getInicio(),
                    ':fin' => $lote->getFin(),
                    ':estado' => $lote->getEstado()
                ));
                $lotesInsertados = $stm->rowCount();
                if ($lotesInsertados != 0) {
                    $lote->setIdEnvio($db->lastInsertId());
                }
                $stm->closeCursor();
            } catch (\PDOException $ex) {
                Error::displayStop(
                        array(
                            'mensaje' => 'Error: ' . $ex->getMessage() . '. (' . $ex->getCode() . ')',
                            'clase' => __CLASS__,
                            'funcion' => __FUNCTION__,
                            'accion' => 'Insert ' . self::nombreTabla .
                            "\nDesde=" . $lote->getDesde() .
                            "\nHasta=" . $lote->getHasta()
                ));
            }
        }
        if ($dbLocal) {
            unset($db);
        }
        return $lotesInsertados;
    }

    public static function updateLote(lote $loteNuevo, lote $loteActual, PdoConnection $db = NULL) {
        $lotesModificados = 0;
        $dbLocal = FALSE;
        if ($db) {
            $db = new PDOConnection();
            $dbLocal = TRUE;
        }

        if (isset($loteNuevo) && ($loteNuevo != $loteActual)) {
            if ($db) {
                try {
                    $sql = 'UPDATE ' . self::NOMBRE_TABLA . ' SET ' .
                            ' desde = :desde,' .
                            ' hasta =  :hasta,' .
                            ' inicio =  :inicio,' .
                            ' fin =  :fin,' .
                            ' estado =  :estado' .
                            ' WHERE idLote = :idLote';
                    $stm = $db->prepare($sql);
                    $stm->execute([
                        ':idLote' => $loteNuevo->getIdLote(),
                        ':desde' => $loteNuevo->getDesde(),
                        ':hasta' => $loteNuevo->getHasta(),
                        ':inicio' => $loteNuevo->getInicio(),
                        ':fin' => $loteNuevo->getFin(),
                        ':estado' => $loteNuevo->getEstado()
                    ]);
                    $lotesModificados = $stm->rowCount();
                    $stm->closeCursor();
                } catch (\PDOException $ex) {
                    Error::displayStop([
                        'mensaje' => 'Error: ' . $ex->getMessage() . '. (' . $ex->getCode() . ')',
                        'clase' => __CLASS__,
                        'funcion' => __FUNCTION__,
                        'accion' => 'Update ' . self::nombreTabla .
                        "\niD=" . $loteActual->getIdLote() .
                        "\nDesde=" . $loteNuevo->getDesde() .
                        "\nHasta=" . $loteNuevo->getHasta()
                    ]);
                }
            }
        }

        if ($dbLocal) {
            unset($db);
        }
        return $lotesModificados;
    }

}
