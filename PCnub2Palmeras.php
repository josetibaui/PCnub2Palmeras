<?php

// PCnub2Palmeras.php

/*
 * 
 */


namespace PCnub2Palmeras;

require_once __DIR__ . '/autoload.php';

/*
 * Array con valores para ejecución en ambiente de desarrollo
 *   Cada elemento tiene valores para la clase y los valores para crea la clase
 */
$argumentosDefault = [
    'WebService' => [
        'clase' => 'PCnub2Palmeras\WebService\WebService',
        'argumentos' => array('locales=2,3', 'desde=2017-12-30', 'hasta=')
    ]
];

// Defaults para las dos variables
$clase = '';
$argumentos = array();

if ($argc > 2) {
    $clase = $argv[1];
    $argumentos = array_slice($argv, 2);
} else {
    // Esto es normalmente el ambiente de desarrollo,
    // el segundo argumento es el índice para tomar los valores de clase y 
    // demás argumentos del arreglo $argumentosDefault
    if ($argc > 1 && array_key_exists($argv[1], $argumentosDefault)) {
        $clase = $argumentosDefault[$argv[1]]['clase'];
        $argumentos = $argumentosDefault[$argv[1]]['argumentos'];
    }
}

if (!isset($argumentos) || !is_array($argumentos)){
    $argumentos = array();
}

// SOlo para debug y desarrollo. En producción hay que quitar estas dos lineas
//$clase = $argumentosDefault['WebService']['clase'];
//$argumentos = $argumentosDefault['WebService']['argumentos'];


if (class_exists($clase)) {
    $proceso = new $clase($argumentos);
//    $proceso->readUrl();
    if (method_exists($proceso, 'fin')) {
        $proceso->fin($argumentos);
    }
}
