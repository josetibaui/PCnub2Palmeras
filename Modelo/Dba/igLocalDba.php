<?php

// Modelo\Dba\igLocaDba.php

namespace PCnub2Palmeras\Modelo\Dba;

use PCnub2Palmeras\Modelo\Entidades\igLocal;
use PCnub2Palmeras\Comun\PdoConnection;
use PCnub2Palmeras\Comun\Error;

//require_once SRCDIR . '/Modelo/Entidades/igLocal.php';
//require_once SRCDIR . '/Clases/PdoConnection.php';



class igLocalDba extends Dba {
    
    const NOMBRE_TABLA = 'ig_local';

    public static function getLocalById($idLocal, PdoConnection $db) {
        $igLocal = false;
        $dbLocal = false;
        if (!$db) {
            $db = new PdoConnection();
            $dbLocal = true;
        }
        if ($db) {
            try {
                $sql = 'SELECT * FROM ig_local WHERE idLocal = :idLocal';
                $stm = $db->prepare($sql);
                $stm->execute(array('idLocal' => $idLocal));
                $resultado = $stm->fetch(\PDO::FETCH_ASSOC);
                $igLocal = new igLocal($resultado);
                $stm->closeCursor();
            } catch (\PDOException $e) {
                Error::displayStop(
                        array(
                            'mensaje' => 'Error ' . $e->getMessage() . '(' . $e->getCode() . ')',
                            'clase' => __CLASS__,
                            'funcion' => __FUNCTION__,
                            'accion' => 'Select ig_local By Id'
                ));
            }
        }
        if ($dbLocal) {
            unset($db);
        }
        return $igLocal;
    }

    public static function getLocalByCodLocal($codLocal, PdoConnection $db = NULL) {
        $igLocal = false;
        $dbLocal = false;
        if (!$db) {
            $db = new PdoConnection();
            $dbLocal = true;
        }
        if ($db) {
            try {
                $sql = 'SELECT * FROM ig_local WHERE codLocal = :codLocal';
                $stm = $db->prepare($sql);
                $stm->execute(array('codLocal' => $codLocal));
                $resultado = $stm->fetch(\PDO::FETCH_ASSOC);
                $igLocal = new igLocal($resultado);
                $stm->closeCursor();
            } catch (\PDOException $e) {
                Error::displayStop(
                        array(
                            'mensaje' => 'Error ' . $e->getMessage() . '(' . $e->getCode() . ')',
                            'clase' => __CLASS__,
                            'funcion' => __FUNCTION__,
                            'accion' => 'Select ig_local By CodLocal'
                ));
            }
            if ($dbLocal) {
                unset($db);
            }
            return $igLocal;
        }
    }

    public static function getLocalByNumero($numeroLocal, PDOConnection $db = NULL) {
        return self::getLocalByCodLocal($numeroLocal, $db);
    }

    public static function getListLocalesActivos(PdoConnection $db = NULL) {
        $sql = 'SELECT * FROM ig_local WHERE estado = 1 ORDER BY codLocal';
        return self::getListaLocales($sql, $db);
    }

    public static function getListaLocales($sql = NULL, PdoConnection $db = NULL) {
        $dbLocal = false;
        $listaLocales = array();
        if (!$db) {
            $db = new PdoConnection();
            $dbLocal = true;
        }
        if ($db) {
            try {
                if (!$sql) {
                    $sql = 'SELECT * FROM ig_local ORDER BY codLocal';
                }
                $result = $db->query($sql, \PDO::FETCH_ASSOC);
                foreach ($result as $local) {
                    $listaLocales[] = new igLocal($local);
                }
                $result->closeCursor();
            } catch (\PDOException $e) {
                Error::displayStop(
                        array('mensaje' => 'Error ' . $e->getMessage() . '(' . $e->getCode() . ')',
                            'clase' => __CLASS__,
                            'funcion' => __FUNCTION__,
                            'accion' => 'Select ig_local Order By CodLocal'
                ));
            }
        }
        if ($dbLocal) {
            unset($db);
        }
        return $listaLocales;
    }
}
