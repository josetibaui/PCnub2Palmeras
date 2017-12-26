<?php

// Modelo\Entidad.php

/**
 * Description of entidad
 *
 * @author jtibau
 */

namespace PCnub2Palmeras\Modelo;

class Entidad {

    public function __construct($registro = array()) {
        if (isset($registro) && is_array($registro)) {
            $this->array2Object($registro);
        }
    }

    protected function array2Object(array $registro = array()) {
        foreach ($registro as $columna => $valor) {
            if (property_exists($this, $columna)) {
                $setter = 'set' . ucfirst($columna);
                if (method_exists($this, $setter)) {
                    $this->$setter($valor);
                } else {
                    $this->$columna = $valor;
                }
            }
        }
    }

    protected function constructSqlInsert(array $excludeFields = array()) {
        $nombresCampos = '';
        $valoresCampos = '';
        foreach (get_class_vars(__CLASS__) as $campo) {
            if (!in_array($campo, $excludeFields)) {
                $nombresCampos .= ' ' . $campo . ',';
                $valoresCampos .= ' :' . $campo . ',';
            }
        }
        return 'INSERT INTO ' . self::NOMBRE_TABLA . '(' .
                substr($nombresCampos, 0, -1) .
                ') VALUES (' .
                substr($valoresCampos, 0, -1) .
                ')';
    }

    protected function constructSqlUpdate(string $idField, array $excludeFields = array()) {
        $nombresCampos = '';
        foreach (get_class_vars(__CLASS__) as $campo) {
            if (!in_array($campo, $excludeFields) && $campo != $idField) {
                $nombresCampos .= ' '. $campo. ' = :'. $campo. ',';
            }
        }
        return 'UPDATE ' . self::NOMBRE_TABLA . ' SET' .
                substr($nombresCampos, 0, -1) .
                ' WHERE '. $idField. ' = :'. $idField;
    }
    
    protected function constructFieldsArray(array $excludeFields = array()) {
        $fieldsArray = [];
        foreach (get_class_vars(__CLASS__) as $campo) {
            $fieldsArray[$campo] = ':'. $campo;
            $stm = $db->prepare($sql);
        }
        return $fieldsArray;
    }

}
