<?php

// Comun/Error.php

namespace PCnub2Palmeras\Comun;

/**
 * Description of Error
 *
 * @author jtibau
 * 
 * Esta versión utiliza namespaces
 * Creado 3-Dic-2015
 */

class Error {
    
    public static function displayStop(array $opciones){
        $mensaje = self::ConstructMessage($opciones);
        die ($mensaje);
    }
    public static function displayContinue(array $opciones){
        $mensaje = self::ConstructMessage($opciones);
        echo $mensaje. PHP_EOL;
    }
    
    protected static function constructMessage(array $opciones){
        if (is_array($opciones)){
            $mensajeError = '';
            if (array_key_exists('mensaje', $opciones)){
                $mensajeError.= $opciones['mensaje']. PHP_EOL;
            }
            
            if (array_key_exists('modulo', $opciones)){
                $mensajeError.= ' En:'. $opciones['modulo']. PHP_EOL;
            }
            
            if (array_key_exists('linea', $opciones)){
                $mensajeError.= ' Linea:'. $opciones['linea']. PHP_EOL;
            }
            
            if (array_key_exists('clase', $opciones)){
                $mensajeError.= ' Clase:'. $opciones['clase']. PHP_EOL;
            }
            
            if (array_key_exists('funcion', $opciones)){
                $mensajeError.= ' Función:'. $opciones['funcion']. PHP_EOL;
            }
            
            if (array_key_exists('accion', $opciones)){
                $mensajeError.= ' Acción:'. $opciones['accion']. PHP_EOL;
            }
        } else {
            $mensajeError = $opciones;
        }
        return $mensajeError;
    }
    
    public static function displayStopMysql($errorInfo, array $opciones){
        $opciones['mensaje'] = 'Error. '. $errorInfo[2]. '. '. $errorInfo[0]. $errorInfo[1];
        self::displayStop($opciones);
    }
    public static function displayContinueMysql($errorInfo, array $opciones){
        $opciones['mensaje'] = 'Error. '. $errorInfo[2]. '. '. $errorInfo[0]. $errorInfo[1];
        self::displayContinue($opciones);
    }
}
